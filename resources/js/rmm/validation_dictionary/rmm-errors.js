/* Vee Validate Error Messages For Rbt Media Manager */
const rmmValidationErrors = {
    en: {
        custom: {
            directory_name: {
                required: () => 'Directory Name can\'t be empty!',
                regex: () => 'Directory Name can not contain any special character and must start and end with alphanumeric value!'
            },
            image: {
                mimes: () => 'The image type must be valid jpeg/png/gif...!'
            }
        }
    }
};

export default rmmValidationErrors;
