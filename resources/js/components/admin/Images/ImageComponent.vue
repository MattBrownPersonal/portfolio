<template>
<v-app>
    <h1 class="text-center">Image Test</h1>
    <v-row>
        <v-col class="col-10 offset-1 text-center">
            <v-form>
                <v-file-input
                show-size
                v-model="image"
                @change="Preview_image()"
                ref="file"
                accept="image/*"
                ></v-file-input>
                <v-img 
                v-if="imgUrl"
                :src="imgUrl"
                max-width="200"></v-img>
                <v-btn @click="uploadImage">Add Image</v-btn>
            </v-form>
            <v-row class="mt-4">
                <v-col class="col-4" v-for="image in allImages" :key="image.key">
                    <a :href="image.imageurl" target="_blank">
                        <v-img :src="image.imageurl" max-width="400"></v-img>
                    </a>   
                </v-col>
            </v-row>
        </v-col>
    </v-row>

</v-app>
</template>
<script>
import axios from 'axios';
export default {
data () {
        return {
            image: null,
            imgUrl: null,
            allImages: [],
            adjustedImages: [],
            snackbar: false,
            snackbarText: "",
            timeout: 6000,
        }
    },
    methods: {
        getImages() {
        axios.get('api/imageuploads') 
            .then(res => {
                this.allImages = res.data;
            })
            .catch(err => {
                this.snackbar = true;
                this.snackbarText = err.response.data.message;
            });
        },
        uploadImage() {
            let imageData = new FormData();
            imageData.append('file', this.image);
            axios.post('api/imageuploads', imageData,{headers: {"Content-Type": "multipart/form-data"}})
            .then( res => {
                this.getImages(),
                this.imgUrl = null,
                this.image = null
            })
                .catch(err => {
                    this.snackbar = true;
                    this.snackbarText = err.response.data.message;
                });
        },
        Preview_image() {
            this.imgUrl= URL.createObjectURL(this.image)
        }, 
    },
    mounted() {
       this.getImages();
    }
}
</script>