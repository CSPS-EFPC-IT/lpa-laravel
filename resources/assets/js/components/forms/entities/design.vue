<template>
  <el-tabs
    :type="type"
    :tabPosition="tabPosition"
    v-model="innerActiveIndex"
    v-if="displayForm"
  >
    <el-tab-pane data-name="description">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('description') }">
          {{ $t('forms.design.tabs.description') }}
        </span>
      </template>
      <h3>{{ $t('forms.design.tabs.description') }}</h3>
      <el-form-item-wrap
        prop="learning_product_code_id"
        contextPath="forms.design.learning_product_code"
        required
      >
        <learning-product-code-search
          name="learning_product_code_id"
          :validate="'required'"
          :dataVvAs="$t('forms.design.learning_product_code.label')"
          :model.sync="form.learning_product_code_id"
          :codes="codes"
          active
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="sequence"
        contextPath="forms.design.sequence"
        required
      >
        <input-wrap
          v-model="form.sequence"
          name="sequence"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="$t('forms.design.sequence.label')"
          :min="1"
          :max="99"
          type="number"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="version"
        contextPath="forms.design.version"
        required
      >
        <input-wrap
          v-model="form.version"
          name="version"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="$t('forms.design.version.label')"
          :min="1"
          :max="99"
          type="number"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="description"
        contextPath="forms.design.description"
        required
      >
        <input-wrap
          v-model="form.description"
          name="description"
          v-validate="'required'"
          :data-vv-as="$t('forms.design.description.label')"
          maxlength="1000"
          type="textarea"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="learning_objectives"
        contextPath="forms.design.learning_objectives"
        required
      >
        <input-wrap
          v-model="form.learning_objectives"
          name="learning_objectives"
          v-validate="'required'"
          :data-vv-as="$t('forms.design.learning_objectives.label')"
          maxlength="1250"
          type="textarea"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="target_audience_description"
        contextPath="forms.design.target_audience_description"
        required
      >
        <input-wrap
          v-model="form.target_audience_description"
          name="target_audience_description"
          v-validate="'required'"
          :data-vv-as="$t('forms.design.target_audience_description.label')"
          maxlength="1250"
          type="textarea"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="outcome_types"
        contextPath="forms.design.outcome_types"
        required
      >
        <el-select-wrap
          v-model="form.outcome_types"
          name="outcome_types"
          :data-vv-as="$t('forms.design.outcome_types.label')"
          v-validate="'required'"
          :options="outcomeTypesList"
          multiple
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="is_required_training"
        contextPath="forms.design.is_required_training"
        required
      >
        <yes-no
          v-model="form.is_required_training"
          v-validate="'required'"
          :data-vv-as="$t('forms.design.is_required_training.label')"
          name="is_required_training"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="total_duration"
        contextPath="forms.design.total_duration"
        required
        class="total_duration"
      >
        <duration
          isRequired
          v-model="form.total_duration"
          :hoursMaxValue="225"
        />
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="specifications">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('specifications') }">
          {{ $t('forms.design.tabs.specifications') }}
        </span>
      </template>
      <h3>{{ $t('forms.design.tabs.specifications') }}</h3>
      <el-form-item-wrap
        v-if="isCourseInstructorLed"
        prop="possible_offering_types"
        contextPath="forms.design.possible_offering_types"
        required
      >
        <el-select-wrap
          v-model="form.possible_offering_types"
          name="possible_offering_types"
          :data-vv-as="$t('forms.design.possible_offering_types.label')"
          v-validate="'required'"
          :options="possibleOfferingTypesList"
          multiple
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="registration_mode_id"
        contextPath="forms.design.registration_mode"
        required
      >
        <el-select-wrap
          v-model="form.registration_mode_id"
          name="registration_mode_id"
          :data-vv-as="$t('forms.design.registration_mode.label')"
          v-validate="'required'"
          :options="registrationModeList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        v-if="isCourseOnlineSelfPaced"
        prop="expected_annual_participant_number"
        contextPath="forms.design.expected_annual_participant_number"
        required
      >
        <input-wrap
          v-model="form.expected_annual_participant_number"
          name="expected_annual_participant_number"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="$t('forms.design.expected_annual_participant_number.label')"
          :min="1"
          :max="500000"
          type="number"
        />
      </el-form-item-wrap>
      <form-section-group
        v-if="isCourseInstructorLed || isCourseDistance"
        v-model="form.expected_annual_participants"
        entityForm="design"
        entitySection="expected-annual-participants"
        :min="1"
        :data="{
          regionList
        }"
      />
      <el-form-item-wrap
        v-if="isCourseInstructorLed || isCourseDistance"
        prop="comments"
        contextPath="forms.design.expected_annual_participant_comments"
      >
        <input-wrap
          v-model="form.expected_annual_participant_comments"
          name="expected_annual_participant_comments"
          v-validate="''"
          :data-vv-as="$t('forms.design.expected_annual_participant_comments.label')"
          maxlength="1250"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="content">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('content') }">
          {{ $t('forms.design.tabs.content') }}
        </span>
      </template>
      <h3>{{ $t('forms.design.tabs.content') }}</h3>
      <form-section-group
        v-model="form.applicable_policies"
        entityForm="design"
        entitySection="applicable-policy"
      />
      <el-form-item-wrap
        prop="content_source_type_id"
        contextPath="forms.design.content_source_type"
        required
      >
        <el-select-wrap
          v-model="form.content_source_type_id"
          name="content_source_type_id"
          :data-vv-as="$t('forms.design.content_source_type.label')"
          v-validate="'required'"
          :options="contentSourceTypeList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="content_source_codes"
        contextPath="forms.design.content_source_codes"
        :required="isContentSourceCodesFieldRequired"
      >
        <learning-product-code-search
          name="content_source_codes"
          :validate="{ required: isContentSourceCodesFieldRequired }"
          :dataVvAs="$t('forms.design.content_source_codes.label')"
          :disabled="!isContentSourceCodesFieldRequired"
          :model.sync="form.content_source_codes"
          :codes="codes"
          multiple
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="classifications">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('classifications') }">
          {{ $t('forms.design.tabs.classifications') }}
        </span>
      </template>
      <h3>{{ $t('forms.design.tabs.classifications') }}</h3>
      <el-form-item-wrap
        prop="topics"
        contextPath="forms.design.topics"
        required
      >
        <el-tree-wrap
          v-model="form.topics"
          name="topics"
          v-validate="'required'"
          :data-vv-as="$t('forms.design.topics.label')"
          :data="topicList"
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="programs"
        contextPath="forms.design.programs"
      >
        <el-tree-wrap
          v-model="form.programs"
          name="programs"
          v-validate="''"
          :data-vv-as="$t('forms.design.programs.label')"
          :data="programList"
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="series"
        contextPath="forms.design.series"
      >
        <el-tree-wrap
          v-model="form.series"
          name="series"
          v-validate="''"
          :data-vv-as="$t('forms.design.series.label')"
          :data="seriesList"
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="curriculum_type_id"
        contextPath="forms.design.curriculum_type"
        required
      >
        <el-select-wrap
          v-model="form.curriculum_type_id"
          name="curriculum_type_id"
          :data-vv-as="$t('forms.design.curriculum_type.label')"
          v-validate="'required'"
          :options="curriculumTypeList"
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="management_accountability_framework_areas"
        contextPath="forms.design.management_accountability_framework_areas"
        required
      >
        <el-tree-wrap
          v-model="form.management_accountability_framework_areas"
          name="management_accountability_framework_areas"
          v-validate="'required'"
          :data-vv-as="$t('forms.design.management_accountability_framework_areas.label')"
          :data="managementAccountabilityFrameworkAreaList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="competencies"
        contextPath="forms.design.competencies"
      >
        <el-select-wrap
          v-model="form.competencies"
          name="competencies"
          :data-vv-as="$t('forms.design.competencies.label')"
          v-validate="''"
          :options="competenceList"
          multiple
          :multiple-limit="3"
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="target_audience_knowledge_level_id"
        contextPath="forms.design.target_audience_knowledge_level"
        required
      >
        <el-select-wrap
          v-model="form.target_audience_knowledge_level_id"
          name="target_audience_knowledge_level_id"
          :data-vv-as="$t('forms.design.target_audience_knowledge_level.label')"
          v-validate="'required'"
          :options="targetAudienceKnowledgeLevelList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="communities"
        contextPath="forms.design.communities"
        required
      >
        <el-tree-wrap
          v-model="form.communities"
          name="communities"
          v-validate="'required'"
          :data-vv-as="$t('forms.design.communities.label')"
          :data="communityList"
          sorted
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="prerequisites">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('prerequisites') }">
          {{ $t('forms.design.tabs.prerequisites') }}
        </span>
      </template>
      <h3>{{ $t('forms.design.tabs.prerequisites') }}</h3>
      <el-form-item-wrap
        prop="mandatory_prerequisites"
        contextPath="forms.design.mandatory_prerequisites"
      >
        <learning-product-code-search
          name="mandatory_prerequisites"
          :validate="''"
          :dataVvAs="$t('forms.design.mandatory_prerequisites.label')"
          :model.sync="form.mandatory_prerequisites"
          :codes="codes"
          active
          multiple
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="recommended_prerequisites"
        contextPath="forms.design.recommended_prerequisites"
      >
        <learning-product-code-search
          name="recommended_prerequisites"
          :validate="''"
          :dataVvAs="$t('forms.design.recommended_prerequisites.label')"
          :model.sync="form.recommended_prerequisites"
          :codes="codes"
          active
          multiple
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="complementary_learning_products"
        contextPath="forms.design.complementary_learning_products"
      >
        <learning-product-code-search
          name="complementary_learning_products"
          :validate="''"
          :dataVvAs="$t('forms.design.complementary_learning_products.label')"
          :model.sync="form.complementary_learning_products"
          :codes="codes"
          active
          multiple
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="clients">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('clients') }">
          {{ $t('forms.design.tabs.clients') }}
        </span>
      </template>
      <h3>{{ $t('forms.design.tabs.clients') }}</h3>
      <el-form-item-wrap
        contextPath="forms.design.prescribed_by_tbs"
        prop="prescribed_by_tbs"
        required
      >
        <yes-no
          v-model="form.prescribed_by_tbs"
          v-validate="'required'"
          :data-vv-as="$t('forms.design.prescribed_by_tbs.label')"
          name="prescribed_by_tbs"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="prescribed_by_departments"
        contextPath="forms.design.prescribed_by_departments"
      >
        <el-select-wrap
          v-model="form.prescribed_by_departments"
          name="prescribed_by_departments"
          :disabled="form.prescribed_by_tbs"
          :data-vv-as="$t('forms.design.prescribed_by_departments.label')"
          v-validate="''"
          :options="departmentList"
          multiple
          filterable
          sorted
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="recommended_by_departments"
        contextPath="forms.design.recommended_by_departments"
      >
        <el-select-wrap
          v-model="form.recommended_by_departments"
          name="recommended_by_departments"
          :disabled="form.prescribed_by_tbs"
          :data-vv-as="$t('forms.design.recommended_by_departments.label')"
          v-validate="''"
          :options="departmentList"
          multiple
          filterable
          sorted
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="timeframe">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('timeframe') }">
          {{ $t('forms.design.tabs.timeframe') }}
        </span>
      </template>
      <h3>{{ $t('forms.design.tabs.timeframe') }}</h3>
      <el-form-item-wrap
        prop="expected_pilot_start_date"
        contextPath="forms.design.expected_pilot_start_date"
      >
        <el-date-picker-wrap
          v-model="form.expected_pilot_start_date"
          v-validate="''"
          :data-vv-as="$t('forms.design.expected_pilot_start_date.label')"
          name="expected_pilot_start_date"
          :pickerOptions="expectedPilotStartDateRange"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="expected_launch_date"
        contextPath="forms.design.expected_launch_date"
        required
      >
        <el-date-picker-wrap
          v-model="form.expected_launch_date"
          v-validate="'required'"
          :data-vv-as="$t('forms.design.expected_launch_date.label')"
          name="expected_launch_date"
          :pickerOptions="expectedLaunchDateRange"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="recommended_review_interval"
        contextPath="forms.design.recommended_review_interval"
        required
      >
        <input-wrap
          v-model="form.recommended_review_interval"
          name="recommended_review_interval"
          v-validate="{ required: true, numeric: true }"
          :data-vv-as="$t('forms.design.recommended_review_interval.label')"
          :min="1"
          :max="60"
          type="number"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="costs">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('costs') }">
          {{ $t('forms.design.tabs.costs') }}
        </span>
      </template>
      <h3>{{ $t('forms.design.tabs.costs') }}</h3>
      <form-section-group
        v-model="form.additional_costs"
        entityForm="design"
        entitySection="additional-cost"
      />
      <el-form-item-wrap
        prop="internal_order"
        contextPath="forms.design.internal_order"
      >
        <input-wrap
          v-model="form.internal_order"
          name="internal_order"
          v-validate="{ regex: /[A-Z][0-9]{1,6}/ }"
          :data-vv-as="$t('forms.design.internal_order.label')"
          :placeholder="$t('forms.design.internal_order.hint')"
          v-mask="'A######'"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="comments">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('comments') }">
          {{ $t('forms.design.tabs.comments') }}
        </span>
      </template>
      <h3>{{ $t('forms.design.tabs.comments') }}</h3>
      <el-form-item-wrap
        prop="comments"
        contextPath="forms.design.comments"
      >
        <input-wrap
          v-model="form.comments"
          name="comments"
          v-validate="''"
          :data-vv-as="$t('forms.design.comments.label')"
          maxlength="2500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import Form from '../form.vue';

  import LearningProductsAPI from '@api/learning-products';
  import ListsAPI from '@api/lists';

  import LearningProductCodeSearch from '../learning-product-code-search';

  export default {
    name: 'design',

    extends: Form,

    components: { LearningProductCodeSearch },

    data() {
      return {
        codes: [],
        lists: []
      };
    },

    computed: {
      expectedPilotStartDateRange() {
        const that = this;
        return {
          disabledDate(time) {
            const potentialPilotStartDate = time.getTime();
            if (that.form.expected_launch_date) {
              let launchDate = new Date(that.form.expected_launch_date);
              // we need to increment the date by 1
              // because of the fact that when we create a date, it sets the time to 00:00:00
              let actualLaunchDate = new Date(launchDate.setDate(launchDate.getDate() + 1));
              return potentialPilotStartDate < new Date('2000-01-01') || potentialPilotStartDate > actualLaunchDate;
            } else {
              return potentialPilotStartDate < new Date('2000-01-01') || potentialPilotStartDate > new Date('2051-01-01');
            }
          }
        };
      },
      expectedLaunchDateRange() {
        const that = this;
        return {
          disabledDate(time) {
            const potentialLaunchDate = time.getTime();
            if (that.form.expected_pilot_start_date) {
              let pilotStartDate = new Date(that.form.expected_pilot_start_date);
              // increment the date by 1
              // e.g.: pilotStartDate = Feb. 18th
              //       launchDate = Feb. 19th
              // expectedLaunchDateRange = Feb. 19th - 2050-12-31
              return potentialLaunchDate < pilotStartDate || potentialLaunchDate > new Date('2051-01-01');
            } else {
              return potentialLaunchDate < new Date('2000-01-01') || potentialLaunchDate > new Date('2051-01-01');
            }
          }
        };
      },
      isContentSourceCodesFieldRequired() {
        const contentSourceType = this.contentSourceTypeList.find(contentSourceType => contentSourceType.id === this.form.content_source_type_id);
        return contentSourceType ? contentSourceType.name_key !== 'new' : false;
      },
      outcomeTypesList() {
        return this.lists['outcome-type'];
      },
      possibleOfferingTypesList() {
        return this.lists['possible-offering-type'];
      },
      registrationModeList() {
        return this.lists['registration-mode'];
      },
      regionList() {
        return this.lists['region'];
      },
      contentSourceTypeList() {
        return this.lists['content-source-type'];
      },
      topicList() {
        return this.lists['topic'];
      },
      programList() {
        return this.lists['program'];
      },
      seriesList() {
        return this.lists['series'];
      },
      curriculumTypeList() {
        return this.lists['curriculum-type'];
      },
      managementAccountabilityFrameworkAreaList() {
        return this.lists['management-accountability-framework-area'];
      },
      competenceList() {
        return this.lists['competency'];
      },
      targetAudienceKnowledgeLevelList() {
        return this.lists['target-audience-knowledge-level'];
      },
      communityList() {
        return this.lists['community'];
      },
      complementaryLearningProductList() {
        return this.lists['complementary-learning-product'];
      },
      departmentList() {
        return this.lists['department'];
      }
    },

    watch: {
      form() {
        this.formatLists();
      },
      'form.content_source_type_id'(id) {
        if (this.contentSourceTypeList.find(contentSourceType => contentSourceType.id === id).name_key === 'new') {
          this.form.content_source_codes = [];
        }
      },
      'form.prescribed_by_tbs'(isPrescribedByTbs) {
        if (isPrescribedByTbs) {
          this.form.prescribed_by_departments = [];
          this.form.recommended_by_departments = [];
        }
      }
    },

    methods: {
      async loadData() {
        const requests = [];
        requests.push(
          LearningProductsAPI.getCodes(),
          ListsAPI.getLists([
            'outcome-type',
            'possible-offering-type',
            'registration-mode',
            'region',
            'content-source-type',
            'topic',
            'program',
            'series',
            'curriculum-type',
            'management-accountability-framework-area',
            'competency',
            'target-audience-knowledge-level',
            'community',
            'department'
          ])
        );
        // Exception handled by interceptor
        const [
          { data: codes },
          { data: lists }
        ] = await Promise.all(requests);

        this.codes = codes;
        this.lists = lists;
      },

      formatLists() {
        // replace our internal organizational_units with only the ids
        // since ElementUI only need ids to populate the selected options
        this.form.content_source_codes = this.form.content_source_codes.map(code => code.id);
        this.form.mandatory_prerequisites = this.form.mandatory_prerequisites.map(prerequisite => prerequisite.id);
        this.form.recommended_prerequisites = this.form.recommended_prerequisites.map(prerequisite => prerequisite.id);
        this.form.complementary_learning_products = this.form.complementary_learning_products.map(prerequisite => prerequisite.id);
      }
    },

    created() {
      this.formatLists();
    }
  };
</script>

<style lang="scss">

</style>
