<template>
  <el-tabs
    :type="type"
    :tabPosition="tabPosition"
    v-model="innerActiveIndex"
    v-if="displayForm"
  >
    <el-tab-pane data-name="assessment">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('assessment') }">
          {{ $t('forms.form_assessment.tabs.overall_assessment') }}
        </span>
      </template>
      <h3>{{ $t('forms.form_assessment.tabs.overall_assessment') }}</h3>
      <el-form-item-wrap
        prop="assessment_date"
        contextPath="forms.form_assessment.assessment_date"
        required
      >
        <el-date-picker-wrap
          v-model="form.assessment_date"
          v-validate="'required'"
          :data-vv-as="$t('forms.form_assessment.assessment_date.label')"
          name="assessment_date"
          :pickerOptions="assessment_date_options"
        />
      </el-form-item-wrap>
      <template v-if="form.allow_entity_cancellation">
        <el-form-item-wrap
          class="entity-cancellation"
          :contextPath="`forms.form_assessment.entity_cancellation.${snakeCase(processEntityType)}.is_entity_cancelled`"
          prop="is_entity_cancelled"
        >
          <el-radio-group
            v-model="form.is_entity_cancelled"
            v-validate="''"
            name="is_entity_cancelled"
            @change="onEntityCancelledChange"
          >
            <el-radio :label=false>{{ $t(`forms.form_assessment.entity_cancellation.${snakeCase(processEntityType)}.is_entity_cancelled.options.false`) }}</el-radio>
            <el-radio :label=true>{{ $t(`forms.form_assessment.entity_cancellation.${snakeCase(processEntityType)}.is_entity_cancelled.options.true`) }}</el-radio>
          </el-radio-group>
          <el-collapse-transition>
            <div v-show="form.is_entity_cancelled">
              <el-alert
                :closable="false"
                :title="$t(`forms.form_assessment.entity_cancellation.${snakeCase(processEntityType)}.cancel_entity`)"
                type="warning"
                show-icon
              />
            </div>
          </el-collapse-transition>
          <form-error name="is_entity_cancelled"/>
        </el-form-item-wrap>
        <el-form-item-wrap
          prop="entity_cancellation_rationale"
          :contextPath="`forms.form_assessment.entity_cancellation.${snakeCase(processEntityType)}.entity_cancellation_rationale`"
          :required="form.is_entity_cancelled"
        >
          <input-wrap
            v-model="form.entity_cancellation_rationale"
            :disabled="!form.is_entity_cancelled"
            v-validate="{ required: form.is_entity_cancelled }"
            :data-vv-as="$t(`forms.form_assessment.entity_cancellation.${snakeCase(processEntityType)}.entity_cancellation_rationale.label`)"
            name="entity_cancellation_rationale"
            maxlength="2500"
            type="textarea"
          />
        </el-form-item-wrap>
      </template>
    </el-tab-pane>
    <el-tab-pane
      :data-name="item.assessed_process_form.replace(/-/g, '_')"
      v-for="(item, index) in form.assessments"
      :key="item.id"
    >
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes(item.assessed_process_form.replace(/-/g, '_')) }">
          {{ getSectionTitle(item) }}
        </span>
      </template>
      <h3>{{ getSectionTitle(item) }}</h3>
      <el-form-item-wrap
        prop="process_form_decision_id"
        :description="$t('forms.form_assessment.process_form_decision_id.description', { assessed_form_title: getSectionTitle(item) })"
        contextPath="forms.form_assessment.process_form_decision_id"
        :required="!form.is_entity_cancelled"
      >
        <el-select-wrap
          v-model="item.process_form_decision_id"
          :disabled="form.is_entity_cancelled"
          :name="'process_form_decision_id.' + index"
          :data-vv-as="$t('forms.form_assessment.process_form_decision_id.label')"
          v-validate="'required'"
          :options="processFormDecisionList"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="assessment_comments"
        contextPath="forms.form_assessment.assessment_comments"
        :description="$t('forms.form_assessment.assessment_comments.description', { assessed_form_title: getSectionTitle(item) })"
        :required="isAssessmentCommentsFieldRequired(item)"
      >
        <input-wrap
          v-model="item.comments"
          :disabled="form.is_entity_cancelled"
          v-validate="{ required: isAssessmentCommentsFieldRequired(item) }"
          :data-vv-as="$t('forms.form_assessment.assessment_comments.label')"
          :name="'assessment_comments.' + index"
          maxlength="2500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import Form from './form.vue';

  import ListsAPI from '@api/lists';

  export default {
    name: 'assessment',

    extends: Form,

    watch: {
      displayForm() {
        // watch for when the form is displayed, and then wait for it to be rendered
        // so that we can count the number of tab panes afterwards
        // emit straight to the form entity component (e.g: gate-one-approval)
        this.$parent.$emit('mounted');
      }
    },

    data() {
      return {
        assessment_date_options: {
          disabledDate(time) {
            return time.getTime() < new Date('2000-01-01') || time.getTime() > new Date();
          }
        },
        snakeCase: _.snakeCase
      }
    },

    computed: {
      processFormDecisionList() {
        return this.lists['process-form-decision'];
      }
    },

    methods: {
      async loadData() {
        const requests = [];
        requests.push(
          ListsAPI.getLists([
            'process-form-decision'
          ])
        );
        // Exception handled by interceptor
        const [
          { data: lists }
        ] = await Promise.all(requests);

        this.lists = lists;
      },

      getSectionTitle(item) {
        return this.$t(`forms.${item.assessed_process_form.replace(/-/g, '_')}.form_title`);
      },

      isAssessmentCommentsFieldRequired(item) {
        const processFormDecision = this.processFormDecisionList.find(processFormDecision => processFormDecision.id === item.process_form_decision_id);
        return processFormDecision ? processFormDecision.name_key === 'rejected' : false;
      },

      onEntityCancelledChange(isChecked) {
        // if checked, reset the form to null values
        if (isChecked) {
          this.form.entity_cancellation_rationale = null;
          this.form.assessments.forEach(assessment => {
            assessment.process_form_decision_id = null;
            assessment.comments = null;
          });
        } else {
          this.form.entity_cancellation_rationale = null;
        }
      }
    },

    mounted() {
      this.$on('update:value', index => {
        // emit straight to the form entity component (e.g: gate-one-approval)
        this.$parent.$emit('update:value', index);
      });
    }
  };
</script>

<style lang="scss">
  .entity-cancellation {
    .el-radio {
      display: block;
      margin: 0 0 14px 0;
    }
  }
  .el-alert__content {
    line-height: 1.3em;
  }
</style>
