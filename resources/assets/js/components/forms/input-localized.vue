<template>
  <div class="input-localized">
    <el-card v-if="toTranslate" class="to-translate" shadow="never">
      <i>{{ toTranslate }}</i>
    </el-card>
    <div class="form-items">
      <el-form-item
        :prop="nameEn"
        :class="{ 'is-error': verrors.collect(nameEn).length }"
      >
        <input-wrap
          v-model="inputEn"
          :data-vv-as="innerDataVvAsEn"
          v-validate="innerValidateEn"
          :disabled="disabled"
          :name="nameEn"
          :maxlength="maxlength"
          :type="type"
        >
          <template v-slot:prepend>{{ $t('entities.form.en') }}</template>
        </input-wrap>
      </el-form-item>
      <el-form-item
        :prop="nameFr"
        :class="{ 'is-error': verrors.collect(nameFr).length }"
      >
        <input-wrap
          v-model="inputFr"
          :data-vv-as="innerDataVvAsFr"
          v-validate="innerValidateFr"
          :disabled="disabled"
          :name="nameFr"
          :maxlength="maxlength"
          :type="type"
        >
          <template v-slot:prepend>{{ $t('entities.form.fr') }}</template>
        </input-wrap>
      </el-form-item>
    </div>
  </div>
</template>

<script>
  import InputWrap from './input-wrap.vue';

  export default {
    name: 'input-localized',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    components: { InputWrap },

    props: {
      modelEn: {
        type: String,
      },
      modelFr: {
        type: String,
      },
      nameEn: {
        type: String,
        required: true
      },
      nameFr: {
        type: String,
        required: true
      },
      maxlength: {
        type: String | Number
      },
      toTranslate: {
        type: String
      },
      disabled: {
        type: Boolean
      },
      validate: {
        type: String | Object,
        default: ''
      },
      validateEn: {
        type: String | Object,
        default: ''
      },
      validateFr: {
        type: String | Object,
        default: ''
      },
      dataVvAsEn: {
        type: String,
        default() {
          return this.nameEn;
        }
      },
      dataVvAsFr: {
        type: String,
        default() {
          return this.nameFr;
        }
      },
      type: {
        type: String,
        default: 'input',
        validator(val) {
          // The value must match one of these strings
          return ['input', 'textarea'].indexOf(val) !== -1
        }
      }
    },

    computed: {
      innerDataVvAsEn() {
        return `${this.dataVvAsEn} (${this.$t('entities.form.en')})`;
      },
      innerDataVvAsFr() {
        return `${this.dataVvAsFr} (${this.$t('entities.form.fr')})`;
      },
      inputEn: {
        get() {
          return this.modelEn;
        },
        set(val) {
          // affect the computed value so that our field is in sync
          this.$emit('update:modelEn', val);
        }
      },
      inputFr: {
        get() {
          return this.modelFr;
        },
        set(val) {
          // affect the computed value so that our field is in sync
          this.$emit('update:modelFr', val);
        }
      },
      innerValidateEn() {
        return this.validateEn ? this.validateEn : this.validate;
      },
      innerValidateFr() {
        return this.validateFr ? this.validateFr : this.validate;
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .input-localized {
    .el-form-item:last-child {
      margin-bottom: 0px;
    }
    .to-translate {
      background-color: $--background-color-base;
      margin-bottom: 20px;
      .el-card__body {
        padding: 10px 20px;
        line-height: 1.6;
      }
    }
    .el-input-group__prepend,
    .el-input-group__append {
      width: 100px !important;
      padding: 0;
    }
  }
</style>
