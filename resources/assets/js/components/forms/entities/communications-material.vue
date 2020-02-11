<template>
  <el-tabs
    :type="type"
    :tabPosition="tabPosition"
    v-model="innerActiveIndex"
  >
    <el-tab-pane data-name="official_languages">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('official_languages') }">
          {{ $t('forms.communications_material.tabs.official_languages') }}
        </span>
      </template>
      <h3>{{ $t('forms.communications_material.tabs.official_languages') }}</h3>
      <el-form-item-wrap
        prop="linguistic_services_consulted"
        contextPath="forms.communications_material.linguistic_services_consulted"
        required
      >
        <yes-no
          v-model="form.linguistic_services_consulted"
          v-validate="'required'"
          :data-vv-as="$t('forms.communications_material.linguistic_services_consulted.label')"
          name="linguistic_services_consulted"
        />
      </el-form-item-wrap>
      <el-form-item-wrap
        prop="material_original_language"
        contextPath="forms.communications_material.material_original_language"
        required
      >
        <el-radio-group
          v-model="form.material_original_language"
          v-validate="'required'"
          :data-vv-as="$t('forms.communications_material.material_original_language.label')"
          :class="{ 'is-error': verrors.has('material_original_language') }"
          name="material_original_language"
        >
          <el-radio-button label="en">{{ $t('entities.form.en') }}</el-radio-button>
          <el-radio-button label="fr">{{ $t('entities.form.fr') }}</el-radio-button>
        </el-radio-group>
        <form-error name="material_original_language"/>
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="titles">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('titles') }">
          {{ $t('forms.communications_material.tabs.titles') }}
        </span>
      </template>
      <h3>{{ $t('forms.communications_material.tabs.titles') }}</h3>
      <el-form-item-wrap
        prop="title"
        contextPath="forms.communications_material.title"
        required
      >
        <input-localized
          :toTranslate="processEntityInfo.name || $t('entities.general.na')"
          :modelEn.sync="form.title_en"
          :modelFr.sync="form.title_fr"
          :validate="'required'"
          :dataVvAsEn="$t('forms.communications_material.title.label')"
          :dataVvAsFr="$t('forms.communications_material.title.label')"
          nameEn="title_en"
          nameFr="title_fr"
          maxlength="250"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="summary">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('summary') }">
          {{ $t('forms.communications_material.tabs.summary') }}
        </span>
      </template>
      <h3>{{ $t('forms.communications_material.tabs.summary') }}</h3>
      <el-form-item-wrap
        prop="summary"
        contextPath="forms.communications_material.summary"
        :required="!!operationalDetailsMetaInfo.summary_content"
      >
        <input-localized
          :toTranslate="operationalDetailsMetaInfo.summary_content || $t('entities.general.na')"
          :modelEn.sync="form.summary_en"
          :modelFr.sync="form.summary_fr"
          :validate="{ required: !!operationalDetailsMetaInfo.summary_content }"
          :disabled="! !!operationalDetailsMetaInfo.summary_content"
          :dataVvAsEn="$t('forms.communications_material.summary.label')"
          :dataVvAsFr="$t('forms.communications_material.summary.label')"
          nameEn="summary_en"
          nameFr="summary_fr"
          maxlength="280"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="descriptions">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('descriptions') }">
          {{ $t('forms.communications_material.tabs.descriptions') }}
        </span>
      </template>
      <h3>{{ $t('forms.communications_material.tabs.descriptions') }}</h3>
      <el-form-item-wrap
        prop="description"
        contextPath="forms.communications_material.description"
        required
      >
        <input-localized
          :toTranslate="designMetaInfo.description || $t('entities.general.na')"
          :modelEn.sync="form.description_en"
          :modelFr.sync="form.description_fr"
          :validate="'required'"
          :dataVvAsEn="$t('forms.communications_material.description.label')"
          :dataVvAsFr="$t('forms.communications_material.description.label')"
          nameEn="description_en"
          nameFr="description_fr"
          maxlength="1000"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="keywords">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('keywords') }">
          {{ $t('forms.communications_material.tabs.keywords') }}
        </span>
      </template>
      <h3>{{ $t('forms.communications_material.tabs.keywords') }}</h3>
      <el-form-item-wrap
        prop="keywords"
        contextPath="forms.communications_material.keywords"
        required
      >
        <input-localized
          :modelEn.sync="form.keywords_en"
          :modelFr.sync="form.keywords_fr"
          :validate="'required'"
          :dataVvAsEn="$t('forms.communications_material.keywords.label')"
          :dataVvAsFr="$t('forms.communications_material.keywords.label')"
          nameEn="keywords_en"
          nameFr="keywords_fr"
          maxlength="500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="note">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('note') }">
          {{ $t('forms.communications_material.tabs.note') }}
        </span>
      </template>
      <h3>{{ $t('forms.communications_material.tabs.note') }}</h3>
      <el-form-item-wrap
        prop="note"
        contextPath="forms.communications_material.note"
        :required="!!operationalDetailsMetaInfo.note_content"
      >
        <input-localized
          :toTranslate="operationalDetailsMetaInfo.note_content || $t('entities.general.na')"
          :modelEn.sync="form.note_en"
          :modelFr.sync="form.note_fr"
          :validate="{ required: !!operationalDetailsMetaInfo.note_content }"
          :disabled="! !!operationalDetailsMetaInfo.note_content"
          :dataVvAsEn="$t('forms.communications_material.note.label')"
          :dataVvAsFr="$t('forms.communications_material.note.label')"
          nameEn="note_en"
          nameFr="note_fr"
          maxlength="500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
    <el-tab-pane data-name="disclaimer">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('disclaimer') }">
          {{ $t('forms.communications_material.tabs.disclaimer') }}
        </span>
      </template>
      <h3>{{ $t('forms.communications_material.tabs.disclaimer') }}</h3>
      <el-form-item-wrap
        prop="disclaimer"
        contextPath="forms.communications_material.disclaimer"
        :required="!!operationalDetailsMetaInfo.disclaimer_content"
      >
        <input-localized
          :toTranslate="operationalDetailsMetaInfo.disclaimer_content || $t('entities.general.na')"
          :modelEn.sync="form.disclaimer_en"
          :modelFr.sync="form.disclaimer_fr"
          :validate="{ required: !!operationalDetailsMetaInfo.disclaimer_content }"
          :disabled="! !!operationalDetailsMetaInfo.disclaimer_content"
          :dataVvAsEn="$t('forms.communications_material.disclaimer.label')"
          :dataVvAsFr="$t('forms.communications_material.disclaimer.label')"
          nameEn="disclaimer_en"
          nameFr="disclaimer_fr"
          maxlength="500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>

    <el-tab-pane data-name="comments">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('comments') }">
          {{ $t('forms.communications_material.tabs.comments') }}
        </span>
      </template>
      <h3>{{ $t('forms.communications_material.tabs.comments') }}</h3>
      <el-form-item-wrap
        prop="comments"
        contextPath="forms.communications_material.comments"
      >
        <input-wrap
          v-model="form.comments"
          name="comments"
          v-validate="''"
          :data-vv-as="$t('forms.communications_material.comments.label')"
          maxlength="2500"
          type="textarea"
        />
      </el-form-item-wrap>
    </el-tab-pane>
  </el-tabs>
</template>

<script>
  import Form from '../form.vue';

  export default {
    name: 'communications-material',

    extends: Form,

    computed: {
      designMetaInfo() {
        return this.meta.design;
      },

      operationalDetailsMetaInfo() {
        return this.meta.operational_details;
      },

      processEntityInfo() {
        return this.meta.process_entity;
      }
    },

    watch: {
      'operationalDetailsMetaInfo.summary_content'() {
        this.form.summary_en = null;
        this.form.summary_fr = null;
      },
      'operationalDetailsMetaInfo.note_content'() {
        this.form.note_en = null;
        this.form.note_fr = null;
      },
      'designMetaInfo.description'() {
        this.form.description_en = null;
        this.form.description_fr = null;
      },
      'operationalDetailsMetaInfo.disclaimer_content'() {
        this.form.disclaimer_en = null;
        this.form.disclaimer_fr = null;
      }
    }
  };
</script>

<style lang="scss">

</style>
