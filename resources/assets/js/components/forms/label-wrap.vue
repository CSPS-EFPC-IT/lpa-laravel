 <template>
  <span class="label-wrap">
    <span class="wrapper">
      <span class="label-inner-wrap">
        <component :is="tag">
          {{ innerLabel }}
          <el-popover-wrap
            v-if="innerDescription || innerHelp"
            :description="innerDescription"
            :help="innerHelp">
          </el-popover-wrap>
        </component>
      </span>
      <span v-if="innerInstruction" class="instruction" v-html="innerInstruction"></span>
      <slot></slot>
    </span>
  </span>
</template>

<script>
  import ElPopoverWrap from '../el-popover-wrap';

  export default {
    name: 'label-wrap',

    inheritAttrs: false,

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    props: {
      tag: {
        type: String,
        default: 'span'
      },
      contextPath: {
        type: String
      },
      label: {
        type: String
      },
      labelIsPlural: {
        type: Boolean,
        default: false
      },
      instruction: {
        type: String
      },
      description: {
        type: String
      },
      help: {
        type: String
      }
    },

    components: { ElPopoverWrap },

    computed: {
      labelPluralCount() {
        return this.labelIsPlural ? 2 : 1;
      },

      innerLabel() {
        const string = this.$te(`${this.contextPath}.label`);
        return this.label ?
               this.label : string ?
               this.$tc(`${this.contextPath}.label`, this.labelPluralCount) : '';
      },

      innerDescription() {
        const string = this.$te(`${this.contextPath}.description`);
        return this.description ?
               this.description : string ?
               this.$tc(`${this.contextPath}.description`) : '';
      },

      innerHelp() {
        const string = this.$te(`${this.contextPath}.help`);
        return this.help ?
               this.help : string ?
               this.$tc(`${this.contextPath}.help`) : '';
      },

      innerInstruction() {
        const string = this.$te(`${this.contextPath}.instruction`);
        return this.instruction ?
               this.instruction : string ?
               this.$tc(`${this.contextPath}.instruction`) : '';
      }
    }
  }
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  .label-wrap {
    // this styles cases where we might want multiple info
    // inside the same label
    .wrapper,
    .label-inner-wrap {
      width: 100%;
      box-sizing: border-box;
    }
    .instruction {
      display: block;
      font-style: italic;
      line-height: normal;
      color: $--color-text-regular;
    }
  }
</style>
