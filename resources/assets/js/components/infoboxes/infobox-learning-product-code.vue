<template>
  <div class="infobox-learning-product-code">
    <el-card-wrap
      icon="el-icon-lpa-settings"
      :headerTitle="learningProductCode.code"
    >
      <template v-slot:controls v-if="hasRole('admin')">
        <el-control-wrap
          componentName="el-button"
          :tooltip="$t('components.tooltip.edit_learning_product_code')"
          size="mini"
          icon="el-icon-lpa-edit"
          @click.native="edit()"
        />
        <el-control-wrap
          componentName="el-button"
          :tooltip="$t('components.tooltip.delete_learning_product_code')"
          type="danger"
          size="mini"
          icon="el-icon-lpa-delete"
          :disabled="!canDelete"
          plain
          @click.native="onDeleteConfirm()"
        />
      </template>
      <dl>
        <dt>{{ $t('entities.general.active') }}</dt>
        <dd>{{ learningProductCode.active | booleanFilter }}</dd>
      </dl>
      <dl>
        <dt>{{ $t('entities.general.comments') }}</dt>
        <dd>{{ learningProductCode.comments | nullFilter }}</dd>
      </dl>
      <dl>
        <dt>{{ $t('entities.general.created') }}</dt>
        <dd v-audit:created="learningProductCode"></dd>
      </dl>
      <dl>
        <dt>{{ $t('entities.general.updated') }}</dt>
        <dd v-audit:updated="learningProductCode"></dd>
      </dl>
    </el-card-wrap>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import ElCardWrap from '@components/el-card-wrap';
import ElControlWrap from '@components/el-control-wrap';

export default {
  name: 'infobox-learning-product-code',

  components: { ElCardWrap, ElControlWrap },

  props: {
    learningProductCode: {
      type: Object,
      required: true
    },
    canDelete: {
      type: Boolean,
      default: false
    }
  },

  computed: {
    ...mapGetters('users', [
      'hasRole'
    ])
  },

  methods: {
    edit() {
      this.$router.push(`${this.learningProductCode.id}/edit`);
    },

    onDeleteConfirm() {
      this.confirmDelete({
        title: this.$t('components.notice.title.delete_learning_product_code'),
        message: this.$t('components.notice.message.delete_learning_product_code')
      }).then(() => {
        this.$emit('delete');
      }).catch(() => false);
    }
  }
};
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .infobox-learning-product-code {
    h2 i {
      // override default icon color
      @include svg(settings, $--color-primary);
    }
  }
</style>
