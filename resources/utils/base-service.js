let baseService = {
    // Install moment.js to work
    fullDate (value){
        return moment(value).format('MMMM Do YYYY, h:mm:ss a');
    },

    roundDecimal(num){
       return Math.round(num * 100) / 100
    },

    deleteCookies(){
        const cookies = document.cookie.split("; ");
        console.log(cookies);
        for (let c = 0; c < cookies.length; c++) {
            const d = window.location.hostname.split(".");
            while (d.length > 0) {
                const cookieBase = encodeURIComponent(cookies[c].split(";")[0].split("=")[0]) + '=; expires=Thu, 01-Jan-1970 00:00:01 GMT; domain=' + d.join('.') + ' ;path=';
                const p = location.pathname.split('/');
                document.cookie = cookieBase + '/';
                while (p.length > 0) {
                    document.cookie = cookieBase + p.join('/');
                    p.pop();
                }
                d.shift();
            }
        }

        console.log("Cookie deleted");
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
