<template>
  <data-tables
    ref="table"
    :search-def="searchDef"
    class="entity-data-table"
    :custom-filters="customFilters"
    :table-props="tableProps"
    :pagination-def="paginationDef"
    :data="parsedData"
    @filter-change="onFilterChange"
    @current-page-change="scrollToTop"
    @row-click="onRowClick"
    @header-click="headerClick"
    :sort-method="$helpers.localeSort">
    <el-table-column
      v-for="(attr, i) in normalizedColumns"
      :key="i"
      :label="labels[i]"
      :prop="attr"
      :column-key="attr"
      :filters="getFilters(attr, i)"
      :min-width="minWidths[i]"
      sortable="custom"
    >
      <template slot-scope="scope">
        <span v-if="attr === 'id'">{{ scope.row.LPA_num }}</span>
        <template v-else-if="isArray(scope.row[attr])">
          <el-tag
            v-for="item in scope.row[attr]"
            :key="item.id"
            type="info"
            size="small"
            :title="item"
            disable-transitions
          >
            {{item}}
          </el-tag>
        </template>
        <template v-else-if="attr === 'created_at'">
          <span v-audit:created="scope.row"></span>
        </template>
        <template v-else-if="attr === 'updated_at'">
          <span v-audit:updated="scope.row"></span>
        </template>
        <span v-else>{{ scope.row[attr] }}</span>
      </template>
    </el-table-column>
  </data-tables>
</template>

