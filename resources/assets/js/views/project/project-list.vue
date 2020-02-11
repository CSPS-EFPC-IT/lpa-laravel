<template>
  <div v-if="displayPage">
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-projects"
          :headerTitle="$t('base.navigation.projects')"
        >
          <template v-slot:controls v-if="hasRole('owner') || hasRole('admin')">
            <el-control-wrap
              componentName="el-button"
              :tooltip="$t('pages.project_list.create_project')"
              size="mini"
              icon="el-icon-lpa-add"
              @click.native="goToPage({ name: 'project-create' })"
            />
          </template>
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <entity-data-table
          :data="projects"
          :attributes="dataTableAttributes.projects"
          @rowClick="onProjectRowClick"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import Page from '@components/page';
  import EntityDataTable from '@components/entity-data-table.vue';
  import ElCardWrap from '@components/el-card-wrap';
  import ElControlWrap from '@components/el-control-wrap';
  import ProjectsAPI from '@api/projects';

  export default {
    name: 'project-list',

    extends: Page,

    components: { EntityDataTable, ElCardWrap, ElControlWrap },

    data() {
      return {
        isUsingNewLoading: true,
        projects: []
      }
    },

    computed: {
      dataTableAttributes: {
        get() {
          return {
            projects: {
              id: {
                label: this.$t('entities.general.lpa_num'),
                minWidth: 36
              },
              name: {
                label: this.$t('entities.general.name')
              },
              organizational_unit: {
                label: this.$tc('entities.general.organizational_units'),
                areFiltersSorted: true,
                isFilterable: true
              },
              updated_at: {
                label: this.$t('entities.general.updated')
              },
              updated_by: {
                isColumn: false
              },
              state: {
                label: this.$t('entities.general.status'),
                areFiltersSorted: true,
                isFilterable: true
              },
              'current_process.definition': {
                label: this.$t('entities.process.current'),
                isFilterable: true
              }
            }
          }
        }
      }
    },

    methods: {
      async loadData() {
        const response = await ProjectsAPI.getAll();
        this.projects = response.data.projects;
      },

      onProjectRowClick(project) {
        this.scrollToTop();
        this.goToPage({
          name: 'project',
          params: { entityId: `${project.id}` }
        });
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .project-list {
    i.el-icon-lpa-projects {
      @include svg(projects, $--color-primary);
    }
  }
</style>
