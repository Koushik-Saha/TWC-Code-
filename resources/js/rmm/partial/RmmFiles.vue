<template>
    <div class="all-files">
        <div class="row">
            <div class="col-md-4 col-lg-3" v-for="file in files" v-if="view_as === 'thumbnail'">
                <div class="rmm-file-container">
                    <div class="file-image" title="Select Image" @click.prevent="selectImage(file)">
                        <img :src="fileUrl(file, 'large')" alt="" class="w-100">
                    </div>
                    <!-- Image Option -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-light" style="overflow: hidden;">{{ file }}</button>
                        <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item text-secondary" href="#" @click.prevent="previewImage(file)">
                                <i class="fa fa-eye"></i> Preview
                            </a>
                            <a class="dropdown-item text-secondary" href="#" @click.prevent="copyLinkToClipboard(file)">
                                <i class="fa fa-link"></i> Get Link
                            </a>
                            <a class="dropdown-item text-secondary" href="#" @click.prevent="downloadImage(file)">
                                <i class="fa fa-download"></i> Download
                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item text-info" href="#">
                                <i class="fa fa-arrows"></i> Resize</a>
                            <a class="dropdown-item text-info" href="#">
                                <i class="fa fa-crop"></i> Crop
                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item text-danger" href="#" @click.prevent="deleteImage(file)">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" v-if="view_as === 'list'">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Image</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="file in files">
                                <td>{{ file }}</td>
                                <td class="text-center">
                                    <a class="text-secondary" title="Preview" href="#" @click.prevent="previewImage(file)">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a class="text-secondary" title="Get Link" href="#" @click.prevent="copyLinkToClipboard(file)">
                                        <i class="fa fa-link"></i>
                                    </a>
                                    <a class="text-secondary" title="Download" href="#" @click.prevent="downloadImage(file)">
                                        <i class="fa fa-download"></i>
                                    </a>
                                    <a class="text-info" title="Resize" href="#">
                                        <i class="fa fa-arrows"></i>
                                    </a>
                                    <a class="text-info" title="Crop" href="#">
                                        <i class="fa fa-crop"></i>
                                    </a>

                                    <a class="text-danger" title="Delete" href="#" @click.prevent="deleteImage(file)">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <span class="sr-only" id="linkToCopy">{{ linkToCopy }}</span>
    </div>
</template>

<script>
    export default {
        props: ['files', 'view_url', 'directory', 'view_as'],
        data() {
            return {
                linkToCopy: '',
            }
        },
        methods: {
            fileUrl(file, size) {
                let url = this.view_url + '/'+ size + '/';
                if(this.directory === '/' || this.directory === undefined) {
                    url = url + file;
                }
                else {
                    url = url + this.directory + '/' + file
                }
                return url;
            },
            fileUrlOriginal(file) {
                let fileUrl = '';
                if(this.directory === '/' || this.directory === undefined) {
                    fileUrl = file;
                }
                else {
                    fileUrl = this.directory + '/' + file
                }
                return fileUrl
            },
            previewImage(file) {
                this.$emit('open-preview-modal', this.fileUrl(file, 'original'));
            },
            selectImage(file) {
                if(this.$parent.show_as === 'modal') {
                    let fileCache = this.fileUrl(file, 'original');
                    let fileUrl = this.fileUrlOriginal(file);
                    if(this.$parent.element_id !== undefined) {
                        let imageElement = '#' + this.$parent.element_id + ' > img';
                        let inputElement = '#' + this.$parent.element_id + ' > input';
                        $(imageElement).attr('src', fileCache);
                        $(inputElement).attr('value', fileUrl);
                    }
                    this.$emit('image-selected', {
                        cache: fileCache,
                        original: fileUrl
                    });
                }
                else {
                    this.copyLinkToClipboard(file);
                }
            },
            deleteImage(file) {
                this.$emit('open-delete-modal', this.fileUrlOriginal(file));
            },
            copyLinkToClipboard(file) {
                this.linkToCopy = this.fileUrl(file, 'original');

                let inp =document.createElement('input');
                document.body.appendChild(inp);
                inp.value = this.linkToCopy;
                inp.select();
                let copied = document.execCommand('copy', false);
                inp.remove();
                if(copied) {
                    this.makeToast('Link copied successfully..!', 'success', 5000);
                }
                else {
                    this.makeToast('Link can\'t be copied..!', 'error', 5000);
                }
            },
            downloadImage(file) {
                let url = this.$parent.url_prefix + '/download-image';
                axios.post(url, { image: this.fileUrlOriginal(file) }, { responseType: 'blob' })
                    .then((response) => {
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'image.jpg');
                        document.body.appendChild(link);
                        link.click();
                    })
                    .catch((error) => {
                        this.makeToast(error.response.data.error, 'error', 5000);
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
