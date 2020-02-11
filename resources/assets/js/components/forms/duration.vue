<template>
  <div class="duration">
    <div class="form-items">
      <el-form-item
        prop="hours"
        :class="{ 'is-error': verrors.collect('hours').length }"
      >
        <input-wrap
          v-model="innerHoursModel"
          name="hours"
          v-validate="innerHoursValidate"
          :data-vv-as="$t('entities.general.hours')"
          type="number"
          :min="0"
          :max="hoursMaxValue"
          hideErrors
        >
          <template v-slot:prepend>{{ $t('entities.general.hours') }}</template>
        </input-wrap>
      </el-form-item>
      <el-form-item
        prop="minutes"
        :class="{ 'is-error': verrors.collect('minutes').length }"
      >
        <input-wrap
          v-model="innerMinsModel"
          name="minutes"
          v-validate="innerMinsValidate"
          :data-vv-as="$t('entities.general.minutes')"
          type="number"
          :min="0"
          :max="innerHoursModel === hoursMaxValue?0:minsMaxValue"
          hideErrors
        >
          <template v-slot:prepend>{{ $t('entities.general.minutes') }}</template>
        </input-wrap>
      </el-form-item>
      <el-form-item
        prop="seconds"
        :class="{ 'is-error': verrors.collect('seconds').length }"
      >
        <input-wrap
          v-model="innerSecsModel"
          name="seconds"
          v-validate="innerSecsValidate"
          :data-vv-as="$t('entities.general.seconds')"
          type="number"
          :min="0"
          :max="innerHoursModel === hoursMaxValue?0:secsMaxValue"
          hideErrors
        >
          <template v-slot:prepend>{{ $t('entities.general.seconds') }}</template>
        </input-wrap>
      </el-form-item>
    </div>
    <form-error name="hours" />
    <form-error name="minutes" />
    <form-error name="seconds" />
  </div>
</template>

<script>
  import FormError from './error.vue';
  import InputWrap from './input-wrap.vue';

  export default {
    name: 'duration',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    components: { FormError, InputWrap },

    props: {
      isRequired: {
        type: Boolean,
        default: false
      },
      hoursMaxValue: {
        type: Number,
        default: 24
      },
      minsMaxValue: {
        type: Number,
        default: 59
      },
      secsMaxValue: {
        type: Number,
        default: 59
      },
      value: {
        type: String | Number
      }
    },

    data() {
      return {
        innerHoursModel: null,
        innerMinsModel: null,
        innerSecsModel: null
      };
    },

    computed: {
      innerHoursValidate() {
        return {
          min_value: 0,
          max_value: this.hoursMaxValue,
          required: this.isRequired,
          numeric: true
        };
      },
      innerMinsValidate() {
        return {
          min_value: 0,
          max_value: this.innerHoursModel === this.hoursMaxValue?0:this.minsMaxValue,
          required: this.isRequired,
          numeric: true
        };
      },
      innerSecsValidate() {
        return {
          min_value: 0,
          max_value: this.innerHoursModel === this.hoursMaxValue?0:this.secsMaxValue,
          required: this.isRequired,
          numeric: true
        };
      },
      totalDuration() {
        const hours = this.innerHoursModel;
        const mins = this.innerMinsModel;
        const secs = this.innerSecsModel;
        return (hours * 60 * 60) + (mins * 60) + secs;
      },
    },

    watch: {
      value: {
        immediate: true,
        handler() {
          if (!_.isNull(this.value)) {
            this.innerHoursModel = Math.floor(this.value / 3600);
            this.innerMinsModel = Math.floor((this.value % 3600) / 60);
            this.innerSecsModel = this.value % 60;
          }
        }
      },
      // this makes sure that when the hours are at max
      // that we reset the mins and secs to 0
      innerHoursModel() {
        if (this.innerHoursModel === this.hoursMaxValue) {
          this.innerMinsModel = 0;
          this.innerSecsModel = 0;
        }
      },
      totalDuration(val) {
        // affect the computed value so that our field is in sync
        this.$emit('input', val);
      }
    }
  };
</script>

<style lang="scss">
  .duration {
    .form-items {
      display: flex;
      .el-form-item {
        margin-bottom: 0;
      }
    }
    .input-wrap {
      margin-right: 20px;
      display: inline-block;
      & + .input-wrap {
        margin-top: 0;
      }
    }
  }
</style>
