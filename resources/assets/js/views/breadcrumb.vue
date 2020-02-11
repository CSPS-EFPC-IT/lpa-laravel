<template>
  <div class="breadcrumb">
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item
        v-for="crumb in getBreadcrumbs()"
        :to="$i18nRoute({ name: crumb.name })"
        :key="crumb.id"
      >{{ crumb.title }}</el-breadcrumb-item>
    </el-breadcrumb>
  </div>
</template>

<script>
  import _ from 'lodash';

  export default {
    name: 'breadcrumb',

    data() {
      return {
        breadcrumbs: this.getBreadcrumbs()
      }
    },

    watch: {
      $route: function (to) {
        // update the internal breadcrumbs length
        this.breadcrumbs = this.getBreadcrumbs();
      }
    },

    methods: {
      validateMeta() {
        let matched = this.$route.matched;
        if (!this.$route.matched.length || !matched[0].meta || !matched[0].meta.breadcrumbs) {
          return false;
        }

        if (matched.length > 1) {
          this.$log.error('[breadcrumb] Route childrens not supported yet');
          return false;
        }
        return true;
      },

      getBreadcrumbs() {
        // build the home crumb as a starting point
        const homeRoute = this.$router.options.routes.find(route => route.name === 'home');
        let crumbs = [{
          id: _.uniqueId(),
          name: homeRoute.name,
          title: homeRoute.meta.title.call(this)
        }];

        if (!this.validateMeta()) {
          return crumbs;
        }

        // gather meta data and process any locale strings before moving on
        let matched = this.$route.matched;
        let meta = matched[0].meta;
        let matchedCrumbs = meta.breadcrumbs();
        let matchedCrumbsArr = _.compact(matchedCrumbs.split('/'));

        let title;
        let outputTitle;
        let crumb;

        // build up the breadcrumbs data
        for (let i = 0; i < matchedCrumbsArr.length; i++) {
          crumb = this.$router.options.routes.find(route => route.name === matchedCrumbsArr[i]);
          if (!crumb) {
            this.$log.error(`[breadcrumb] Route not found for name: ${matchedCrumbsArr[i]}`);
          }
          outputTitle = crumb.meta.title.call(this);

          // try to translate the title, or just take it as value
          title = outputTitle || '';

          // even if the title is empty for now,
          // it will be processed by the store later on
          crumbs.push({ id: _.uniqueId(), name: crumb.name, title: title });
        }

        this.setWindowTitle(crumbs);

        return crumbs;
      },

      setWindowTitle(crumbs) {
        let windowCrumbs = Object.assign({}, crumbs);
        // remove home crumb for window title
        delete windowCrumbs[0];
        let windowTitle = _.map(windowCrumbs, 'title').join(' - ');

        window.document.title = this.$t('base.navigation.app_name') + ' - ' + windowTitle;
      }
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';
  @import '~@sass/base/helpers';

  .breadcrumb {
    background-color: $--color-white;
    box-shadow: $--box-shadow-base;
    position: relative;
    z-index: $--index-top;
    .el-breadcrumb {
      padding: 20px 30px;
      font-size: 18px;
      // this fixes a bug when we have multiple lines in the breadcrumb
      line-height: inherit;

      .el-breadcrumb__item:not(:last-child) .el-breadcrumb__inner {
        @extend .fake-link;
      }
    }
  }
</style>
