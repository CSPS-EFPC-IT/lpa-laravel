<template>
  <div class="learning-product-code-search">
    <el-select-wrap
      :value.sync="model"
      v-validate="validate"
      :data-vv-as="dataVvAs"
      :name="name"
      optionLabel="code"
      defaultFirstOption
      filterable
      remote
      :multiple="multiple"
      :remoteMethod="querySearchAsync"
      :disabled="disabled"
      :options="options"
      :isLoading="loading"
      @input="updateValue($event)"
    >
    </el-select-wrap>
  </div>
</template>

<script>
  import ElSelectWrap from './el-select-wrap';

  import LearningProductCodesAPI from '@api/learning-product-codes';

  export default {
    name: 'learning-product-code-search',

    components: { ElSelectWrap },

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      name: {
        type: String,
        required: true
      },
      model: {
        type: Number | Array,
        required: true
      },
      validate: {
        type: Object | String,
        default: ''
      },
      dataVvAs: {
        type: String,
        default: ''
      },
      codes: {
        type: Array,
        required: true
      },
      // Search should only use active codes.
      active: {
        type: Boolean,
        default: false
      },
      disabled: {
        type: Boolean
      },
      multiple: {
        type: Boolean,
        default: false
      }
    },

    data() {
      return {
        options: [],
        loading: false
      };
    },

    watch: {
      codes: {
        immediate: true,
        handler() {
          // filter through the codes and return the ones
          // that corresponds to the ids received
          if (!this.model) {
            this.options = [];
          } else {
            this.options = _(this.codes).keyBy('id').at(this.model).value();
          }
        }
      }
    },

    methods: {
      async querySearchAsync(code) {
        if (!_.isEmpty(code) && /[a-zA-Z0-9\-]/.test(code)) {
          this.loading = true;
          const params = { code };
          if (this.active) {
            params.active = 1;
          }
          const response = await LearningProductCodesAPI.search(params);
          this.options = response.data;
          this.loading = false;
        } else {
          this.options = [];
        }
      },

      updateValue(value) {
        this.$emit('update:model', value);
      }
    }
  };
</script>

<style lang="scss">

</style>
