export const SET_AUTH_ADMIN = (state, {data}) => {
    state.admin = data.user;
}

export const SET_CATEGORIES = (state, {data}) => {
    state.categories = data.categories;
}
