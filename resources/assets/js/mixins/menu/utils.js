export default {
  methods: {
    // This method will loop through the menu items and check whether or not
    // that the current route is a child of one of its items.
    setActiveIndex(to, menu) {
      // because of the $refs that cannot be reactive, we need to compare its items omitting the language
      const items = menu.items;

      // remove the language from the route's fullPath as well
      const route = Object.assign({}, to);

      // check if the current page is found in the items paths
      if (items[route.name]) {
        menu.activeIndex = items[route.name].index;
      } else {
        // if not, just pop last index of path and try again
        // this allows us to be on '/projects/1'
        // so that the sidebar still have projects item selected
        const pathArray = route.fullPath.split('/');
        pathArray.pop();
        const parentRoute = this.$router.match(pathArray.join('/'));
        // fail safe in case we get a path that the sidebar doesn't recognize
        if (parentRoute && parentRoute.fullPath === '' || parentRoute.fullPath === '/') {
          return;
        }
        // recursive call with new route, until we hit the base case
        // in which we will return
        this.setActiveIndex(parentRoute, menu);
      }
    }
  }
};
