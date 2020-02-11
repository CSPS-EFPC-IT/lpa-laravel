<template>
  <el-tabs
    :type="type"
    :tabPosition="tabPosition"
    v-model="innerActiveIndex"
    v-if="displayForm"
  >
    <el-tab-pane data-name="regions"
      v-if="isCourseInstructorLed || isCourseDistance"
    >
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('regions') }">
          {{ $t('forms.offering_and_enrolment_estimates.tabs.regions') }}
        </span>
      </template>
      <h3>{{ $t('forms.offering_and_enrolment_estimates.tabs.regions') }}</h3>
      <form-section-group
        v-model="form.offering_regions"
        entityForm="offering-and-enrolment-estimates"
        entitySection="region"
        :min="1"
        :data="{
          regionList
        }"
      />
    </el-tab-pane>
    <el-tab-pane data-name="comments"
      v-if="isCourseInstructorLed || isCourseDistance"
    >
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('comments') }">
          {{ $t('forms.offering_and_enrolment_estimates.tabs.comments') }}
        </span>
      </template>
      <h3>{{ $t('forms.offering_and_enrolment_estimates.tabs.comments') }}</h3>
      <el-form-item-wrap
        prop="comments"
        contextPath="forms.offering_and_enrolment_estimates.comments"
      >
        <input-wrap
          v-model="form.comments"
          name="comments"
          v-validate="''"
          :data-vv-as="$t('forms.offering_and_enrolment_estimates.comments.label')"
          maxlength="2500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import Form from '../form.vue';

  import ListsAPI from '@api/lists';

  export default {
    name: 'offering-and-enrolment-estimates',

    extends: Form,

    data() {
      return {
        lists: []
      };
    },

    computed: {
      regionList() {
        return this.lists['region'];
      }
    },

    methods: {
      async loadData() {
        const requests = [];
        requests.push(
          ListsAPI.getLists([
            'region'
          ])
        );
        // Exception handled by interceptor
        const [
          { data: lists }
        ] = await Promise.all(requests);

        this.lists = lists;
      }
    }
  };
</script>

<style lang="scss">

</style>
