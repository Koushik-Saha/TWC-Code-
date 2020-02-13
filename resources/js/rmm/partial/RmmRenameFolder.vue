<template>
    <div class="modal wow animated bounceInUp rename-folder-modal" id="renameFolderModal" tabindex="-1" role="dialog"
         aria-labelledby="renameFolderModalLabel" aria-hidden="true" data-wow-duration="1s">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title w-100" id="renameFolderModalLabel">Rename Folder</h5>
                    <button type="button" class="close" @click.prevent="closeRenameModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" class="text-center" @submit.prevent="renameDirectory">
                        <div class="form-group">
                            <label for="folderName" class="sr-only">Directory Name</label>
                            <input type="text" class="form-control" v-validate="'required|regex:^[a-zA-Z0-9 ]+([-._][a-zA-Z0-9 ]+)*$'" v-model="directoryName" autocomplete="off"
                                   data-vv-validate-on="keyup" :class="[(errors.has('directory_name') || serverError) ? 'is-invalid' : '']" name="directory_name" id="folderName" placeholder="Folder Name" autofocus>

                            <div class="invalid-feedback" style="margin-top: 10px;" v-if="errors.has('directory_name')">
                                {{ errors.first('directory_name') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success" type="submit" :disabled="!enableRenameButton">
                                {{ renameButton }} <i class="fa fa-spinner fa-pulse fa-fw" v-if="renaming"></i>
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
        props: ['rename_url', 'rename_directory'],
        data() {
            return {
                directoryName: '',
                renameButton: 'Rename Folder',
                renaming: false,
                serverError: null
            }
        },
        methods: {
            closeRenameModal() {
                this.$emit('close-rename-folder-modal');
            },
            renameDirectory() {
                this.$validator.validate().then((result) => {
                    if(!result) {
                        this.enableRenameButton = false;
                        return;
                    }
                    this.renameButton = 'Renaming Folder';
                    this.renaming = true;

                    axios.patch(this.rename_url, {
                        rename_from: this.rename_directory,
                        rename_to: this.directoryName
                    })
                        .then((response) => {
                            this.$emit('directory-renamed', this.directoryName);
                            this.closeRenameModal();
                        })
                        .catch((error) => {
                            this.serverError = error.response.data.error;
                            this.makeToast(error.response.data.error, 'error', 5000);
                        });
                    this.renameButton = 'Rename Folder';
                    this.renaming = false;
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
        watch: {
            'rename_directory': function(newVal, oldVal) {
                this.directoryName = newVal;
            }
        },
        computed: {
            enableRenameButton() {
                if(Object.keys(this.fields).some(key => this.fields[key].invalid)) {
                    return false;
                }
                if(this.rename_directory === this.directoryName) {
                    return false;
                }
                return true;
            }
        },
    }
</script>