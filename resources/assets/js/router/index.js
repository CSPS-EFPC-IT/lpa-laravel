import Vue from 'vue';
import Router from 'vue-router';
import { Trans } from '@/i18n/Translation';
import store from '@/store/';

import Home                      from '@/views/home.vue';
import ProjectList               from '@/views/project/project-list.vue';
import ProjectView               from '@/views/project/project.vue';
import ProjectEdit               from '@/views/project/project-edit.vue';
import ProjectCreate             from '@/views/project/project-create.vue';
import ProcessForm               from '@/views/process/process-form.vue';
import Process                   from '@/views/process/process.vue';
import LearningProductList       from '@/views/learning-product/learning-product-list.vue';
import LearningProductView       from '@/views/learning-product/learning-product.vue';
import LearningProductCreate     from '@/views/learning-product/learning-product-create.vue';
import LearningProductEdit       from '@/views/learning-product/learning-product-edit.vue';
import Administration            from '@/views/admin/administration.vue';
import UserList                  from '@/views/admin/user-list.vue';
import UserCreate                from '@/views/admin/user-create.vue';
import UserEdit                  from '@/views/admin/user-edit.vue';
import OrganizationalUnitList    from '@/views/admin/organizational-unit-list.vue';
import OrganizationalUnitEdit    from '@/views/admin/organizational-unit-edit.vue';
import ProcessNotificationList   from '@/views/admin/process-notification-list.vue';
import ProcessNotificationEdit   from '@/views/admin/process-notification-edit.vue';
import LearningProductCode       from '@/views/admin/learning-product-code.vue';
import LearningProductCodeList   from '@/views/admin/learning-product-code-list.vue';
import LearningProductCodeCreate from '@/views/admin/learning-product-code-create.vue';
import LearningProductCodeEdit   from '@/views/admin/learning-product-code-edit.vue';
import NotFound                  from '@/views/errors/404.vue';
import Forbidden                 from '@/views/errors/403.vue';

Vue.use(Router);

let forceProceed = false;

async function beforeProceed(to, from, next) {
  // Here's the flow of events:
  //  1. authenticate user
  //  2. if authenticated, url may be standardized or changed to a forbidden url, else just proceed with requested page
  //  3. if url was altered, forceProceed to the result url
  if (forceProceed) {
    next();
    // reset flag
    forceProceed = false;
    return;
  }

  let isAuth = await isAuthenticated(to, from, next);
  if (isAuth) {
    proceed(to, from, next);
  }
}

// This will check to see if the user is authenticated or not.
async function isAuthenticated(to, from, next) {
  try {
    await store.dispatch('users/load');
    return true;
  } catch (e) {
    Vue.$log.error(`[router][isAuthenticated] ${e}`);
    return false;
  }
}

let isPathDirty = false;
function proceed(to, from, next) {
  // record the language if changed
  setLanguage(to);

  let newPath = to.fullPath;

  // prefix the route with the language if needed
  newPath = prefixRoute(newPath);
  // remove non-necessary trailling slashes at end of path
  newPath = trimTraillingSlashes(newPath);

  // check if user has rights to view the requested page
  const pathForbidden = isPathForbidden(newPath);

  // user has no access rights for the requested page, redirect to forbidden page
  if (pathForbidden) {
    forceProceed = true;
    const route = Vue.prototype.$i18nRoute({ name: 'forbidden' });
    router.replace(route);
    return;
  }

  // url has been cleaned up, so redirect to new url
  if (isPathDirty) {
    forceProceed = true;
    next(newPath);
    return;
  }

  // url do not need any modifications and the user do have access to it
  next();
}

function setLanguage(to) {
  const lang = to.params.lang;
  if (lang && lang !== Trans.currentLanguage) {
    Trans.setI18nLanguageInServices(lang);
  }
}

function prefixRoute(newPath) {
  if (!newPath.match(/\/en|fr/)) {
    newPath = `/${Trans.currentLanguage}${newPath}`;
    isPathDirty = true;
  }
  return newPath;
}

function trimTraillingSlashes(newPath) {
  if (newPath.charAt(newPath.length - 1) === '/') {
    newPath = newPath.slice(0, -1);
    isPathDirty = true;
  }
  return newPath;
}

