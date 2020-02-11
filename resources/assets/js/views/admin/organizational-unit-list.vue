<template>
  <div v-if="displayPage">
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-organizational-unit"
          :headerTitle="$t('base.navigation.admin_organizational_unit_list')"
        >
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <entity-data-table
          :data="organizationalUnits"
          :attributes="dataTableAttributes.organizationalUnits"
          @rowClick="onRowClick"
          @formatData="onFormatData"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import Page from '@components/page';
  import EntityDataTable from '@components/entity-data-table.vue';
  import ElCardWrap from '@components/el-card-wrap';

  import OrganizationalUnitsAPI from '@api/organizational-units';

  export default {
    name: 'organizational-unit-list',

    extends: Page,

    components: { EntityDataTable, ElCardWrap },

    data() {
      return {
        isUsingNewLoading: true,
        organizationalUnits: []
      };
    },

    computed: {
      dataTableAttributes: {
        get() {
          return {
            organizationalUnits: {
              id: {
                isColumn: false
              },
              name_key: {
                label: this.$t('entities.general.name_key'),
                minWidth: 20
              },
              name: {
                label: this.$t('entities.general.name'),
                minWidth: 20
              },
              director: {
                label: this.$t('entities.organizational_unit.director'),
                minWidth: 20
              },
              email: {
                label: this.$t('entities.general.email'),
                minWidth: 20
              },
              owner: {
                label: this.$t('entities.organizational_unit.owner'),
                minWidth: 20,
                isFilterable: true
              },
              updated_at: {
                label: this.$t('entities.general.updated'),
                minWidth: 14
              },
              updated_by: {
                isColumn: false
              }
            }
          }
        }
      }
    },

    methods: {
      async loadData() {
        const { data: { organizational_units } } = await OrganizationalUnitsAPI.getAll();
        this.organizationalUnits = organizational_units;
      },

      onRowClick(organizationalUnit) {
        this.scrollToTop();
        this.goToPage({
          name: 'organizational-unit-edit',
          params: { entityId: `${organizationalUnit.id}` }
        });
      },

      onFormatData(normEntity) {
        normEntity.owner = this.$options.filters.booleanFilter(normEntity.owner)
      }
    }
  };
</script>

<style lang="scss">

</style>
