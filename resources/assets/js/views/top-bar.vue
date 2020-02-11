<template>
  <div>
    <el-row class="top-bar" type="flex">
      <el-col :span="8" class="app-title-col">
        <div>
          <h1>
            <router-link
              :to="$i18nRoute({ name: 'home' })"
              :class="{ 'disabled': isMainLoading }"
            >
              {{ $t('base.navigation.app_name') }}
            </router-link>
          </h1>
        </div>
      </el-col>
      <el-col :span="16" class="nav-col">
        <nav>
          <el-menu
            ref="topMenu"
            :default-active="$route.fullPath"
            background-color="#201e2c"
            text-color="#fff"
            active-text-color="#fff"
            class="top-menu"
            mode="horizontal"
            router
          >
            <el-submenu
              index="user-menu"
              popper-class="sub-menu"
              :class="{ 'disabled': isMainLoading }"
            >
              <template v-slot:title>{{ user.name }}</template>
              <el-menu-item index="" @click="onLogout()">
                {{ $t('base.navigation.logout') }}
              </el-menu-item>
            </el-submenu>
            <el-submenu
              index="help-menu"
              popper-class="sub-menu"
              :class="{ 'disabled': isMainLoading }"
            >
              <template v-slot:title>{{ $t('base.navigation.help') }}</template>
              <el-menu-item index="">
                <a :href="$t('base.navigation.help_support_centre_url')" target="_blank">
                  {{ $t('base.navigation.help_support_centre') }}
                </a>
              </el-menu-item>
              <el-menu-item index="">
                <a :href="$t('base.navigation.help_getting_started_url')" target="_blank">
                  {{ $t('base.navigation.help_getting_started') }}
                </a>
              </el-menu-item>
              <el-menu-item index="">
                <a :href="$t('base.navigation.help_projects_url')" target="_blank">
                  {{ $t('base.navigation.help_projects') }}
                </a>
              </el-menu-item>
              <el-menu-item index="">
                <a :href="$t('base.navigation.help_learning_products_url')" target="_blank">
                  {{ $t('base.navigation.help_learning_products') }}
                </a>
              </el-menu-item>
            </el-submenu>
            <el-menu-item
              index=""
              @click="showContactUsModal"
              :class="{ 'disabled': isMainLoading }"
            >
              {{ $t('base.navigation.contact_us') }}
            </el-menu-item>
            <el-menu-item
              index=""
              @click="setLanguage"
              :class="{ 'disabled': isMainLoading }"
            >
              {{ $t('base.navigation.language_toggle') }}
            </el-menu-item>
          </el-menu>
        </nav>
      </el-col>
    </el-row>
    <el-dialog
      custom-class="contact-dialog"
      :title="$t('base.navigation.contact_us')"
      :visible.sync="isContactUsModalVisible"
      :close-on-click-modal="false"
      @close="onContactUsModalClose"
    >
      <el-form
        @submit.native.prevent
        :disabled="isSubmitting"
        label-position="top"
      >
        <el-form-item-wrap
          prop="subject_id"
          :label="$t('entities.form.subject')"
        >
          <el-select-wrap
            v-model="contactForm.subject_id"
            name="subject_id"
            v-validate="'required'"
            :data-vv-as="$t('entities.form.subject')"
            :options="subjectList"
          />
        </el-form-item-wrap>
        <el-form-item-wrap :label="$t('entities.form.body')">
          <input-wrap
            v-model="contactForm.body"
            name="body"
            v-validate="'required'"
            :data-vv-as="$t('entities.form.body')"
            maxlength="1250"
            type="textarea"
          />
        </el-form-item-wrap>
      </el-form>
      <span slot="footer">
        <el-button size="small" @click="isContactUsModalVisible = false" :loading="isSubmitting">{{ $t('base.actions.cancel') }}</el-button>
        <el-button size="small" @click="onSubmit" :loading="isSubmitting" :disabled="!contactForm.subject_id || !contactForm.body" type="primary">{{ $t('base.actions.submit') }}</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import { mapState, mapMutations } from 'vuex';
  import { Trans } from '@/i18n/Translation';

  import UsersAPI from '@/api/users';
  import ListsAPI from '@api/lists';

  import Config from '@/config.js';
  import MenuUtils from '@mixins/menu/utils.js';
  import FormUtils from '@mixins/form/utils';

  import HttpStatusCodes from '@axios/http-status-codes';

  import ElFormItemWrap from '@components/forms/el-form-item-wrap';
  import InputWrap from '@components/forms/input-wrap';
  import ElSelectWrap from '@components/forms/el-select-wrap';

  export default {
    name: 'top-bar',

    mixins: [ MenuUtils, FormUtils ],

    components: { ElFormItemWrap, InputWrap, ElSelectWrap },

    // Gives us the ability to inject validation in child components
    // https://baianat.github.io/vee-validate/advanced/#disabling-automatic-injection
    inject: ['$validator'],

    data() {
      return {
        isTryingToLogOut: false,
        isContactUsModalVisible: false,
        isDataLoaded: false,
        subjectList: [],
        contactForm: {
          subject_id: null,
          body: ''
        }
      };
    },

    computed: {
      ...mapState([
        'isMainLoading',
        'shouldConfirmBeforeLeaving',
        'waitForLogout',
        'filteredDataTableList'
      ]),
      ...mapState('users', {
        user: 'current'
      }),

      language() {
        return Trans.currentLanguage;
      }
    },

    watch: {
      '$i18n.locale': async function (to, from) {
        // force a reload of the data
        this.isDataLoaded = false;
      },
      $route(to) {
        // since this is a 3rd party library,
        // we do not know when it will update itself
        // so just wait until the DOM is updated
        this.$nextTick(() => {
          this.setActiveIndex(to, this.$refs.topMenu);
        });
      },
      waitForLogout(val) {
        if (!val) {
          this.doLogout();
        }
      }
    },

    methods: {
      ...mapMutations([
        'showAppLoading',
        'setWaitForLogout'
      ]),

      async loadData() {
        const requests = [];
        requests.push(
          ListsAPI.getLists([
            'contact-form-subject'
          ])
        );
        // Exception handled by interceptor
        const [
          { data: lists }
        ] = await Promise.all(requests);

        this.subjectList = lists['contact-form-subject'];
        this.isDataLoaded = true;
      },

      onLogout() {
        this.setWaitForLogout(true);
      },

      async doLogout() {
        this.showAppLoading();
        // try-catch here since the logout uses another instance of axios
        // which doesn't have the interceptors
        try {
          await UsersAPI.logout();
          window.location = `/${this.language}/login`;
        } catch (e) {
          if (e.response) {
            // redirect to login page even if we have an error,
            // chances are that the user cleared its cache before the action
            window.location = `/${this.language}/login`;
          } else {
            throw e;
          }
        }
      },

      getSwitchedLang(lang) {
        return lang === 'en' ? 'fr' : 'en';
      },

      setLanguage() {
        if (this.filteredDataTableList.length !== 0) {
          this.$confirm(
            this.$t('components.notice.message.language_toggle'),
            this.$t('components.notice.type.warning'),
            {
              type: 'warning',
              confirmButtonText: this.$t('base.actions.continue'),
              confirmButtonClass: 'el-button--warning',
              cancelButtonText: this.$t('base.actions.cancel'),
              dangerouslyUseHTMLString: true
            }
          )
          .then(() => {
            this.doSetLanguage();
          })
          .catch(() => false);
        } else {
          this.doSetLanguage();
        }
      },

      async doSetLanguage() {
        // @todo: we probably should hide the app entirely
        // to avoid seeing bouncing texts
        this.$helpers.debounceAction(() => {
          let oldLang = this.language;
          let newLang = this.getSwitchedLang(oldLang);
          let route = Object.assign({}, this.$route);

          // apply lang to route
          // so that we can 'refresh' the current route
          // with the new language
          route.params.lang = newLang;

          /*
           * Also we need to set the fullPath to be able to forward to routes that have wildcards
           * See: https://github.com/vuejs/vue-router/issues/1994
           * And make sure that the params are reflecting the new language
           * We also need to decode the URI before assigning it
           * to avoid double encoding. Ex: "%20" -> "%2520".
           */
          let newPath = decodeURIComponent(route.path).replace(new RegExp(`^\/${oldLang}\/`), `/${newLang}/`);
          route.params.fullPath = newPath;
          if (['not-found'].some(s => route.name.includes(s))) {
            route.params['0'] = newPath;
          }
          this.$router.push(route);
        });
      },

      async showContactUsModal() {
        if (!this.isDataLoaded) {
          await this.loadData();
        }
        this.isContactUsModalVisible = true;
      },

      onContactUsModalClose() {
        // reset the contact us form
        this.contactForm.subject_id = null;
        this.contactForm.body = '';
        // reset the validation
        this.$nextTick(() => {
          this.resetErrors();
        });
      },

      onSubmit() {
        this.submit(this.handleSubmit);
      },

      async handleSubmit() {
        try {
          // be sure to pass the referer here so that we get the current location
          await axios.post('contact-form', {
            ...this.contactForm,
            referrer: window.location.href
          });
          this.notifySuccess({
            message: this.$t('components.notice.message.form_submitted')
          });
          this.isContactUsModalVisible = false;
        } catch (e) {
          this.isContactUsModalVisible = false;
          throw e;
        }
      }
    },

    mounted() {
      this.setActiveIndex(this.$route, this.$refs.topMenu);
    }
  };
