<template>
  <div class="el-date-picker-wrap">
    <el-date-picker
      v-model="innerValue"
      :name="name"
      :type="type"
      :picker-options="pickerOptions"
      :value-format="valueFormat"
      :class="{ 'is-error': verrors.has(name) }"
      @change="updateValue"
    />
    <form-error :name="name" />
  </div>
</template>

<script>
  import FormError from './error.vue';

  export default {
    name: 'el-date-picker-wrap',

    inheritAttrs: false,

    components: { FormError },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      name: {
        type: String,
        required: true
      },
      type: {
        type: String,
        default: 'date',
        validator: value => {
          return [
            'year', 'month', 'date', 'dates', 'datetime',
            'week', 'datetimerange' ,'daterange' , 'monthrange'
          ].indexOf(value) !== -1;
        }
      },
      pickerOptions: {
        type: Object
      },
      valueFormat: {
        type: String,
        default: 'yyyy-MM-dd'
      },
      value: {
        type: String
      }
    },

    data() {
      return {
        innerValue: this.value
      };
    },

    methods: {
      updateValue(value) {
        this.$emit('input', value);
      }
    }
  };
</script>

<style lang="scss">

</style>
