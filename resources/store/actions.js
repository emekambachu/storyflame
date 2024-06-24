import axios from 'axios';

export async function getData({commit}, {
    url: url,
    commit_name: commit_name,
    has_auth: has_auth = false,
    token: token = null,
    user_id: user_id = null
}){

    let headers = {
        'Accept' : 'application/json',
    }

    let params = {};

    if (has_auth && token) {
        headers["Authorization"] = "Bearer " + token;
    }

    if (user_id) {
        params["user_id"] = user_id;
    }

    if (token) {
        params["token"] = token;
    }

    await axios.get(url, {
        headers: headers,
        params: params

    }).then((response) => {
        if(response.data.success === true){
            console.log("GET_DATA", response.data);
            console.log("COMMIT_NAME", commit_name);
            commit(commit_name, {data: response.data});
        }
        console.log(response.data);
    }).catch((error) => {
        console.log(error);
    });
}

export async function postData({commit}, {
    url,
    form_data,
    commit_name,
    has_auth,
    token,
    return_data = false
}){

    let headers = {
        'Accept' : 'application/json',
    }

    let params = {};

    if(has_auth === true){
        headers["Authorization"] = "Bearer " + token;
    }

    await axios.post(url, form_data, {
        headers: headers,
        params: params

    }).then((response) => {
        if(response.data.success === true){
            commit('SET_SUCCESS', {status: true, message: response.data.message})
            if(return_data === true){
                commit(commit_name, {data: response.data});
            }
        }else if(response.data.errors.length > 0){
            commit('SET_ERROR', {status: true, errors: response.data.errors})
        }
        console.log(response.data);
    }).catch((error) => {
        console.log(error);
    });
}
