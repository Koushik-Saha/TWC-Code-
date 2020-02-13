<template>
    <div class="modal wow animated bounceInUp add-folder-modal" id="addFolderModal" tabindex="-1" role="dialog"
         aria-labelledby="addFolderModalLabel" aria-hidden="true" data-wow-duration="1s">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title w-100" id="addFolderModalLabel">Add New Folder</h5>
                    <button type="button" class="close" @click.prevent="closeUploadModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" class="text-center" @submit.prevent="addDirectory">
                        <div class="form-group">
                            <label for="folderName" class="sr-only">Directory Name</label>
                            <input type="text" class="form-control" v-validate="'required|regex:^[a-zA-Z0-9 ]+([-._][a-zA-Z0-9 ]+)*$'" v-model="directoryName" autocomplete="off"
                                   data-vv-validate-on="keyup" :class="[(errors.has('directory_name') || serverError) ? 'is-invalid' : '']" name="directory_name" id="folderName" placeholder="Folder Name">

                            <div class="invalid-feedback" style="margin-top: 10px;" v-if="errors.has('directory_name')">
                                {{ errors.first('directory_name') }}
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button class="btn btn-success" type="submit" :disabled="!enableAddButton">
                                {{ addButton }} <i class="fa fa-spinner fa-pulse fa-fw" v-if="adding"></i>
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
        props: ['directory_url'],
        data() {
            return {
                directoryName: '',
                addButton: 'Create Folder',
                adding: false,
                serverError: null
            }
        },
        methods: {
            closeUploadModal() {
                this.$emit('close-add-folder-modal');
            },
            addDirectory() {
                this.$validator.validate().then((result) => {
                    if(!result) {
                        this.enableAddButton = false;
                        return;
                    }
                    this.addButton = 'Creating Folder';
                    this.adding = true;

                    axios.post(this.directory_url, {
                        directory_name: this.directoryName
                    })
                        .then((response) => {
                            this.$emit('directory-added', response.data);
                            this.closeUploadModal();
                            this.directoryName = '';
                            this.serverError = null;
                        })
                        .catch((error) => {
                            this.serverError = error.response.data.error;
                            this.makeToast(error.response.data.error, 'error', 5000);
                        });
                    this.addButton = 'Create Folder';
                    this.adding = false;
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
        },
        computed: {
            enableAddButton() {
                if(Object.keys(this.fields).some(key => this.fields[key].invalid)) {
                    return false;
                }
                return true;
            }
        },
    }
</script>