<template>
  <div v-if="displayPage">
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-process-notification"
          :headerTitle="$t('base.navigation.admin_process_notification_list')"
        >
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <entity-data-table
          :data="processNotifications"
          :attributes="dataTableAttributes.processNotifications"
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

  import ProcessNotificationsAPI from '@api/process-notifications';

  export default {
    name: 'process-notification-list',

    extends: Page,

    components: { EntityDataTable, ElCardWrap },

    data() {
      return {
        isUsingNewLoading: true,
        processNotifications: []
      };
    },

    computed: {
      dataTableAttributes: {
        get() {
          return {
            processNotifications: {
              id: {
                isColumn: false
              },
              name_key: {
                label: this.$t('entities.general.name_key'),
                minWidth: 20
              },
              process_definition: {
                label: this.$t('entities.process.label'),
                areFiltersSorted: true,
                isFilterable: true,
                minWidth: 20
              },
              name: {
                label: this.$t('entities.general.name'),
                minWidth: 20
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
        const { data: { process_notifications } } = await ProcessNotificationsAPI.getAll();
        this.processNotifications = process_notifications;
      },

      onRowClick(processNotification) {
        this.scrollToTop();
        this.goToPage({
          name: 'process-notification-edit',
          params: { entityId: `${processNotification.id}` }
        });
      },

      onFormatData(normEntity) {
        normEntity.name_key = normEntity.process_definition.name_key + '.' + normEntity.name_key;
        normEntity.process_definition = normEntity.process_definition.name;
      }
    }
  };
</script>

<style lang="scss">

</style>
