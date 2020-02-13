<template>
    <div class="modal wow animated bounceInUp delete-image-modal" id="deleteImageModal" tabindex="-1" role="dialog"
         aria-labelledby="deleteImageModalLabel" aria-hidden="true" data-wow-duration="1s">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title w-100" id="deleteImageModalLabel">Delete Image</h5>
                    <button type="button" class="close" @click.prevent="closeDeleteImageModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    Are you sure want to delete this image?
                </div>
                <div class="modal-footer">
                    <p class="w-100">
                        <button class="btn btn-light text-left" type="button" @click.prevent="closeDeleteImageModal">Cancel</button>
                        <button class="btn btn-danger pull-right" type="button" @click.prevent="deleteImage">
                            {{ deleteButton }} <i class="fa fa-spinner fa-pulse fa-fw" v-if="deleting"></i>
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['delete_url', 'delete_image_file'],
        data() {
            return {
                deleteButton: 'Confirm',
                deleting: false,
                serverError: null
            }
        },
        methods: {
            closeDeleteImageModal() {
                this.$emit('close-delete-modal');
            },
            deleteImage() {
                this.deleteButton = 'Deleting';
                this.deleting = true;
                axios.post(this.delete_url, {image: this.delete_image_file})
                    .then((response) => {
                        this.$emit('image-deleted');
                        this.closeDeleteImageModal();
                    })
                    .catch((error) => {
                        this.serverError = error.response.data.error;
                        this.makeToast(error.response.data.error, 'error', 5000);
                    });
                this.deleteButton = 'Confirm';
                this.deleting = false;
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