let authService = {

    redirectIfUnauthenticated: function(error) {
        if(error.response.data.message === 'Unauthenticated.' || error.response.status === 401){
            window.location.href = '/learning/login';
        }
    },

}

export default authService;
