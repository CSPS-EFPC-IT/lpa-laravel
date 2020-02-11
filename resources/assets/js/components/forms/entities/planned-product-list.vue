<template>
  <el-tabs
    :type="type"
    :tabPosition="tabPosition"
    v-model="innerActiveIndex"
    v-if="displayForm"
  >
    <el-tab-pane data-name="planned_product">
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('planned_product') }">
          {{ $tc('forms.planned_product_list.tabs.planned_product', 2) }}
        </span>
      </template>
      <h3>{{ $tc('forms.planned_product_list.tabs.planned_product', 2) }}</h3>
      <form-section-group
        v-model="form.planned_products"
        entityForm="planned-product-list"
        entitySection="planned-product"
        :data="{
          typeList
        }"
        :min="1"
      />
    </el-tab-pane>
    <el-tab-pane>
      <template v-slot:label>
        <span :class="{'is-error': errorTabs.includes('comments') }">
          {{ $t('forms.planned_product_list.tabs.comments') }}
        </span>
      </template>
      <h3>{{ $t('forms.planned_product_list.tabs.comments') }}</h3>
      <el-form-item-wrap
        prop="comments"
        contextPath="forms.planned_product_list.comments"
      >
        <input-wrap
          v-model="form.comments"
          name="comments"
          v-validate="''"
          :data-vv-as="$t('forms.planned_product_list.comments.label')"
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
    name: 'planned-product-list',

    extends: Form,

    data() {
      return {
        lists: []
      };
    },

    computed: {
      typeList() {
        return this.lists['learning-product-type'];
      }
    },

    methods: {
      async loadData() {
        const requests = [];
        requests.push(
          ListsAPI.getLists([
            'learning-product-type'
          ])
        );
        // Exception handled by interceptor
        const [
          { data: lists }
        ] = await Promise.all(requests);

        this.lists = lists;
      },

      onTypeChange(value) {
        let type = value[0];
        let subType = value[1];

        [this.form.type, this.form.subType] = [type, subType];
      }
    }
  };
</script>

<style lang="scss">

</style>
