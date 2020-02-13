<template>
    <div class="modal wow animated bounceInUp delete-folder-modal" id="deleteFolderModal" tabindex="-1" role="dialog"
         aria-labelledby="deleteFolderModalLabel" aria-hidden="true" data-wow-duration="1s">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title w-100" id="deleteFolderModalLabel">Delete Folder</h5>
                    <button type="button" class="close" @click.prevent="closeDeleteModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    Are you sure want to delete "{{ delete_directory }}"?
                </div>
                <div class="modal-footer">
                    <p class="w-100">
                        <button class="btn btn-light text-left" type="button" @click.prevent="closeDeleteModal">Cancel</button>
                        <button class="btn btn-danger pull-right" type="button" @click.prevent="deleteDirectory">
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
        props: ['delete_url', 'delete_directory'],
        data() {
            return {
                deleteButton: 'Confirm',
                deleting: false,
                serverError: null
            }
        },
        methods: {
            closeDeleteModal() {
                this.$emit('close-delete-folder-modal');
            },
            deleteDirectory() {
                this.deleteButton = 'Deleting';
                this.deleting = true;
                axios.delete(this.delete_url + '/' + this.delete_directory)
                    .then((response) => {
                        this.$emit('directory-deleted');
                        this.closeDeleteModal();
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