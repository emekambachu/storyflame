import router from "../router";

const RouteService = {
    // Authenticate each api request
    authenticateUser(authenticated, token, next, logout){
        if(authenticated === true){
            next();
        }else{
            window.location.href = logout;
        }
    },

    logoutUser(url, local_storage, user_data, logout){
        console.log("ROUTE LOCALSTORAGE", user_data.token);
        axios.get(url, {
            headers: {
                "Authorization" : "Bearer " + user_data.token,
                'Accept' : 'application/json',
            },
            params: {
                token: user_data.token
            }

        }).then((response) => {
            if(response.data.success){
                localStorage.removeItem(local_storage);
                window.location.href = logout;
            }else{
                window.location.href = logout;
            }
        });
    },

    // check if diagnostic tool has been completed
    // If not redirect to diagnostic tool
    completedDiagnosticTool(pre_diagnostic_completed){
        // Get token from local storage
        if(pre_diagnostic_completed === false){
            router.push({ path: '/diagnostic/user-details' });
            window.location.href = '/diagnostic/user-details';
        }

        // axios.get('/api/learning/diagnostic-tool/completed', {
        //     headers: {
        //         "Authorization" : "Bearer " + token,
        //         'Accept' : 'application/json',
        //     },
        // }).then((response) => {
        //     if(response.data.completed === false){
        //         // router.push({ path: '/diagnostic/user-details' });
        //         window.location.href = '/diagnostic/user-details';
        //     }
        //
        // }).catch((error) => {
        //     console.log(error)
        // });
    }

}

export default RouteService;
