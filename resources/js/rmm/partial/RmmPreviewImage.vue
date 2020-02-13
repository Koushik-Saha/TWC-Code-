<template>
    <div class="modal wow animated bounceInUp rmm-preview-modal" tabindex="-1" role="dialog" id="rmmPreviewModal"
         aria-labelledby="rmmPreviewModalLabel" aria-hidden="true" data-wow-duration="1s">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <span class="close-preview" title="Close Image" @click.prevent="closePreviewModal">
                        <i class="fa fa-times"></i>
                    </span>
                    <div class="image-container">
                        <div class="image-loading" v-if="!imageLoaded">
                            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                            <span>Loading...</span>
                        </div>
                        <img :src="image_url" v-show="imageLoaded" v-on:load="imageOnLoad">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['image_url'],
        data() {
            return {
                imageLoaded: false,
            }
        },
        methods: {
            imageOnLoad() {
                this.imageLoaded = true;
            },
            closePreviewModal() {
                this.imageLoaded = false;
                this.$emit('close-preview-modal');
            }
        }
    }
</script>