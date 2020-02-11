<template>
  <div class="administration">
    <el-row>
      <el-col>
        <el-card-wrap
          icon="el-icon-lpa-settings"
          :headerTitle="$t('base.navigation.administration')"
        >
        </el-card-wrap>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="24">
        <el-button @click="$router.push('admin/users')" icon="el-icon-lpa-user">
          {{ $t('base.navigation.admin_user_list') }}
        </el-button>
        <el-button @click="$router.push('admin/organizational-units')" icon="el-icon-lpa-organizational-unit">
          {{ $t('base.navigation.admin_organizational_unit_list') }}
        </el-button>
        <el-button @click="$router.push('admin/process-notifications')" icon="el-icon-lpa-process-notification">
          {{ $t('base.navigation.admin_process_notification_list') }}
        </el-button>
        <el-button @click="$router.push('admin/learning-product-codes')" icon="el-icon-lpa-settings">
          {{ $t('base.navigation.admin_learning_product_code_list') }}
        </el-button>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import Page from '@components/page';
  import ElCardWrap from '@components/el-card-wrap';

  export default {
    name: 'administration',

    extends: Page,

    components: { ElCardWrap },
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .administration {
    i.el-icon-lpa-settings {
      @include svg(settings, $--color-primary);
    }
    .el-button {
      min-width: 100px;
      height: 70px;
      i {
        width: $svg-icons-size-medium;
      }
    }
  }
</style>
