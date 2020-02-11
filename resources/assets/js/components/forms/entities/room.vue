<template>
  <div class="room">
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.quantity`"
      contextPath="forms.operational_details.rooms.quantity"
      required
    >
      <input-wrap
        v-model="form.quantity"
        :name="`${fieldNamePrefix}.quantity`"
        v-validate="{ required: true, numeric: true }"
        :data-vv-as="$t('forms.operational_details.rooms.quantity.label')"
        type="number"
        :min="1"
        :max="10"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.room_usage_id`"
      contextPath="forms.operational_details.rooms.room_usage"
      required
    >
      <el-select-wrap
        v-model="form.room_usage_id"
        v-validate="'required'"
        :data-vv-as="$t('forms.operational_details.rooms.room_usage.label')"
        :name="`${fieldNamePrefix}.room_usage_id`"
        :options="data.roomUsageList"
        sorted
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.room_type_id`"
      contextPath="forms.operational_details.rooms.room_type"
      required
    >
      <el-select-wrap
        v-model="form.room_type_id"
        v-validate="'required'"
        :data-vv-as="$t('forms.operational_details.rooms.room_type.label')"
        :name="`${fieldNamePrefix}.room_type_id`"
        :options="data.roomTypeList"
        sorted
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.room_layout_id`"
      :classes="['has-other']"
      contextPath="forms.operational_details.rooms.room_layout"
      required
    >
      <el-select-other-wrap
        :modelSelect.sync="form.room_layout_id"
        :nameSelect="`${fieldNamePrefix}.room_layout_id`"
        :dataVvAsSelect="$t('forms.operational_details.rooms.room_layout.label')"
        :validateSelect="{ required: !this.isRoomLayoutOther }"
        :options="data.roomLayoutList"
        sorted

        localizable
        :modelOtherEn.sync="form.room_layout_other_en"
        :modelOtherFr.sync="form.room_layout_other_fr"
        :nameOtherEn="`${fieldNamePrefix}.room_layout_other_en`"
        :nameOtherFr="`${fieldNamePrefix}.room_layout_other_fr`"
        :dataVvAsOtherEn="$t('forms.operational_details.rooms.room_layout_other.label')"
        :dataVvAsOtherFr="$t('forms.operational_details.rooms.room_layout_other.label')"
        :validateOther="{ required: this.isRoomLayoutOther }"
        :isChecked.sync="isRoomLayoutOther"
        maxlength="100"
      />
    </el-form-item-wrap>
    <el-form-item-wrap
      :prop="`${fieldNamePrefix}.special_requirement_description`"
      contextPath="forms.operational_details.rooms.special_requirement_description"
    >
      <input-localized
        :modelEn.sync="form.special_requirement_description_en"
        :modelFr.sync="form.special_requirement_description_fr"
        :validateEn="{ required: !!form.special_requirement_description_fr }"
        :validateFr="{ required: !!form.special_requirement_description_en }"
        :dataVvAsEn="$t('forms.operational_details.rooms.special_requirement_description.label')"
        :dataVvAsFr="$t('forms.operational_details.rooms.special_requirement_description.label')"
        :nameEn="`${fieldNamePrefix}.special_requirement_description_en`"
        :nameFr="`${fieldNamePrefix}.special_requirement_description_fr`"
        maxlength="1250"
        type="textarea"
      />
    </el-form-item-wrap>
  </div>
</template>

<script>
  import ElFormItemWrap from '../el-form-item-wrap';
  import ElSelectWrap from '../el-select-wrap';
  import ElSelectOtherWrap from '../el-select-other-wrap';
  import InputWrap from '../input-wrap';
  import InputLocalized from '../input-localized';

  export default {
    name: 'room',

    components: { ElFormItemWrap, ElSelectWrap, ElSelectOtherWrap, InputWrap, InputLocalized },

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
        return 'rooms.' + this.index;
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
          quantity: null,
          room_usage_id: null,
          room_type_id: null,
          room_layout_id: null,
          room_layout_other_en: null,
          room_layout_other_fr: null,
          special_requirement_description_en: null,
          special_requirement_description_fr: null
        },
        isRoomLayoutOther: false
      };
    },

    watch: {
      form: function () {
        // make the checkbox react when the form data changes
        this.isRoomLayoutOther = !!this.form.room_layout_other_en || !!this.form.room_layout_other_fr;
      }
    },

    mounted() {
      // make the checkbox react
      // based on the value of its corresponding field
      this.isRoomLayoutOther = !!this.form.room_layout_other_en || !!this.form.room_layout_other_fr;
    }
  };
</script>

<style lang="scss">

</style>
