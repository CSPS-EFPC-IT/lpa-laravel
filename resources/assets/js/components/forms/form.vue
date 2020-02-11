<template>

</template>

<script>
  import { mapGetters } from 'vuex';

  import FormError from './error.vue';

  import ElFormItemWrap from './el-form-item-wrap';
  import ElSelectOtherWrap from './el-select-other-wrap';
  import ElSelectWrap from './el-select-wrap';
  import ElDatePickerWrap from './el-date-picker-wrap';
  import InputWrap from './input-wrap';
  import FormSectionGroup from './form-section-group';
  import ElTreeWrap from './el-tree-wrap';
  import ElPopoverWrap from '../el-popover-wrap';
  import Duration from './duration';
  import InputLocalized from './input-localized';
  import YesNo from './yes-no';

  export default {
    name: 'form',

    components: {
      FormError,
      ElFormItemWrap,
      ElSelectOtherWrap,
      ElSelectWrap,
      ElDatePickerWrap,
      InputWrap,
      FormSectionGroup,
      ElTreeWrap,
      ElPopoverWrap,
      Duration,
      InputLocalized,
      YesNo
    },

    provide() {
      return {
        isCourse: this.isCourse,
        isCourseInstructorLed: this.isCourseInstructorLed,
        isCourseDistance: this.isCourseDistance,
        isCourseOnlineSelfPaced: this.isCourseOnlineSelfPaced
      };
    },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      processEntityType: {
        type: String,
        required: true
      },
      processEntity: {
        type: Object,
        required: true
      },
      formData: {
        type: Object
      },
      meta: {
        type: Object,
        default: {}
      },
      errorTabs: {
        type: Array
      },
      type: {
        type: String
      },
      tabPosition: {
        type: String
      },
      activeIndex: {
        type: String,
        required: true
      }
    },

    data() {
      return {
        displayForm: false
      };
    },

    computed: {
      ...mapGetters('lists', {
        getList: 'list'
      }),

      form: {
        get() {
          return this.formData;
        },
        set(data) {
          this.$emit('update:formData', data);
        }
      },

      innerActiveIndex: {
        get() {
          return this.activeIndex;
        },
        set(value) {
          this.$emit('update:activeIndex', value);
        }
      },

      isCourse() {
        return this.processEntity.type && this.processEntity.type.name_key === 'course';
      },
      isCourseInstructorLed() {
        return this.isCourse && this.processEntity.sub_type.name_key === 'instructor-led';
      },
      isCourseDistance() {
        return this.isCourse && this.processEntity.sub_type.name_key === 'distance-learning';
      },
      isCourseOnlineSelfPaced() {
        return this.isCourse && this.processEntity.sub_type.name_key === 'online-self-paced';
      }
    },

    watch: {
      '$i18n.locale'(to, from) {
        this.onLanguageUpdate();
      },

      form() {
        this.bindCheckboxes();
      },

      displayForm() {
        // watch for when the form is displayed, and then wait for it to be rendered
        // so that we can count the number of tab panes afterwards
        this.$emit('mounted');
      }
    },

    methods: {
      async loadData() {},

      onLanguageUpdate() {
        this.loadData();
      },

      bindCheckboxes() {}
    },

    async created() {
      await this.loadData();
      this.bindCheckboxes();
      this.displayForm = true;
    }
  };
</script>

<style lang="scss">

</style>
