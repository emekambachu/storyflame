let baseService = {

    getUserFromLocalStorage(){
        let user = JSON.parse(localStorage.getItem('story-flame-admin'));
        return user ? user : null;
    },

    getTokenFromLocalStorage(){
        let user = JSON.parse(localStorage.getItem('story-flame-admin'));
        return user ? user.token : null;
    },

    // Install moment.js to work
    fullDate (value){
        return moment(value).format('MMMM Do YYYY, h:mm:ss a');
    },

    roundDecimal(num){
       return Math.round(num * 100) / 100
    },

    downloadAnyFile(file_url, title){
        axios.get(file_url, { responseType: 'blob' })
            .then(response => {
                const ext = file_url.split(/[#?]/)[0].split('.').pop().trim();
                const blob = new Blob([response.data], { type: 'application/'+ext });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = title +'.'+ ext;
                link.click();
                URL.revokeObjectURL(link.href)
            }).catch(console.error);
    },

    roundToTwoDecimal(num){
        return Math.round((num + Number.EPSILON) * 100) / 100;
    }
}

export default baseService;