function isPathForbidden(newPath) {
  if (!!newPath.match(/\/admin/)) {
    return !store.getters['users/hasRole']('admin');
  }
  return false;
}

// @note: Vue-router needs a name property when using history mode
// so that it can differ route changes. e.g.: language change
// @note: the meta.breadcrumbs property need to follow this convention:
//            'routeName/someOtherRouteName/currentRouteName'
//        And for the title, it can only be either a value or a a translatable property
//            'base.navigation.admin_user_edit' or
//            `${some.path.to.name}`
const routes = [
  {
    path: '/:lang(en|fr)',
    name: 'home',
    component: Home,
    meta: {
      title() {
        return this.$t('base.navigation.home');
      }
    }
  },
  {
    path: '/:lang(en|fr)/projects',
    name: 'project-list',
    component: ProjectList,
    meta: {
      title() {
        return this.$t('base.navigation.projects');
      },
      breadcrumbs: () => 'project-list'
    }
  },
  {
    path: '/:lang(en|fr)/projects/create',
    name: 'project-create',
    component: ProjectCreate,
    meta: {
      title() {
        return this.$t('base.navigation.create');
      },
      breadcrumbs: () => 'project-list/project-create'
    }
  },
  {
    path: '/:lang(en|fr)/projects/:entityId(\\d+)',
    name: 'project',
    component: ProjectView,
    meta: {
      title() {
        return this.$store.state.breadcrumbs[0];
      },
      breadcrumbs: () => 'project-list/project'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/projects/:entityId(\\d+)/edit',
    name: 'project-edit',
    component: ProjectEdit,
    meta: {
      title() {
        return this.$t('base.navigation.edit');
      },
      breadcrumbs: () => 'project-list/project/project-edit'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/:entityName(projects)/:entityId(\\d+)/process/:processId(\\d+)',
    name: 'project-process',
    component: Process,
    meta: {
      title() {
        return this.$store.state.breadcrumbs[1];
      },
      breadcrumbs: () => 'project-list/project/project-process'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/:entityName(projects)/:entityId(\\d+)/process/:processId(\\d+)/form/:formId(\\d+)',
    name: 'project-process-form',
    component: ProcessForm,
    meta: {
      title() {
        return this.$store.state.breadcrumbs[2];
      },
      breadcrumbs: () => 'project-list/project/project-process/project-process-form'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/learning-products',
    name: 'learning-product-list',
    component: LearningProductList,
    meta: {
      title() {
        return this.$t('base.navigation.learning_products');
      },
      breadcrumbs: () => 'learning-product-list'
    }
  },
  {
    path: '/:lang(en|fr)/learning-products/:entityId(\\d+)',
    name: 'learning-product',
    component: LearningProductView,
    meta: {
      title() {
        return this.$store.state.breadcrumbs[0];
      },
      breadcrumbs: () => 'learning-product-list/learning-product'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/learning-products/create',
    name: 'learning-product-create',
    component: LearningProductCreate,
    meta: {
      title() {
        return this.$t('base.navigation.create');
      },
      breadcrumbs: () => 'learning-product-list/learning-product-create'
    }
  },
  {
    path: '/:lang(en|fr)/learning-products/:entityId(\\d+)/edit',
    name: 'learning-product-edit',
    component: LearningProductEdit,
    meta: {
      title() {
        return this.$t('base.navigation.edit');
      },
      breadcrumbs: () => 'learning-product-list/learning-product/learning-product-edit'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/:entityName(learning-products)/:entityId(\\d+)/process/:processId(\\d+)',
    name: 'learning-product-process',
    component: Process,
    meta: {
      title() {
        return this.$store.state.breadcrumbs[1];
      },
      breadcrumbs: () => 'learning-product-list/learning-product/learning-product-process'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/:entityName(learning-products)/:entityId(\\d+)/process/:processId(\\d+)/form/:formId(\\d+)',
    name: 'learning-product-process-form',
    component: ProcessForm,
    meta: {
      title() {
        return this.$store.state.breadcrumbs[2];
      },
      breadcrumbs: () => 'learning-product-list/learning-product/learning-product-process/learning-product-process-form'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/admin',
    name: 'administration',
    component: Administration,
    meta: {
      title() {
        return this.$t('base.navigation.administration');
      },
      breadcrumbs: () => 'administration'
    }
  },
  {
    path: '/:lang(en|fr)/admin/users',
    name: 'user-list',
    component: UserList,
    meta: {
      title() {
        return this.$t('base.navigation.admin_user_list');
      },
      breadcrumbs: () => 'administration/user-list'
    }
  },
  {
    path: '/:lang(en|fr)/admin/users/create',
    name: 'user-create',
    component: UserCreate,
    meta: {
      title() {
        return this.$t('base.navigation.create');
      },
      breadcrumbs: () => 'administration/user-list/user-create'
    }
  },
  {
    path: '/:lang(en|fr)/admin/users/:userId(\\d+)/edit',
    name: 'user-edit',
    component: UserEdit,
    meta: {
      title() {
        return this.$store.state.breadcrumbs[0];
      },
      breadcrumbs: () => 'administration/user-list/user-edit'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/admin/organizational-units',
    name: 'organizational-unit-list',
    component: OrganizationalUnitList,
    meta: {
      title() {
        return this.$t('base.navigation.admin_organizational_unit_list');
      },
      breadcrumbs: () => 'administration/organizational-unit-list'
    }
  },
  {
    path: '/:lang(en|fr)/admin/organizational-units/:entityId(\\d+)/edit',
    name: 'organizational-unit-edit',
    component: OrganizationalUnitEdit,
    meta: {
      title() {
        return this.$store.state.breadcrumbs[0];
      },
      breadcrumbs: () => 'administration/organizational-unit-list/organizational-unit-edit'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/admin/process-notifications',
    name: 'process-notification-list',
    component: ProcessNotificationList,
    meta: {
      title() {
        return this.$t('base.navigation.admin_process_notification_list');
      },
      breadcrumbs: () => 'administration/process-notification-list'
    }
  },
  {
    path: '/:lang(en|fr)/admin/process-notifications/:entityId(\\d+)/edit',
    name: 'process-notification-edit',
    component: ProcessNotificationEdit,
    meta: {
      title() {
        return this.$store.state.breadcrumbs[0];
      },
      breadcrumbs: () => 'administration/process-notification-list/process-notification-edit'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/admin/learning-product-codes',
    name: 'learning-product-code-list',
    component: LearningProductCodeList,
    meta: {
      title() {
        return this.$t('base.navigation.admin_learning_product_code_list');
      },
      breadcrumbs: () => 'administration/learning-product-code-list'
    }
  },
  {
    path: '/:lang(en|fr)/admin/learning-product-codes/:entityId(\\d+)',
    name: 'learning-product-code',
    component: LearningProductCode,
    meta: {
      title() {
        return this.$store.state.breadcrumbs[0];
      },
      breadcrumbs: () => 'administration/learning-product-code-list/learning-product-code'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/admin/learning-product-codes/create',
    name: 'learning-product-code-create',
    component: LearningProductCodeCreate,
    meta: {
      title() {
        return this.$t('base.navigation.create');
      },
      breadcrumbs: () => 'administration/learning-product-code-list/learning-product-code-create'
    }
  },
  {
    path: '/:lang(en|fr)/admin/learning-product-codes/:entityId(\\d+)/edit',
    name: 'learning-product-code-edit',
    component: LearningProductCodeEdit,
    meta: {
      title() {
        return this.$t('base.navigation.edit');
      },
      breadcrumbs: () => 'administration/learning-product-code-list/learning-product-code/learning-product-code-edit'
    },
    props: true
  },
  {
    path: '/:lang(en|fr)/logout'
  },
  // serves as a 404 handler
  // this allows us to manually redirect the user to this route
  // when for example we are trying to load something that doesn't exist
  {
    path: '*',
    name: 'not-found',
    component: NotFound,
    meta: {
      title() {
        return this.$t('errors.not_found');
      },
      breadcrumbs: () => 'not-found'
    }
  },
  // serves as 403 handler for cases where we want to keep the url
  {
    path: '/:lang(en|fr)/forbidden',
    name: 'forbidden',
    component: Forbidden,
    meta: {
      title() {
        return this.$t('errors.forbidden');
      },
      breadcrumbs: () => 'forbidden'
    }
  }
];

window.router = new Router({
  routes,
  mode: 'history',
  scrollBehavior (to, from, savedPosition) {
    return { x: 0, y: 0 }
  }
});

// Router Guards
router.beforeEach(beforeProceed);

export default router;
