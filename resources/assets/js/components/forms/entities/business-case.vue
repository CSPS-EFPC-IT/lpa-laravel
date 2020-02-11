<template>
  <el-tabs
    :type="type"
    :tabPosition="tabPosition"
    v-model="innerActiveIndex"
    v-if="displayForm"
  >
    <el-tab-pane data-name="project_objective">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('project_objective') }">
          {{ $t('forms.business_case.tabs.project_objective') }}
        </span>
      </template>
      <h3>{{ $t('forms.business_case.tabs.project_objective') }}</h3>
      <el-form-item-wrap
        prop="request_origins"
        :classes="['has-other']"
        contextPath="forms.business_case.request_origins"
        required
      >
        <el-select-other-wrap
          :modelSelect.sync="form.request_origins"
          nameSelect="request_origins"
          :dataVvAsSelect="$t('forms.business_case.request_origins.label')"
          :validateSelect="{ required: !this.isRequestOriginOther }"
          :options="requestOriginList"
          multiple
          sorted

          :modelOther.sync="form.request_origins_other"
          nameOther="request_origins_other"
          :dataVvAsOther="$t('forms.business_case.request_origins_other.label')"
          :validateOther="{ required: this.isRequestOriginOther }"
          :isChecked.sync="isRequestOriginOther"
          maxlength="100"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="business_issue"
        contextPath="forms.business_case.business_issue"
        required
      >
        <input-wrap
          v-model="form.business_issue"
          v-validate="'required'"
          :data-vv-as="$t('forms.business_case.business_issue.label')"
          name="business_issue"
          maxlength="1250"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="proposed_solution">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('proposed_solution') }">
          {{ $t('forms.business_case.tabs.proposed_solution') }}
        </span>
      </template>
      <h3>{{ $t('forms.business_case.tabs.proposed_solution') }}</h3>
      <el-form-item-wrap
        prop="short_term_learning_response"
        contextPath="forms.business_case.short_term_learning_response"
        required
      >
        <input-wrap
          v-model="form.short_term_learning_response"
          v-validate="'required'"
          :data-vv-as="$t('forms.business_case.short_term_learning_response.label')"
          name="short_term_learning_response"
          maxlength="1250"
          type="textarea"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="medium_term_learning_response"
        contextPath="forms.business_case.medium_term_learning_response"
        required
      >
        <input-wrap
          v-model="form.medium_term_learning_response"
          v-validate="'required'"
          :data-vv-as="$t('forms.business_case.medium_term_learning_response.label')"
          name="medium_term_learning_response"
          maxlength="1250"
          type="textarea"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="long_term_learning_response"
        contextPath="forms.business_case.long_term_learning_response"
        required
      >
        <input-wrap
          v-model="form.long_term_learning_response"
          v-validate="'required'"
          :data-vv-as="$t('forms.business_case.long_term_learning_response.label')"
          name="long_term_learning_response"
          maxlength="1250"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="school_priorities">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('school_priorities') }">
          {{ $t('forms.business_case.tabs.school_priorities') }}
        </span>
      </template>
      <h3>{{ $t('forms.business_case.tabs.school_priorities') }}</h3>
      <el-form-item-wrap
        prop="school_priorities"
        contextPath="forms.business_case.school_priorities"
        required
      >
        <el-select-wrap
          v-model="form.school_priorities"
          name="school_priorities"
          :data-vv-as="$t('forms.business_case.school_priorities.label')"
          v-validate="'required'"
          :options="schoolPriorityList"
          multiple
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="is_required_training"
        contextPath="forms.business_case.is_required_training"
        required
      >
        <yes-no
          v-model="form.is_required_training"
          v-validate="'required'"
          :data-vv-as="$t('forms.business_case.is_required_training.label')"
          name="is_required_training"
        />
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="target_audience">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('target_audience') }">
          {{ $t('forms.business_case.tabs.target_audience') }}
        </span>
      </template>
      <h3>{{ $t('forms.business_case.tabs.target_audience') }}</h3>
      <el-form-item-wrap
        prop="expected_annual_participant_number"
        contextPath="forms.business_case.expected_annual_participant_number"
        required
      >
        <input-wrap
          v-model="form.expected_annual_participant_number"
          name="expected_annual_participant_number"
          v-validate="{ required: true, numeric: true, regex: /[0-9]{1,6}/ }"
          :data-vv-as="$t('forms.business_case.expected_annual_participant_number.label')"
          v-mask="'######'"
          :min="1"
          :max="500000"
          type="number"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="communities"
        contextPath="forms.business_case.communities"
        required
      >
        <el-tree-wrap
          name="communities"
          v-model="form.communities"
          v-validate="'required'"
          :data-vv-as="$t('forms.business_case.communities.label')"
          :data="communityList"
          sorted
        />
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="departmental_results_framework">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('departmental_results_framework') }">
          {{ $t('forms.business_case.tabs.departmental_results_framework') }}
        </span>
      </template>
      <h3>{{ $t('forms.business_case.tabs.departmental_results_framework') }}</h3>
      <el-form-item-wrap
        prop="departmental_results_framework_indicators"
        contextPath="forms.business_case.departmental_results_framework_indicators"
        required
      >
        <el-tree-wrap
          name="departmental_results_framework_indicators"
          v-model="form.departmental_results_framework_indicators"
          v-validate="'required'"
          :data-vv-as="$t('forms.business_case.departmental_results_framework_indicators.label')"
          :data="departmentalResultsFrameworkIndicatorList"
        />
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="costs">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('costs') }">
          {{ $t('forms.business_case.tabs.costs') }}
        </span>
      </template>
      <h3>{{ $t('forms.business_case.tabs.costs') }}</h3>
      <el-form-item-wrap
        prop="cost_centre"
        contextPath="forms.business_case.cost_centre"
        required
      >
        <input-wrap
          v-model="form.cost_centre"
          v-validate="{ required: true, regex: /[A-Z][0-9]{5,5}/ }"
          :data-vv-as="$t('forms.business_case.cost_centre.label')"
          name="cost_centre"
          :placeholder="$t('forms.business_case.cost_centre.hint')"
          v-mask="'A#####'"
        />
      </el-form-item-wrap>
      <form-section-group
        v-model="form.spendings"
        entityForm="business-case"
        entitySection="spending"
        :min="1"
        :data="{
          internalResourceList,
          recurrenceList
        }"
      />
      <el-form-item-wrap
        prop="other_operational_considerations"
        contextPath="forms.business_case.other_operational_considerations"
      >
        <input-wrap
          v-model="form.other_operational_considerations"
          v-validate="''"
          :data-vv-as="$t('forms.business_case.other_operational_considerations.label')"
          name="other_operational_considerations"
          maxlength="1250"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="risks">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('risks') }">
          {{ $t('forms.business_case.tabs.risk') }}
        </span>
      </template>
      <h3>{{ $t('forms.business_case.tabs.risk') }}</h3>
      <form-section-group
        v-model="form.risks"
        entityForm="business-case"
        entitySection="risk"
        :data="{
          riskTypeList
        }"
      />
    </el-tab-pane>

    <el-tab-pane data-name="comments">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('comments') }">
          {{ $t('forms.business_case.tabs.comments') }}
        </span>
      </template>
      <h3>{{ $t('forms.business_case.tabs.comments') }}</h3>
      <el-form-item-wrap
        prop="comments"
        contextPath="forms.business_case.comments"
      >
        <input-wrap
          v-model="form.comments"
          v-validate="''"
          :data-vv-as="$t('forms.business_case.comments.label')"
          name="comments"
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
    name: 'business-case',

    extends: Form,

    data() {
      return {
        isRequestOriginOther: false,
        isInternalResourceOther: false,
        lists: []
      }
    },

    computed: {
      requestOriginList() {
        return this.lists['request-origin'];
      },

      schoolPriorityList() {
        return this.lists['school-priority'];
      },

      communityList() {
        return this.lists['community'];
      },

      departmentalResultsFrameworkIndicatorList() {
        return this.lists['departmental-results-framework-indicator'];
      },

      internalResourceList() {
        return this.lists['internal-resource'];
      },

      recurrenceList() {
        return this.lists['recurrence'];
      },

      riskTypeList() {
        return this.lists['risk-type'];
      }
    },

    methods: {
      async loadData() {
        const requests = [];
        requests.push(
          ListsAPI.getLists([
            'request-origin',
            'school-priority',
            'community',
            'departmental-results-framework-indicator',
            'internal-resource',
            'recurrence',
            'risk-type'
          ])
        );
        // Exception handled by interceptor
        const [
          { data: lists }
        ] = await Promise.all(requests);

        this.lists = lists;
      },

      bindCheckboxes() {
        this.isRequestOriginOther = !!this.form.request_origins_other;
        this.isInternalResourceOther = !!this.form.internal_resource_other;
      }
    }
  };
</script>

<style lang="scss">

</style>
