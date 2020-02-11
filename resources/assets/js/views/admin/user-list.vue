<template>
  <div v-if="displayPage">
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-user"
          :headerTitle="$t('base.navigation.admin_user_list')"
        >
          <template v-slot:controls v-if="hasRole('owner') || hasRole('admin')">
            <el-control-wrap
              componentName="el-button"
              :tooltip="$t('pages.user_list.create_user')"
              size="mini"
              icon="el-icon-lpa-add"
              @click.native="goToPage({ name: 'user-create' })"
            />
          </template>
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col>
        <entity-data-table
          :data="users"
          :attributes="dataTableAttributes.users"
          @rowClick="onUserRowClick"
        />
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import { mapState } from 'vuex';

  import Page from '@components/page';
  import EntityDataTable from '@components/entity-data-table.vue';
  import ElCardWrap from '@components/el-card-wrap';
  import ElControlWrap from '@components/el-control-wrap';

  import UsersAPI from '@api/users';

  export default {
    name: 'user-list',

    extends: Page,

    components: { EntityDataTable, ElCardWrap, ElControlWrap },

    data() {
      return {
        isUsingNewLoading: true,
        users: []
      }
    },

    computed: {
      dataTableAttributes: {
        get() {
          return {
            users: {
              id: {
                isColumn: false
              },
              name: {
                label: this.$t('entities.general.full_name'),
                minWidth: 20
              },
              email: {
                label: this.$t('entities.general.email'),
                minWidth: 20
              },
              organizational_units: {
                label: this.$tc('entities.general.organizational_units', 2),
                minWidth: 25,
                areFiltersSorted: true,
                isFilterable: true
              },
              roles: {
                label: this.$t('entities.user.roles'),
                minWidth: 16,
                areFiltersSorted: true,
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
        const { data: users } = await UsersAPI.getAll();
        this.users = users;
      },

      onUserRowClick(user) {
        this.scrollToTop();
        this.goToPage({
          name: 'user-edit',
          params: { userId: `${user.id}` }
        });
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .user-list {
    h2 i {
      @include svg(user, $--color-primary);
    }
  }
</style>
