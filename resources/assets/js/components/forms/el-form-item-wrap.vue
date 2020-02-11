 <template>
  <el-form-item
    :for="labelFor"
    :class="[classes, { 'is-required': required }]"
  >
    <template v-slot:label>
      <label-wrap v-bind="$props" />
    </template>
    <slot></slot>
  </el-form-item>
</template>

<script>
  import LabelWrap from './label-wrap';

  export default {
    name: 'el-form-item-wrap',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      label: {
        type: String
      },
      contextPath: {
        type: String
      },
      description: {
        type: String
      },
      help: {
        type: String
      },
      instruction: {
        type: String
      },
      prop: {
        type: String
      },
      classes: {
        type: Array
      },
      required: {
        type: Boolean,
        default: false
      },
      for: {
        type: String
      }
    },

    components: { LabelWrap },

    computed: {
      labelFor() {
        return this.for || this.prop
      }
    }
  }
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .el-form-item {
    &, & .form-items .el-form-item {
      // override ElementUI
      // this is to ensure that the char-count height is considerated
      margin-bottom: 28px;
    }
    &__label {
      position: relative;
      // Fixes a too short width in IE11
      width: 100%;
      color: $--color-primary;
      &:before {
        display: inline-block;
        // make sure that the label is always aligned with its control
        // to do that, we move to the left any required *
        // by doing that, this shifts left the entire label
        // when there is a required * next to it.
        margin-left: -10px;
      }
      @at-root &, .el-form-item label {
        color: $--color-primary;
        font-weight: 500;
        line-height: 10px;
      }
    }
    &__content {
      line-height: inherit;
    }
    // ElementUI override so that we can have multiple errors shown as a list
    &__error {
      position: relative;
      top: 0;
      display: flex;
    }
  }
</style>
