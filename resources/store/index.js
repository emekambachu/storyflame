import { createStore } from 'vuex';

import state from './state';
import * as actions from './actions';
import * as mutations from './mutations';
import * as getters from './getters';

// Import modules using the import syntax
// import authModule from './modules/auth';
// import talentsModule from './modules/talent';
// import talentCategoriesModule from './modules/talent-category';

const store = createStore({
    state,
    actions,
    mutations,
    getters,

    modules: {
        // auth: authModule,
        // talents: talentsModule,
        // talentCategories: talentCategoriesModule,
    }
});

export default store;