</script>

<style lang="scss">
  @import '~@sass/abstracts/vars';

  $top-bar-height: 50px;
  $top-bar-fill: #201e2c;

  .top-bar {
    user-select: none;
    padding: 0 20px;
    background-color: $top-bar-fill;
    position: relative;
    z-index: $--index-top;
    // make space for the side-bar-toggle button
    padding-left: 64px + 15px;
    .el-menu-item,
    .el-submenu .el-submenu__title {
      height: $top-bar-height;
      line-height: $top-bar-height;
    }
    .el-col {
      display: flex;
      align-items: center;
      flex: 1;
      &.nav-col {
        justify-content: flex-end;
      }
    }

    h1 {
      font-size: 1.3rem;
      margin: 0;
      display: flex;
      a {
        color: $--color-white;
        text-decoration: none;
        display: inline-block;
        vertical-align: middle;
        font-weight: 600;
      }
    }

    .top-menu {
      border: 0;
      a {
        text-decoration: none;
      }

      li:hover,
      li:hover .el-submenu__title,
      li.is-active .el-submenu__title {
        background-color: #322f43 !important;
      }
      .el-submenu__title {
        border: none !important;
      }

      .el-icon-setting {
        color: $--color-white;
        vertical-align: sub;
        &.active {
          color: $--color-danger;
        }
      }
    }
  }

  .sub-menu .el-menu,
  .sub-menu .el-menu-item,
  .sub-menu .el-submenu .el-submenu__title {
    color: $--color-black !important;
    background-color: $--color-white !important;
    @at-root .sub-menu .el-menu {
      border-radius: 0;
      border: 1px solid #cdcdcd;
      padding: 0;
      margin-top: 2px;
      &-item:hover,
      &-item.is-active {
        color: $--color-white !important;
        background-color: #322f43 !important;
      }
    }
    @at-root .sub-menu .el-menu-item a {
      display: block;
      width: 100%;
      padding: 0 10px;
      margin: 0 -10px;
      color: $--color-black !important;
      background-color: transparent !important;
      text-decoration: none;
      font-weight: normal;
      &:hover {
        color: $--color-white !important;
      }
    }
  }
  .el-dialog__wrapper .contact-dialog {
    width: 800px;
  }
</style>
