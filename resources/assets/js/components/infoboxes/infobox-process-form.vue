<template>
  <div class="infobox-process-form">
    <el-card-wrap
      icon="el-icon-lpa-form"
      :headerTitle="processInstanceForm.definition.name"
    >
      <template v-slot:controls>
        <el-control-wrap
          v-if="hasRole('process-contributor') || hasRole('admin')"
          v-model="isClaimedInner"
          componentName="el-checkbox-button"
          :tooltip="isClaimedTooltipContent"
          class="claim-switch"
          icon="el-icon-lpa-edit"
        />
        <el-control-wrap
          v-if="hasRole('admin')"
          componentName="el-button"
          :disabled="!canRelease"
          :tooltip="$t('components.tooltip.release_form')"
          type="danger"
          size="mini"
          icon="el-icon-lpa-release"
          plain
          @click.native="onReleaseFormConfirm"
        />
      </template>
      <dl>
        <dt>{{ $t('entities.general.status') }}</dt>
        <dd><span :class="'state ' + processInstanceForm.state.name_key">{{ processInstanceForm.state.name }}</span></dd>
      </dl>
      <dl>
        <dt>{{ $tc('entities.form.assigned_organizational_units') }}</dt>
        <dd>{{ processInstanceForm.organizational_unit ? processInstanceForm.organizational_unit.name : $t('entities.general.none') }}</dd>
      </dl>
      <dl>
        <dt>{{ $t('entities.form.current_editor') }}</dt>
        <dd>
          {{
            processInstanceForm.current_editor ?
            processInstanceForm.current_editor.name :
            $t('entities.general.none')
          }}
        </dd>
      </dl>
      <dl>
        <dt>{{ $t('entities.general.updated') }}</dt>
        <dd v-audit:updated="processInstanceForm"></dd>
      </dl>
    </el-card-wrap>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  import ElCardWrap from '@components/el-card-wrap';
  import ElControlWrap from '@components/el-control-wrap';

  export default {
    name: 'infobox-process-form',

    components: { ElCardWrap, ElControlWrap },

    props: {
      processInstanceForm: {
        type: Object,
        required: true
      },
      isClaimed: {
        type: Boolean,
        required: true
      },
      canRelease: {
        type: Boolean,
        default: false
      }
    },

    computed: {
      ...mapGetters('users', [
        'hasRole'
      ]),

      isClaimedTooltipContent() {
        return this.isClaimedInner ? this.$t('components.tooltip.release_form') : this.$t('components.tooltip.claim_form');
      },

      isClaimedInner: {
        get() {
          return this.isClaimed;
        },
        set(val) {
          this.$emit('update:isClaimed', val);
        }
      }
    },

    methods: {
      onReleaseFormConfirm() {
        this.confirmReleaseForm().then(async () => {
          this.$emit('releaseForm');
        }).catch(() => false);
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  .infobox-process-form {
    h2 i {
      // icon is not the same ratio as others...
      @include size($svg-icons-size-small);
    }

    .el-card__header > div {
      .claim-switch {
        .el-checkbox-button__inner {
          // since we cannot use size on a checkbox-button alone
          // we need to modify its style to mock a size="mini"
          border-radius: 4px;
          padding: 7px 15px;
          border: $--border-base;
        }
        i.el-icon-lpa-edit {
          @include svg(edit, $--color-primary);
        }
        &.is-checked i.el-icon-lpa-edit {
          @include svg(edit, $--color-white);
        }
        &:hover, &:focus {
          &.is-checked .el-checkbox-button__inner {
           background-color: $--color-primary-light-3;
          }
        }
      }
    }
  }
</style>
