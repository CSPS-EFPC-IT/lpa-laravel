<template>
  <div class="form-section-group">
    <label-wrap
      tag="h4"
      :contextPath="`forms.${entityFormKey}.form_section_groups.${entitySectionKey}`"
      labelIsPlural
    >
      <div class="header-controls">
        <button :disabled="!groups.length" @click.prevent="expandAll()">
          {{ $t('base.actions.expand_all') }}
        </button>
        <button :disabled="!groups.length" @click.prevent="collapseAll()">
          {{ $t('base.actions.collapse_all') }}
        </button>
      </div>
    </label-wrap>
    <el-collapse :value="activePanels" @change="onActivePanelsChange">
      <transition-group name="slide-up">
        <el-collapse-item v-for="(item, index) in groups" :name="index + 1" :key="`form-section-group-${index}`">
          <template v-slot:title>
            {{ $tc(`forms.${entityFormKey}.form_section_groups.${entitySectionKey}.label`) }} {{ index + 1 }}
            <!--
              only show the delete button:
              if the min value is not specified (0) always show it
              else show it only when there are more groups than the min value
            -->
            <el-control-wrap
              v-if="shouldShowRemoveButton"
              componentName="el-button"
              class="remove-group"
              :tooltip="$t('components.tooltip.delete_record')"
              type="danger"
              icon="el-icon-lpa-delete"
              size="mini"
              @click.native.stop="onRemoveGroup(index)"
              plain
            />
          </template>
          <component
            ref="component"
            :is="entitySection"
            :data="data"
            class="form-item-group"
            :index="index"
            :value="item"
          />
        </el-collapse-item>
      </transition-group>
    </el-collapse>
    <el-button
      class="add-group"
      type="primary"
      icon="el-icon-plus"
      @click="addGroup()"
    >
    </el-button>
  </div>
</template>

