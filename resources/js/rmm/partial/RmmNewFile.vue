<template>
    <div class="modal wow animated bounceInUp rmm-upload-modal" tabindex="-1" role="dialog" id="rmmUploadModal"
         aria-labelledby="rmmUploadModalLabel" aria-hidden="true" data-wow-duration="1s">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title w-100">Upload New File</h5>
                    <button type="button" class="close" aria-label="Close" @click.prevent="closeUploadModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form enctype="multipart/form-data" method="POST" class="text-center">
                        <div class="form-group view-file">
                            <div>
                                <i class="fa fa-file-image-o" v-if="!image.image_data"></i>
                            </div>
                            <img :src="image.image_data" v-if="image.image_data" alt="New Upload" style="max-width: 100%; max-height: 350px; margin-bottom: 15px;">
                        </div>

                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" v-validate="'mimes:image/jpeg,image/gif,image/png'" accept="image/*" v-on:change="onFileChange" :class="[(errors.has('image') || serverError) ? 'is-invalid' : '']" name="image" class="form-control custom-file-input" id="uploadFile">
                                <label class="custom-file-label" for="uploadFile">Choose file</label>
                                <div class="invalid-feedback" style="margin-top: 10px;" v-if="errors.has('image')">
                                    {{ errors.first('image') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" @click.prevent="uploadNew" class="btn btn-success" :disabled="!enableUploadBtn">
                                {{ uploadButton }} <i class="fa fa-spinner fa-pulse fa-fw" v-if="uploading"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['upload_url'],
        data() {
            return {
                image: {
                    image_data: '',
                    original_name: '',
                    user_id: this.$parent.user_id
                },
                enableUploadBtn: false,
                uploading: false,
                uploadButton: 'Upload',
                serverError: null
            }
        },
        methods: {
            closeUploadModal() {
                this.$emit('close-upload-modal');
            },
            onFileChange(e) {
                this.$validator.validate().then((result) => {
                    if(!result) {
                        return;
                    }
                    this.serverError = null;
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length) {
                        this.enableUploadBtn = false;
                        return;
                    }
                    this.createImage(files[0]);
                    this.image.original_name = files[0].name;
                });
            },
            createImage(file) {
                let reader = new FileReader();
                let self = this;
                reader.onload = (e) => {
                    self.image.image_data = e.target.result;
                    this.enableUploadBtn = true;
                };
                reader.readAsDataURL(file);
            },
            uploadNew() {
                this.uploadButton = 'Uploading';
                this.uploading = true;

                axios.post(this.upload_url, this.image)
                    .then((response) => {
                        this.$emit('upload-completed', response.data.image_url);
                        this.closeUploadModal();
                        this.image.image_data = '';
                        this.image.original_name = '';
                        this.uploadButton = 'Upload';
                        this.uploading = false;
                    })
                    .catch((error) => {
                        if('error' in error.response.data) {
                            this.serverError = error.response.data.error;
                            this.makeToast(error.response.data.error, 'error', 5000);
                            this.uploadButton = 'Upload';
                            this.uploading = false;
                        }
                    });
            },
            makeToast(msg, method, duration) {
                let toast = {
                    msg: msg,
                    method: method,
                    duration: duration
                };
                this.$emit('make-toast', toast);
            }
        }
    }
</script>