<script>
  import _ from 'lodash';
  import { mapMutations } from 'vuex';
  import Constants from '@/constants.js';

  export default {
    name: 'entity-data-table',

    props: {
      data: {
        type: Array,
        required: true
      },
      attributes: {
        type: Object,
        required: true
      },
      tableProps: {
        type: Object
      },
      searchable: {
        type: Boolean,
        default: true
      }
    },

    data() {
      return {
        filters: {},
        // used in order to sync the pagination with the filters
        customFilters: [],
        paginationDef: {
          layout: 'total, prev, pager, next, sizes',
          // @todo: ideally get values from localstorage
          pageSize: Constants.PAGE_SIZE_DEFAULT,
          pageSizes: Constants.PAGE_SIZES,
          currentPage: 1
        }
      }
    },

    watch: {
      '$i18n.locale'(to, from) {
        this.resetFilters();
      }
    },

    computed: {
      searchDef() {
         return {
          show: this.searchable,
          inputProps: {
            placeholder: this.$t('base.actions.search')
          }
        }
      },
      /**
       * Gets all the root keys of the attributes as sorted props
       * @return { Array }
       */
      props() {
        return Object.keys(this.attributes).sort();
      },

      /**
       * Gets all the objects that are not set isColumn: false
       * @return { Array }
       */
      columns() {
        const columns = _.chain(this.attributes).pickBy((val, key) => {
          return val.isColumn === false ? false : true;
        }).keys().value();

        if (!columns.length) {
          this.$log.error('Columns are required to render the data-table. Make sure you specified which attributes are columns using `isColumn` property.')
        }
        return columns;
      },

      /**
       * Creates a simlified version of columns, which only keeps the first part of the property path
       * e.g.: current_process.definition => current_process
       * @return { Array }
       */
      normalizedColumns() {
        return this.columns.map(column => {
          return column.indexOf('.') !== -1 ? column.split('.')[0] : column;
        });
      },

      /**
       * Gets all the labels properties from the attributes objects
       * @return { Array }
       */
      labels() {
        return _.compact(_.map(this.attributes, 'label'));
      },

      /**
       * Gets all the minWidth properties from the attributes objects
       * @return { Array }
       */
      minWidths() {
        return _.compact(_.map(this.attributes, 'minWidth'));
      },

      /**
       * Used in order to normalize the props of an entity
       * so that we only get what we need on rendering.
       * E.g.: entity.organizational_unit.name -> entity.organizational_unit
       *       entity.organizational_units[x].name -> entity.organizational_units[x]
       *       entity.current_process.definition.name -> entity.current_process
       * @return { Array }
       */
      parsedData() {
        // Keep a reference to translated string instead of resolving through $t() on each row.
        // This improves the performance by alot when rendering many entries.
        const notApplicable = this.$t('entities.general.na');

        return _.cloneDeep(this.data).map(entity => {
          // equivalent of _.pick, but keeps the property if it is found in the attributes
          // but is undefined / null
          const normEntity = _.pickBy(entity, (val, key) => {
            // if key is found in desired column names
            if (this.props.includes(key)) {
              return true;
            }
            // else, loop through every desired entity props
            // and check for possibilities like
            // current_process.definition
            // since we only want current_process to show up in the column name
            // we cannot put current_process.definition in the props
            // hence why we need to check if the column name
            // current_process is found in current_process.definition string
            for (const attr of this.props) {
              if (attr.indexOf(key) !== -1) {
                return true;
              }
            }
          });
          // formatting
          this.$emit('formatData', normEntity);

          // sort to make correlation possible with the props
          Object.keys(normEntity).sort().forEach((key, i) => {
            const value = _.get(normEntity, key);
            // check if we need to return the name of the path to the value
            // given in the entity props
            if (key === 'id') {
              // add a new prop LPA_num, filtered by the LPANumFilter,
              // so that we can search by it without modifying the original id
              normEntity.LPA_num = this.$options.filters.LPANumFilter(value);
            }
            if (_.isArray(value)) {
              normEntity[key] = _.map(normEntity[this.props[i]], 'name');
            } else if (_.isObject(_.get(normEntity, key))) {
              normEntity[key] = _.get(normEntity, this.props[i]).name;
            } else if (_.isNull(value)) {
              normEntity[key] = notApplicable;
            }
          });
          return normEntity;
        });
      }
    },

    methods: {
      ...mapMutations([
        'addFilteredDataTable',
        'deleteFilteredDataTable'
      ]),

      /**
       * Scroll the current page up. Called when navigating from one table page to the other.
       */
      scrollToTop() {
        document.querySelectorAll('.el-main')[0].scrollTop = 0;
        // IE11 scroll to top
        document.querySelectorAll('.content-wrap')[0].scrollTop = 0
      },

      /**
       * Handle click on sortable and filterable columns
       * since ElementUI has no behavior when clicking a column that has both methods
       * @param { Object } col - The column data that was clicked
       * @param { Object } e - Event triggered
       */
      headerClick(col, e) {
        if (!this.$refs.table) {
          this.$log.warn('[Mixin][Table][utils] Missing ref="table" reference on the table element.');
          return;
        }
        if (col.sortable && !_.isUndefined(col.filters)) {
          this.$refs.table.$refs.elTable.$refs.tableHeader.handleSortClick(e, col);
        }
      },

      /**
       * Grabs the attrs values,
       * put them in an array, remove dupplicates
       * then rearrange its format to match ElementUI's
       * and format it so that we do not show '-' as options
       * @note: flatMapDeep is mainly used in user-list
       *        since we may have multiple organizational units associated to a user
       * @param { Array } list - The data to get filters from
       * @param { String } attr - the attribute to look for the the data
       * @param { Boolean } isSorted - Whether or not the filters are sorted alphabetically based on language
       */
      getColumnFilters(list, attr, isSorted) {
        const filters = _.chain(list)
                .mapValues(attr)
                .toArray().flatMapDeep().uniq()
                .map((val, key) => {
                  return { text: val, value: val };
                })
                .value();
        return !filters.length ? null : (isSorted ? filters.sort((a, b) => this.$helpers.localeSort(a, b, 'text')) : filters);
      },

      /**
       * Gets the filters based on the attr from the normalizedColumns.
       * Since we need to reference the attributes, which contains the complex naming (e.g.: 'current_process.definition')
       * we need to also pass an index to be able to reference the same attr passed in from the non-normalized columns
       * @param { String } attr
       * @param { Number } i
       * @return { Array|Null }
       */
      getFilters(attr, i) {
        if (this.attributes[this.columns[i]].isFilterable) {
          return this.getColumnFilters(
            this.parsedData,
            attr,
            this.attributes[this.columns[i]].areFiltersSorted
          );
        }
        return null;
      },

      /**
       * Called upon changing the filters on a column
       * @param { Object } columFilters - column's filters
       */
      onFilterChange(columFilters) {
        // store the current filter changed
        // since Object.keys and Object.values return an array and that we are only dealing with 1 applied filter at a time,
        // just take the first and only one index
        const filter = Object.values(columFilters)[0];
        if (filter.length) {
          // apply filter
          this.filters[Object.keys(columFilters)[0]] = Object.values(columFilters)[0];
        } else {
          // filter removed
          delete this.filters[Object.keys(columFilters)[0]];
        }

        // If there are active filters, make sure to prompt the user before changing the page language.
        this[_.isEmpty(this.filters) ? 'deleteFilteredDataTable' : 'addFilteredDataTable'](this.$options.name);

        // reset custom filters so that we can rebuild them
        this.customFilters = [];
        // loop through all the applied filters, and build the customFilters
        for (let i = 0; i < Object.keys(this.filters).length; i++) {
          this.customFilters[i] = { vals: Object.values(this.filters)[i] };
        }
      },

      /**
       * Called when toggling language in order to reset the filters on confirmation
       */
      resetFilters() {
        // if no filters are set, just proceed updating the language
        if (!_.isEmpty(this.filters)) {
          this.filters = {};
          this.customFilters = [];
          this.$refs.table.$refs.elTable.clearFilter();
          this.deleteFilteredDataTable(this.$options.name);
        }
      },

      isArray(rowValue) {
        return _.isArray(rowValue);
      },

      onRowClick(entity) {
        this.$emit('rowClick', entity);
      }
    },

    mounted() {
      // fix pagination styling since vue-data-tables doesn't support  passing 'background as property
      this.$refs.table.$el.querySelector('.el-pagination').classList.add('is-background');
    },

    beforeDestroy() {
      this.deleteFilteredDataTable(this.$options.name);
    }
  }
</script>

<style lang="scss">
  .entity-data-table {
    .tool-bar {
      margin-bottom: 0 !important;
      .search {
        margin-bottom: 20px;
        float: right;
      }
    }
  }
</style>