<script>
  import { mapMutations } from 'vuex';

  import LabelWrap from '@components/forms/label-wrap';
  import ElControlWrap from '@components/el-control-wrap';

  // Form sections
  import Spending from './entities/spending';
  import Risk from './entities/risk';
  import PlannedProduct from './entities/planned-product';
  import ApplicablePolicy from './entities/applicable-policy';
  import AdditionalCost from './entities/additional-cost';
  import Instructor from './entities/instructor';
  import GuestSpeaker from './entities/guest-speaker';
  import Room from './entities/room';
  import Material from './entities/material';
  import Document from './entities/document';
  import Region from './entities/region';
  import ExpectedAnnualParticipants from './entities/expected-annual-participants';

  export default {
    name: 'form-section-group',

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    components: {
      LabelWrap,
      ElControlWrap,
      Spending,
      Risk,
      PlannedProduct,
      ApplicablePolicy,
      AdditionalCost,
      Instructor,
      GuestSpeaker,
      Room,
      Material,
      Document,
      Region,
      ExpectedAnnualParticipants
    },

    props: {
      entityForm: {
        type: String,
        required: true
      },
      entitySection: {
        type: String,
        required: true
      },
      data: {
        type: Object
      },
      min: {
        type: Number,
        default: 0
      },
      value: {
        type: Array
      }
    },

    data() {
      return {
        activePanels: []
      };
    },

    computed: {
      entityFormKey() {
        return _.snakeCase(this.entityForm);
      },

      entitySectionKey() {
        return _.snakeCase(this.entitySection);
      },

      shouldShowRemoveButton() {
        return this.min ? this.groups.length > this.min : true;
      },

      groups: {
        get() {
          if (!this.value) {
            this.$log.error(`Value wasn't initialized for property group. Make sure that a value is passed in as v-model.`);
            return null;
          }
          return this.value;
        },
        set(val) {
          this.$emit('update:value', val);
        }
      }
    },

    watch: {
      '$i18n.locale'(to, from) {
        this.prepareGroups();
      },
      // triggered when the form is claimed
      value() {
        this.prepareGroups();
      }
    },

    methods: {
      ...mapMutations('processForms', [
        'setHasFormSectionGroupsRemoved'
      ]),

      // triggered when manually expanding-collapsing a collapse-item
      onActivePanelsChange(activeNames) {
        this.activePanels = activeNames;
      },

      expandAll() {
        this.activePanels = _.range(1, this.groups.length + 1);
      },

      collapseAll() {
        this.activePanels = [];
      },

      onRemoveGroup(index) {
        this.confirmDelete({
          title: this.$t('components.notice.title.delete_record'),
          message: this.$t('components.notice.message.delete_record'),
          beforeClose: (action, instance, done) => {
            if (action === 'confirm') {
              instance.confirmButtonLoading = true;
              setTimeout(() => {
                this.removeGroup(index);
                done();
                instance.confirmButtonLoading = false;
              }, 300);
            } else {
              done();
            }
          }
        }).then(() => true).catch(() => false);
      },

      removeGroup(index) {
        this.groups.splice(index, 1);
        // if the panel to be removed is active, remove it also from our activePanels
        const activePanel = this.activePanels.indexOf(index + 1);
        if (activePanel !== -1) {
          this.activePanels.splice(activePanel, 1);
        }
        this.setHasFormSectionGroupsRemoved(true);
      },

      addGroup(isPreparing = false) {
        // push a new group now so that the component can render it
        // and we can access to its defaults
        this.groups.push({});

        this.$nextTick(() => {
          const groupsLength = this.groups.length - 1;
          const defaults = this.$refs.component[groupsLength].defaults;
          const fieldNamePrefix = this.$refs.component[groupsLength].fieldNamePrefix;
          if (_.isUndefined(defaults)) {
            this.$log.error(`Entity '${this.entitySection}' should have a 'defaults' attribute in its data.`);
            return;
          }
          for (const key in defaults) {
            this.$set(this.groups[groupsLength], key, defaults[key]);
            // put the newly added fields dirty when a group is added manually
            // so that vee-validate recognize that new fields were added
            this.$validator.flag(`${fieldNamePrefix}.${key}`, { dirty: !isPreparing });
          }
          this.updateActivePanels(this.groups.length);
        });
      },

      /**
       * Add the number of items if min prop is provided and that the group is empty
       * This function fires when the component:
       *   - is created
       *   - everytime the groups changes (on claim, language update, on add or remove group)
      */
      prepareGroups() {
        if (this.min !== 0 && !this.groups.length) {
          // add the ammount of groups provided
          for (let i = 0; i < this.min; i++) {
            this.addGroup(true);
          }
        }
      },

      updateActivePanels(index) {
        // make sure to not add the same index twice to the array
        if (_.isEmpty(this.activePanels) || !this.activePanels.includes(index)) {
          this.activePanels.push(index);
        }
      }
    },

    created() {
      // if data prop was provided, but is empty, throw error
      if (this.$props.data && _.isEmpty(this.$props.data)) {
        this.$log.error(`Data prop was provided but is empty`);
        return;
      }
      this.prepareGroups();
      if (this.groups.length && _.isEmpty(this.activePanels)) {
        // if we receive groups and there are no activePanels yet,
        // make sure to put the first tab active
        this.updateActivePanels(1);
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  $tab-height: 34px;

  .form-section-group {
    // Slide up
    .slide-up-leave-active {
      transition: all 0.3s ease;
    }
    .slide-up-leave {
      height: $tab-height;
      overflow: hidden;
    }
    .slide-up-leave-to {
      height: 0;
      opacity: 0;
    }

    > .label-wrap {
      border-bottom: 1px solid;
      .wrapper {
        display: grid;
        display: -ms-grid;
        -ms-grid-columns: 1fr auto;
        -ms-grid-rows: 1fr auto;
        grid-template-columns: 1fr auto;
        grid-template-rows: 0fr auto;
      }
      .label-inner-wrap {
        display: block;
        grid-column: 1/6;
        grid-row: 1;
        -ms-grid-column-span: 6;
        -ms-grid-column: 1;
        -ms-grid-row: 1;
      }
      h4 {
        // since there could be a popover next to it,
        // we need to make space for it to stay close to the h4
        display: inline;
      }
      .instruction {
        margin-bottom: 10px;
        grid-column: 1/5;
        grid-row: 2;
        -ms-grid-column-span: 5;
        -ms-grid-column: 1;
        -ms-grid-row: 2;
      }
      .header-controls {
        grid-column: 5/6;
        grid-row: 2;
        align-self: end;
        -ms-grid-column-span: 1;
        -ms-grid-column: 6;
        -ms-grid-row: 2;
        -ms-grid-row-align: end;
        button {
          text-decoration: underline;
          background-color: transparent;
          border: none;
          outline: none;
          &:not(:disabled) {
            color: $--link-color;
            cursor: pointer;
            &:hover {
              color: $--link-hover-color;
            }
          }
          &:disabled {
            cursor: not-allowed;
          }
        }
      }
    }

    // make sure that the el-collapse following a h4
    // goes on top of the h4's margin-bottom
    h4 + .el-collapse {
      margin-top: -1.1em;
      border-top: 0;
      border-bottom: 0;
      // and remove its border-top
      .el-collapse {
        border-top: none;
      }
    }

    .el-collapse-item__header {
      display: flex;
      padding-left: 10px;
      line-height: $tab-height;
      height: $tab-height;
      background-color: $--background-color-base;
      transition: $bg-color-transition-base;
      &:hover {
        background-color: mix($--color-primary, $--background-color-base, 5%);
      }
      i.el-collapse-item__arrow {
        line-height: $tab-height;
        // put the arrow on the very left of the flex
        order: -1;
        margin-right: 10px;
        margin-left: 0;
      }
      button.remove-group {
        height: 100%;
        border-radius: 0;
        // put the button on the right
        margin-left: auto;
      }
    }

    .form-item-group {
      margin-top: 20px;
      margin-left: 20px;
    }

    button.add-group {
      margin: 20px 0;
    }
  }
</style>
