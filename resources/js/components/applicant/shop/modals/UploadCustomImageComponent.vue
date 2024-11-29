<template>
    <v-dialog v-model="uploadCustomImageDialogue" persistent max-width="474px">
        <v-card class="py-0 py-md-5" style="height: 608px; overflow-x: hidden;">
            <v-card-text class="pt-0 pb-0" v-if="layoutType === 'custom'">
                <div class="row mt-5 mt-md-0">
                    <div class="col-1 px-0 py-0 offset-11 text-center">
                        <v-icon @click="closeForm()">mdi-close</v-icon>
                    </div>
                </div>
                <div class="row mt-0">
                    <div class="col-12 text-center upload-image-text">
                        <h6 class="h7 primary-colour">Upload Image</h6>
                        <p class="text-size-regular mb-0">This could be a photo of your loved one or another meaningful image</p>
                        <br>
                        <span class="h7 primary-colour">Step 1</span>
                        <br>
                        <span class="text-size-small">Select an image</span> 
                        <v-divider class="mb-0"></v-divider> 
                    </div>  
                </div>
                <div class="row">
                    <div class="col-12 text-center" style="padding-left: 53px; padding-right: 53px;">
                            <v-file-input accept="image/*" style="margin-bottom:50px" v-model="uploadedImage" label="Please click here to upload an image"></v-file-input>
                            <v-btn
                                class="btn"
                                rounded
                                block 
                                :color="styles.primary_colour" 
                                :style="{ color: styles.secondary_colour }"
                                @click="storeBaseImage()">
                                Save Image
                            </v-btn> <br><br>
                            <v-btn
                                rounded 
                                block :color="styles.primary_colour" 
                                :style="{ color: styles.primary_colour }"
                                @click="closeForm()" 
                                outlined
                                class="btn-outline btn-share-family"
                                >
                                Cancel
                            </v-btn>
                        </div>
                </div>
            </v-card-text>           
        </v-card>
    </v-dialog>
</template>
<script>
import { bus } from '../../../../app'
export default {
    props: ['uploadCustomImageDialogue', 'layoutType'],
    data() {
        return {
            img: null,
            uploadedImage: [],
            baseImageUrl: null,
        }
    },
    methods: {
        closeForm() {
            this.$emit('closeForm', false)
        },
        storeBaseImage() {
            if (this.uploadedImage.length<1) return; // user has failed to select an image
            this.baseImageUrl = URL.createObjectURL(this.uploadedImage);
            this.$emit('closeForm', false)
            bus.$emit('uploadedImage', this.baseImageUrl)
            bus.$emit('additionalImageDialogue', true)
        },
    },
    computed: {
        styles() {
            return this.$store.state.styles;
        }
    },
}
</script>
