const validationService = {

    validateEmail(email){

      if(email === undefined || email === null || email === ''){
        return false;
      }

      return String(email)
          .toLowerCase()
          .match(
              /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
          );
    },

    emailConfirmation(email, emailConfirmation){
      return email === emailConfirmation;
    },

    passwordConfirmation(password, passwordConfirmation){
      if(password !== "" || passwordConfirmation !== ""){
        return password === passwordConfirmation;
      }
    },

    validateCharacterLength(char, min = null, max = null){
      if(char !== "" && char !== null){
        if(min !== null && max !== null){
          return char.length >= min && char.length <= max;
        }else if(min !== null){
          return char.length >= min;
        }else if(max !== null){
          return char.length <= max;
        }
      }
    },

    validateFileType(file, allowedExtensions = []) {
      console.log("inside validation service", file, allowedExtensions);
      const fileName = file.name;
      const fileExtension = fileName.split('.').pop().toLowerCase();

      return allowedExtensions.includes(fileExtension);
    },

    validateFileSize(file, maxSize) {
      // Validate Image
      return file.size <= maxSize;
    },

  validateUrl(url){
    const pattern = new RegExp(
      '^(https?:\\/\\/)?' + // protocol
      '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
      '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR IP (v4) address
      '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
      '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
      '(\\#[-a-z\\d_]*)?$', // fragment locator
      'i'
    );
    return pattern.test(url);
  },

  deleteErrorsInObject(errors, key = null, deleteAll = false){
      if(key !== null && deleteAll === false) {
          Object.keys(errors).forEach((error) => {
              if (error === key) {
                  delete errors[key];
              }
          });
      }

      if(deleteAll === true && Object.keys(errors).length > 0) {
          errors = {};
      }
  }

}

export default validationService;
