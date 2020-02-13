<template>
    <div class="rmm-container">
        <div class="rbt-media-manager" :class="{'modal fade': showAsModal}" :role="showAsModal ? 'dialog' : ''" :tabindex="showAsModal ? '-1' : ''"
             id="rbtMediaManager" :aria-labelledby="showAsModal ? 'rmmModalLabel' : ''" :aria-hidden="showAsModal ? 'true' : 'false'">

            <div :class="{'modal-dialog': showAsModal}">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="rmmModalLabel">Media Manager</h5>
                        <button v-if="showAsModal" type="button" class="close" @click.prevent="closeRmmModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="rmm-header-content">
                            <div class="rmm-top-nav">
                                <div class="btn-group add-new" role="group">
                                    <a href="#" class="btn btn-light" title="Upload" @click.prevent="openModal('rmmUploadModal')">
                                        <i class="fa fa-upload"></i> <span class="d-none d-lg-inline">Upload</span>
                                    </a>
                                    <a href="#" class="btn btn-light" title="Add Folder" @click.prevent="openModal('addFolderModal')">
                                        <i class="fa fa-folder"></i> <span class="d-none d-lg-inline">Add Folder</span>
                                    </a>
                                </div>

                                <div class="btn-group reload" role="group">
                                    <a href="#" class="btn btn-light" title="Reload" @click.prevent="reload">
                                        <i class="fa fa-retweet" :class="{'fa-pulse': reloading}"></i> <span class="d-none d-lg-inline">{{ reloadButton }}</span>
                                    </a>
                                </div>

                                <div class="btn-group view-as" role="group">
                                    <a href="#" class="btn btn-light" :class="{'active': viewFilesAs === 'thumbnail'}" title="Thumbnail View" @click.prevent="setFilesView('thumbnail')">
                                        <i class="fa fa-th-large"></i> <span class="d-none d-lg-inline">Thumbnail</span>
                                    </a>
                                    <a href="#" class="btn btn-light" :class="{'active': viewFilesAs === 'list'}" title="List View" @click.prevent="setFilesView('list')">
                                        <i class="fa fa-list"></i> <span class="d-none d-lg-inline">List</span>
                                    </a>
                                </div>
                            </div>

                            <div class="rmm-action-status">

                            </div>
                        </div>

                        <div class="rmm-main-content">
                            <div class="rmm-sidebar-nav">
                                <a href="#" class="main-directory" :class="{'active' : directories.selectedDirectory === '/' }" @click.prevent="selectDirectory('/')">
                                    <i class="fa fa-folder-open"></i> {{ directories.mainDirectory }}
                                </a>
                                <ul class="nav flex-column">
                                    <li class="nav-item" v-for="directoryName in directories.subDirectories" v-if="directoryName !== 'user'">
                                        <a class="nav-link" :class="{'active' : directoryName === directories.selectedDirectory}" href="#">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-light" @click.prevent="selectDirectory(directoryName)">
                                                    <i class="fa fa-folder"></i> {{ directoryName }}
                                                </button>
                                                <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item text-secondary" href="#" @click.prevent="selectDirectory(directoryName)"><i class="fa fa-folder-open-o"></i> Open</a>
                                                    <a class="dropdown-item text-info" href="#" @click.prevent="renameDirectory(directoryName)"><i class="fa fa-pencil-square-o"></i> Rename</a>
                                                    <a class="dropdown-item text-danger" href="#" @click.prevent="deleteDirectory(directoryName)"><i class="fa fa-trash-o"></i> Delete</a>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="rmm-library">
                                <div class="library-header">
                                    Library
                                </div>

                                <div class="library-content">
                                    <rmm-files :files="displayFiles" :view_url="imageViewRoute" :directory="directories.selectedDirectory" :view_as="viewFilesAs"
                                               @open-preview-modal="openPreviewModal" @open-delete-modal="openDeleteModal" @image-selected="imageSelected" @make-toast="makeToast">

                                    </rmm-files>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Upload Modal -->
        <rmm-new-file @close-upload-modal="closeModal('rmmUploadModal')" @make-toast="makeToast" :upload_url="uploadDirectory" @upload-completed="uploadCompleted"></rmm-new-file>

        <!-- Add Folder Modal -->
        <rmm-new-folder @close-add-folder-modal="closeModal('addFolderModal')" @make-toast="makeToast" @directory-added="directoryAdded"
                        :directory_url="url_prefix + '/add-directory'">
        </rmm-new-folder>

        <!-- Rename Folder Modal -->
        <rmm-rename-folder @close-rename-folder-modal="closeModal('renameFolderModal')" @make-toast="makeToast" :rename_url="url_prefix + '/update-directory'"
                              :rename_directory="directoryToRename" @directory-renamed="directoryRenamed">
        </rmm-rename-folder>

        <!-- Delete Folder Modal -->
        <rmm-delete-folder @close-delete-folder-modal="closeModal('deleteFolderModal')" @make-toast="makeToast" :delete_url="url_prefix + '/remove-directory'"
                           :delete_directory="directoryToDelete" @directory-deleted="directoryDeleted">

        </rmm-delete-folder>

        <preview-image :image_url="previewImageUrl" @close-preview-modal="closeModal('rmmPreviewModal')"></preview-image>

        <delete-image :delete_url="url_prefix + '/remove-image'" :delete_image_file="imageToDelete" @close-delete-modal="closeModal('deleteImageModal')"
                          @image-deleted="imageDeleted">

        </delete-image>

    </div>
