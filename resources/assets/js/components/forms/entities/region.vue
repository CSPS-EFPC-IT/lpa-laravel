<template>
  <div class="region">
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.region_id`"
      contextPath="forms.offering_and_enrolment_estimates.regions.region"
      required
    >
      <el-select-wrap
        v-model="form.region_id"
        v-validate="'required'"
        :data-vv-as="$t('forms.offering_and_enrolment_estimates.regions.region.label')"
        :name="`${fieldNamePrefix}.region_id`"
        :options="data.regionList"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.regional_annual_bilingual_offering_number`"
      contextPath="forms.offering_and_enrolment_estimates.regions.regional_annual_bilingual_offering_number"
      required
    >
      <input-wrap
        v-model="form.regional_annual_bilingual_offering_number"
        :name="`${fieldNamePrefix}.regional_annual_bilingual_offering_number`"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="$t('forms.offering_and_enrolment_estimates.regions.regional_annual_bilingual_offering_number.label')"
        type="number"
        :min="0"
        :max="365"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.regional_annual_english_offering_number`"
      contextPath="forms.offering_and_enrolment_estimates.regions.regional_annual_english_offering_number"
      required
    >
      <input-wrap
        v-model="form.regional_annual_english_offering_number"
        :name="`${fieldNamePrefix}.regional_annual_english_offering_number`"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="$t('forms.offering_and_enrolment_estimates.regions.regional_annual_english_offering_number.label')"
        type="number"
        :min="0"
        :max="365"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.regional_annual_french_offering_number`"
      contextPath="forms.offering_and_enrolment_estimates.regions.regional_annual_french_offering_number"
      required
    >
      <input-wrap
        v-model="form.regional_annual_french_offering_number"
        :name="`${fieldNamePrefix}.regional_annual_french_offering_number`"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="$t('forms.offering_and_enrolment_estimates.regions.regional_annual_french_offering_number.label')"
        type="number"
        :min="0"
        :max="365"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.regional_annual_simultaneous_interpretation_offering_number`"
      contextPath="forms.offering_and_enrolment_estimates.regions.regional_annual_simultaneous_interpretation_offering_number"
      required
      v-if="isCourseInstructorLed"
    >
      <input-wrap
        v-model="form.regional_annual_simultaneous_interpretation_offering_number"
        :name="`${fieldNamePrefix}.regional_annual_simultaneous_interpretation_offering_number`"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="$t('forms.offering_and_enrolment_estimates.regions.regional_annual_simultaneous_interpretation_offering_number.label')"
        type="number"
        :min="0"
        :max="365"
      />
    </el-form-item-wrap>
  </div>
</template>

<script>
  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import InputWrap from '../input-wrap';

  export default {
    name: 'region',

    components: { ElFormItemWrap, ElSelectWrap, InputWrap },

    inject: [
      // Gives us the ability to inject validation in child components
      // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
      '$validator',
      'isCourseInstructorLed'
    ],

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
        return 'offering_regions.' + this.index;
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
          regional_annual_bilingual_offering_number: null,
          regional_annual_english_offering_number: null,
          regional_annual_french_offering_number: null,
          regional_annual_simultaneous_interpretation_offering_number: null
        }
      };
    }
  };
</script>

<style lang="scss">

</style>
