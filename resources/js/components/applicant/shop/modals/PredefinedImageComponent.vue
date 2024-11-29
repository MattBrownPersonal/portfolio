<template>
  <div>
      <v-dialog
          v-model="predfinedImageDialogue"
          max-width="1030px"
          @click:outside="closeForm()"
          scrollable
          >
          <v-card class="select-predefined-image" height="800px">
              <v-card-text class="pt-5 pb-0">
                  <div class="row ">
                      <div class="col-12 text-right">
                          <v-icon @click="closeForm()">mdi-close</v-icon>
                      </div>
                  </div>
                  <div class="predefined-title">
                    <h6 class="primary-colour">Select an image</h6>
                  </div>
                  <div class="predefined">
                    <div class="predefined-box" v-for="image in allImages" >
                      <div height="auto" class="predefined-containers" :style="{ 
                              backgroundImage: `url('${image.imageurl}')`,                                 
                           }">
                      </div>
                      <div>
                        <v-btn
                          class="btn predefined-buttons"
                          :color="styles.primary_colour"
                          :style="{ color: styles.secondary_colour}"
                          @click="selectImage(image.imageurl, image.price)">Select</v-btn>
                      </div>
                    </div>
                  </div>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="snackbar" :timeout="timeout" color="green">
            {{ snackbarText }}
            <template v-slot:action="{ attrs }">
                <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>
<script>
import axios from 'axios';
import { bus } from '../../../../app'
export default {
    props: ['predfinedImageDialogue', 'layoutType', 'allImages'],
    data() {
        return {
            outputCanvas: null,
            outputContext: null,
            images: [],
            selectedImage: null,
            selectImagePrice: null,
            img: null,
            imageScale: null,
            snackbar: false,
            snackbarText: '',
            timeout: 4000,
            cardHeight: '100%'
        }
    },
    methods: {
        closeForm() {
            this.$emit('closeForm', false)
        },
        selectImage(imageSrc, price) {
            this.selectedImage = imageSrc;
            this.selectImagePrice = price;
            localStorage.setItem('predefinedImage', this.selectedImage);
            localStorage.setItem('predefinedImageStamp', +new Date());
            this.closeForm();
            bus.$emit('layoutType', 'predefined');
            bus.$emit('imagePrice', this.selectImagePrice)
            bus.$emit('updatePredefinedImage',{ image: this.selectedImage, price: this.selectImagePrice });
            bus.$emit('filterCarousel');
        },
    },
    watch: {
        allImages: function (propVal) {
            this.image = propVal;
        }
    },
    computed: {
        styles() {
            return this.$store.state.styles;
        },
        outputCanvasSize() {
            switch (this.$vuetify.breakpoint.name) {
                case "xs":
                    return { "width": 200, "height": 200 };
                default:
                    const newHeight = 400;
                    return { "width": 400, "height": newHeight };
            }
        }
    },
}
</script>
<style scoped>
.canvasStyle {
    margin-left: auto;
    margin-right: auto;
    display: block;
}
</style>