</template>

<!--<style lang="scss">-->
<!--    @import "./sass/rmm";-->
<!--</style>-->

<script>

    import { Validator } from 'vee-validate';
    import RmmValidationErrors from './validation_dictionary/rmm-errors';
    Validator.localize(RmmValidationErrors);

    import RmmNewFile from './partial/RmmNewFile';
    import RmmNewFolder from './partial/RmmNewFolder';
    import RmmRenameFolder from './partial/RmmRenameFolder';
    import RmmDeleteFolder from './partial/RmmDeleteFolder';
    import RmmFiles from './partial/RmmFiles';
    import RmmPreviewImage from './partial/RmmPreviewImage';
    import RmmDeleteImage from './partial/RmmDeleteImage';

    export default {
        props: ['directory', 'user_id', 'url_prefix', 'show_as', 'element_id'],
        components: {
            'rmm-new-file': RmmNewFile,
            'rmm-new-folder': RmmNewFolder,
            'rmm-rename-folder': RmmRenameFolder,
            'rmm-delete-folder': RmmDeleteFolder,
            'rmm-files': RmmFiles,
            'preview-image': RmmPreviewImage,
            'delete-image': RmmDeleteImage
        },
        data() {
            return {
                directories: {
                    mainDirectory: '',
                    subDirectories: [],
                    selectedDirectory: '',
                },
                imageViewRoute: '',
                displayFiles: [],
                viewFilesAs: 'thumbnail',
                previewImageUrl: '',
                imageToDelete: '',
                uploadedImageUrl: '',
                createdDirectory: '',
                errorStatus: null,
                directoryToRename: '',
                directoryToDelete: '',
                reloadButton: 'Reload',
                reloading: false
            }
        },
        methods: {
            getDirectories() {
                axios.get(this.url_prefix + '/get-directories')
                    .then((response) => {
                        //console.log(response);
                        this.directories.mainDirectory = response.data.main_directory;
                        this.directories.subDirectories = response.data.sub_directories;
                        this.imageViewRoute = response.data.image_view_route;
                        if(this.directory === undefined || this.directory === 'main') {
                            this.directories.selectedDirectory = '/';
                        }
                        else if(response.data.sub_directories.includes(this.directory)) {
                            this.directories.selectedDirectory = this.directory;
                        }
                        else {
                            this.directories.selectedDirectory = null;
                            this.errorStatus = '"' + this.directory + '" is not found!';
                        }
                        this.getFiles();
                    })
                    .catch((error) => {
                        //console.log(error);
                    });
            },
            selectDirectory(directory) {
                this.directories.selectedDirectory = directory;
                this.getFiles();
            },
            getFiles() {
                this.displayFiles = [];
                let url = '';
                if(this.directories.selectedDirectory === '/') {
                    url = this.url_prefix + '/get-files';
                }
                else {
                    url = this.url_prefix + '/get-files/' + this.directories.selectedDirectory;
                }
                axios.get(url)
                    .then((response) => {
                        this.displayFiles = response.data;
                    })
                    .catch((error) => {
                        //console.log(error.response);
                        this.showToastMsg(error.response.data.error, 'error', 5000);
                    });
            },
            closeRmmModal() {
                $('#rbtMediaManager').modal('hide');
            },
            openModal(id) {
                if(this.showAsModal) {
                    this.closeRmmModal();
                }
                $('#' + id).modal('show');
            },
            closeModal(id) {
                $('#' + id).modal('hide');
                if(this.showAsModal) {
                    $('#rbtMediaManager').modal('show');
                }
            },
            openPreviewModal(url) {
                this.previewImageUrl = url;
                this.openModal('rmmPreviewModal');
            },
            openDeleteModal(file) {
                this.imageToDelete = file;
                this.openModal('deleteImageModal');
            },
            renameDirectory($directory) {
                this.directoryToRename = $directory;
                this.openModal('renameFolderModal');
            },
            deleteDirectory($directory) {
                this.directoryToDelete = $directory;
                this.openModal('deleteFolderModal');
            },
            setFilesView(showAs) {
                this.viewFilesAs = showAs;
            },
            reload() {
                let directory = this.directories.selectedDirectory;
                this.reloadButton = 'Reloading';
                this.reloading = true;
                this.getDirectories();
                this.displayFiles = [];
                window.setTimeout(function() {
                    this.selectDirectory(directory);
                    this.getFiles();
                    this.reloadButton = 'Reload';
                    this.reloading = false;
                }.bind(this), 500);
            },
            showToastMsg(msg, method = 'show', duration = 2500) {
                this.$toasted[method](msg, {
                    action : {
                        text : '',
                        icon: 'times',
                        onClick : (e, toastObject) => {
                            toastObject.goAway(0);
                        }
                    },
                    duration: duration
                });
            },
            makeToast(toast) {
                this.showToastMsg(toast.msg, toast.method, toast.duration);
            },
            uploadCompleted(imageUrl) {
                this.uploadedImageUrl = imageUrl;
                this.showToastMsg('Image successfully uploaded!', 'success', 3000);
                this.getFiles();
            },
            directoryAdded($directory) {
                this.createdDirectory = $directory;
                this.getDirectories();
                this.selectDirectory($directory);
                this.showToastMsg($directory + ' created successfully..!', 'success', 3000);
            },
            directoryRenamed($directory) {
                this.getDirectories();
                this.displayFiles = [];
                this.showToastMsg('Folder renamed successfully..!', 'success', 3000);
                window.setTimeout(function() {
                    this.selectDirectory($directory);
                    this.getFiles();
                }.bind(this), 500);
            },
            directoryDeleted() {
                this.directories.selectedDirectory = '/';
                this.getDirectories();
                this.showToastMsg('Folder deleted successfully..!', 'success', 3000);
                this.getFiles();
            },
            imageDeleted() {
                this.showToastMsg('Image deleted successfully..!', 'success', 3000);
                this.getFiles();
            },
            imageSelected(image) {
                this.$emit('image-selected', image);
                this.closeRmmModal();
            }
        },
        created() {
            this.getDirectories();
        },
        computed: {
            showAsModal() {
                if(this.show_as === undefined || this.show_as === 'page') {
                    return false;
                }
                else if(this.show_as === 'modal') {
                    return true;
                }
            },
            uploadDirectory() {
                if(this.directories.mainDirectory === this.directories.selectedDirectory || this.directories.selectedDirectory === '/') {
                    return this.url_prefix + '/upload';
                }
                else if(this.directories.selectedDirectory.length > 0 && this.directories.selectedDirectory !== '/') {
                    return this.url_prefix + '/upload/' + this.directories.selectedDirectory;
                }
                return null;
            }
        }
    }
</script>
