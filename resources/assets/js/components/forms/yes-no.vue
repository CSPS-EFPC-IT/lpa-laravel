<template>
  <div class="yes-no">
    <el-radio-group
      v-model="innerValue"
      :name="name"
      :class="{ 'is-error': verrors.has(name) }"
      @change="updateValue"
      @keyup.space.native="updateValue"
    >
      <el-radio-button :label="true">{{ $t('base.actions.yes') }}</el-radio-button>
      <el-radio-button :label="false">{{ $t('base.actions.no') }}</el-radio-button>
    </el-radio-group>
    <form-error :name="name"/>
  </div>
</template>

<script>
  import FormError from './error.vue';

  export default {
    name: 'yes-no',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    components: { FormError },

    props: {
      name: {
        type: String,
        required: true
      },
      value: {
        type: String | Number
      }
    },

    computed: {
      innerValue: {
        get() {
          return this.value;
        },
        set(value) {
          this.updateValue(value);
        }
      }
    },

    methods: {
      updateValue(value) {
        if (typeof value === 'object') {
          // since we just triggered the label to change the input value,
          // we need to get its control value, and cast it as a boolean
          value = !!value.target.control.value;
        }
        this.$emit('input', value);
      }
    }
  };
</script>

<style lang="scss">

</style>
