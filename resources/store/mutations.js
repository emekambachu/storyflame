export const SET_AUTH_ADMIN = (state, {data}) => {
    state.admin = data.user;
}

export const SET_CATEGORIES = (state, {data}) => {
    state.categories = data.categories;
}

export const SET_MIN_DATA_POINTS = (state, {data}) => {
    state.dataPoints = data.data_points;
}
