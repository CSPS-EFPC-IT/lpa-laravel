<template>
  <div class="expected-annual-participants">
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.region_id`"
      contextPath="forms.design.expected_annual_participants.region"
      required
    >
      <el-select-wrap
        v-model="form.region_id"
        v-validate="'required'"
        :data-vv-as="$t('forms.design.expected_annual_participants.region.label')"
        :name="`${fieldNamePrefix}.region_id`"
        :options="data.regionList"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.expected_annual_participant_number`"
      contextPath="forms.design.expected_annual_participants.number"
      required
    >
      <input-wrap
        v-model="form.expected_annual_participant_number"
        v-validate="'required'"
        :data-vv-as="$t('forms.design.expected_annual_participants.number.label')"
        :name="`${fieldNamePrefix}.expected_annual_participant_number`"
        :min="1"
        :max="500000"
        type="number"
      />
    </el-form-item-wrap>
  </div>
</template>

<script>
  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import InputWrap from '../input-wrap';

  export default {
    name: 'expected-annual-participants',

    components: { ElFormItemWrap, ElSelectWrap, InputWrap },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      data: {
        type: Object,
        required: true
      },
      index: {
        type: Number,
        required: true
      },
      value: {
        type: Object
      }
    },

    computed: {
      fieldNamePrefix() {
        return 'expected_annual_participants.' + this.index;
      },
      form: {
        get() {
          return this.value;
        },
        set(val) {
          this.$emit('update:value', val);
        }
      }
    },

    data() {
      return {
        // this is used when adding a group
        // so that we know what properties to be aware of when adding a group
        defaults: {
          region_id: null,
          expected_annual_participant_number: null
        }
      };
    }
  };
</script>

<style lang="scss">

</style>
