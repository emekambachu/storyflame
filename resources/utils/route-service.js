import axios from 'axios';

const routeService = {

    getUserFromLocalStorage(){
        let user = JSON.parse(localStorage.getItem('story-flame-admin'));
        return user ? user : null;
    },

    getTokenFromLocalStorage(){
        let user = JSON.parse(localStorage.getItem('story-flame-admin'));
        return user ? user.token : null;
    },

    // Authenticate each api request
    authenticateUser(url, next, logout){

        if(!this.getTokenFromLocalStorage()){
            window.location.href = logout;
        }

        axios.get(url, {
            headers: {
                "Authorization" : "Bearer " + this.getTokenFromLocalStorage(),
                'Accept' : 'application/json',
            },
            params: {
                token: this.getTokenFromLocalStorage()
            }

        }).then((response) => {
            if(response.data.success){
                next();
            }else{
                window.location.href = logout;
            }
        }).catch((error) => {
            if(error.response){
                window.location.href = logout;
            }
        });

    },

}

export default routeService;
