<template>

</template>

<script>
  /**
   * Application flow (until rendering occurs):
   *
   * beforeRouteUpdate (vue-router)
   * beforeEnter (vue-router)
   * beforeRouteEnter (vue-router)
   * beforeResolve (vue-router)
   * ** Navigation confirmed **
   * afterEach (vue-router)
   * beforeCreate
   * beforeRouteEnter next() called (vue-router)
   * created
   * beforeMount
   * mounted
   */

  // @note: when extending in Vuejs, every objects passed in will be overriden
  // but not vuejs hooks (e.g.: created, mounted)
  // Note that vue-router hooks will still be fired for every components.
  // They won't be overridden

  import { Trans } from '@/i18n/Translation';
  import HttpStatusCodes from '@axios/http-status-codes';

  import { mapState, mapGetters, mapMutations } from 'vuex';

  export default {
    name: 'page',

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    $_veeValidate: {
      validator: 'new'
    },

    data() {
      return {
        isInitializing: true,
        displayPage: false
      };
    },

    computed: {
      ...mapState([
        'shouldConfirmBeforeLeaving',
        'isMainLoading',
        'waitForLogout'
      ]),

      ...mapGetters('users', [
        'hasRole',
        'isCurrentUser'
      ]),

      language() {
        return Trans.currentLanguage;
      }
    },

    watch: {
      '$i18n.locale': function (to, from) {
        this.onLanguageUpdate();
      },

      waitForLogout: function (val) {
        if (val) {
          this.beforeLogout();
        }
      }
    },

    methods: {
      ...mapMutations([
        'setShouldConfirmBeforeLeaving',
        'setWaitForLogout',
        'setBreadcrumbs'
      ]),

      async loadData() {},

      async onLanguageUpdate() {
        if (this.resetErrors) {
          // since on submit the backend returns already translated error messages,
          // we need to reset the validator messages so that on next submit
          // the messages are in the correct language
          this.resetErrors();
        }
        if (this.isUsingNewLoading) {
          try {
            await this.loadData();
          } catch (e) {
            if (!e.response) {
              throw e;
            }
            // handle non-authorized cases
            if (e.response.status == HttpStatusCodes.FORBIDDEN) {
              this.goToForbidden();
            }
          }
        } else if (this.loadData) {
          this.loadData.apply(this);
        }
      },

      // Utils
      scrollToTop() {
        document.querySelectorAll('.el-main')[0].scrollTop = 0;
        // IE11 scroll to top
        document.querySelectorAll('.content-wrap')[0].scrollTop = 0
      },

      goToPage({ name, params }) {
        const currentRoute = this.$router.currentRoute
        params = params || currentRoute.params;
        let route = {};

        this.scrollToTop();
        // if current params belong to a 404 or 403 route
        // then just redirect to home page
        if (['forbidden', 'not-found'].some(s => currentRoute.name.includes(s))) {
          route = this.$i18nRoute({ name });
        } else {
          route = this.$i18nRoute({ name, params });
        }
        this.$router.push(route);
      },

      goToParentPage() {
        const crumbs = this.$router.currentRoute.meta.breadcrumbs().split('/');
        const params = this.$router.currentRoute.params;
        // remove last crumb (which is current one)
        crumbs.pop();
        // and then grab the second last one (which is now the last one)
        const name = crumbs.pop();

        // if there is no parent to go to
        if (_.isUndefined(name)) {
          this.$log.error('Cannot go to parent page as there are no parent.')
        }

        // if route cannot be found
        if (!this.$router.match({name, params}).matched.length) {
          this.$log.error(`Route name: ${name}, cannot be found.`);
        }

        this.goToPage({ name });
      },

      goToNotFound(newPath) {
        this.goToPage(
          { name: 'not-found',
          params: { '0': `${newPath}/not-found` }
        });
      },

      goToForbidden() {
        this.goToPage({ name: 'forbidden'});
      },

      beforeLogout() {
        this.setWaitForLogout(false);
      }
    },

    beforeRouteEnter(to, from, next) {
      next(async vm => {
        if (vm.isUsingNewLoading) {
          try {
            await vm.loadData();
            vm.isInitializing = false;
            vm.displayPage = true;
          } catch (e) {
            if (!e.response) {
              throw e;
            }
            // handle non-authorized cases
            if (e.response.status == HttpStatusCodes.FORBIDDEN) {
              vm.goToForbidden();
            }
          }
        }
      });
    },

    mounted() {
      this.$emit('app:ready');
    }
  };
</script>

<style lang="scss">

</style>
