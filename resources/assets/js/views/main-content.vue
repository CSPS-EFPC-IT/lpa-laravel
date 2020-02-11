<template>
  <router-view :class="['content', $route.name]" @app:ready.once="onAppReady"/>
</template>

<script>
  export default {
    name: 'main-content',

    methods: {
      onAppReady() {
        this.$emit('app:ready');
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/abstracts/mixins/helpers';

  $el-main-padding: 30px;

  .content {
    margin: 0 auto;
    position: absolute;
    // allocate space under breadcrumb
    top: 20px;
    // remove padding-left and right
    width: calc(100% - (#{$el-main-padding} * 2));
    // allow transition between contents
    // to keep their position while transitioning
    padding: 0 30px;
    padding-bottom: 20px;

    h2 {
      display: flex;
      align-items: center;
      flex: 1;
      margin: 0;
      i {
        @include size($svg-icons-size-medium);
        margin-right: 10px;
      }
    }
  }
</style>
