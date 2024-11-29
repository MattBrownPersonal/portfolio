<template>
    <v-dialog v-model="editProductDialog" max-width="1000px" persistent>
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit {{ selectedProduct.name }}</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage !== null">
                {{ warningMessage }}
            </v-alert>
            <v-form 
                ref="form" 
                v-model="valid"
            >
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="4">
                                <v-text-field 
                                    label="Product name*" 
                                    required
                                    :rules="[() => !!name || 'This field is required']" 
                                    :error-messages="errorMessages"
                                    v-model='name'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-autocomplete 
                                    :items="allSites" 
                                    label="Site*" 
                                    required
                                    :rules="[() => !!site_id || 'This field is required']"
                                    :error-messages="errorMessages" 
                                    v-model='site_id' 
                                    item-text="name"
                                    item-value="id"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-text-field 
                                    label="Product Price*" 
                                    prepend-inner-icon="mdi-currency-gbp" 
                                    required
                                    :rules="[() => !!price || 'This field is required']" 
                                    :error-messages="errorMessages"
                                    v-model='price'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-autocomplete 
                                    :items="allSuppliers" 
                                    label="Supplier*" 
                                    required
                                    :rules="[() => !!supplier || 'This field is required']"
                                    :error-messages="errorMessages" 
                                    v-model='supplier' 
                                    item-text="name"
                                    item-value="id"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-autocomplete 
                                    :items="allCategories" 
                                    label="Category" 
                                    v-model='memorialisation_id'
                                    :value="memorialisation_id" 
                                    item-text="memorialisation.name"
                                    item-value="memorialisation.id"
                                ></v-autocomplete>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-radio-group v-model="featured" row>
                                    <template v-slot:label>
                                        <div>Mark as featured?</div>
                                    </template>
                                    <v-radio label="Yes" value=1></v-radio>
                                    <v-radio label="No" value=0></v-radio>
                                </v-radio-group>
                            </v-col>
                            <v-col cols="12" sm="4">
                              <v-radio-group v-model="POA" row>
                                  <template v-slot:label>
                                      <div>Mark as POA (price on application)?</div>
                                  </template>
                                  <v-radio label="Yes" value=1></v-radio>
                                  <v-radio label="No" value=0></v-radio>
                              </v-radio-group>
                          </v-col>
                            <v-col cols="12">
                                <v-btn v-if="thumbnailImage"
                                @click="clearThumbnailImage()"
                                small
                                >Upload new image</v-btn>
                                <v-file-input v-if="!thumbnailImage"
                                    label="Image*" 
                                    show-size                                      
                                    v-model="image" 
                                    @change="previewImage()"
                                    accept="image/*"
                                    ref="file" 
                                    placeholder="Click to select image"
                                    :rules="[v => v&&v.size < 2000000 || 'Image required, and size should be less than 2 MB']"
                                ></v-file-input>
                                <v-img v-if="image" :src="url" width="150"></v-img>
                            </v-col>
                            <v-col cols="12">
                                <h4 class="text-center">Short Description</h4>
                                <wysiwyg v-model="shortDescription" />
                            </v-col>
                            <v-col cols="12">
                                <h4 class="text-center">Main Description</h4>
                                <wysiwyg v-model="description" />
                            </v-col>

                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close()">
                        Close
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="submit()">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>
import { bus } from '../../../../admin'
export default ({
    watch: {
    },
    props: ['editProductDialog', 'selectedProduct'],
    data: function () {
        return {
            warningMessage: null,
            allSuppliers: [],
            allCategories: [],
            allSites: [],
            errorMessages: '',
            featured: null,
            POA: null,
            image: [],
            name: '',
            price: '',
            supplier: '',
            memorialisation_id: '',
            description: '',
            shortDescription: '',
            id: '',
            sku: '',
            site_id: '',
            url: null,
            existingImage: false,
            thumbnailImage: null,
            valid: true,
        }
    },
    methods: {
        submit() {
            if (this.$refs.form.validate()) {
                const editedProduct = new FormData();
                editedProduct.append('name', this.name);
                editedProduct.append('price', this.price);
                editedProduct.append('supplier', this.supplier);
                if (this.memorialisation_id) {
                    editedProduct.append('category', this.memorialisation_id);
                }
                editedProduct.append('description', this.description);
                editedProduct.append('shortDescription', this.shortDescription);
                editedProduct.append('id', this.id);
                editedProduct.append('sku', this.sku);
                editedProduct.append('site_id', this.site_id);
                editedProduct.append('featured', this.featured);
                editedProduct.append('POA', this.POA);
                if (this.thumbnailImage) {
                    this.existingImage = true;
                    editedProduct.append("existingImage", this.existingImage);
                } else {
                    editedProduct.append("image", this.image);
                }
                editedProduct.append('_method', 'PUT');
                axios.post(`/api/products/${this.selectedProduct.id}`, editedProduct)
                    .then(res => {
                        this.close();
                        this.$emit('fetchSingleProduct');
                        this.$emit('triggerSnackBar', res.data.message);
                    })
                    .catch(err => {
                        this.warningMessage = false;
                    });
            }
        },
        fetchAllSuppliers() {
            axios.get('/api/suppliers')
                .then(res => {
                    this.allSuppliers = res.data;
                })
                .catch(err => {
                    this.errorMessages = err.response.data.message;
                });
        },
        fetchAllCategories() {
            axios.get(`/api/sitememorialisations/${this.$route.params.id}`)
                .then(res => {
                    this.allCategories = res.data;
                })
                .catch(err => {
                    this.errorMessages = err.response.data.message;
                });
        },
        fetchAllSites() {
            axios.get('/api/sites')
                .then(res => {
                    this.allSites = res.data;
                })
                .catch(err => {
                    this.errorMessages = err.response.data.message;
                });
        },
        close() {
            this.$emit('closeForm', false)
        },
        previewImage() {
            this.thumbnailImage = null;
            if (this.image) this.url = URL.createObjectURL(this.image);
        },
        clearThumbnailImage() {
            this.thumbnailImage = null;
        }
    },
    mounted() {
        this.fetchAllSuppliers();
        this.fetchAllCategories();
        this.fetchAllSites();
    },
    watch: {
        selectedProduct: function (propVal) {
            this.name = propVal.name
            this.price = parseFloat(propVal.price).toFixed(2)
            this.supplier = propVal.supplier.id
            this.memorialisation_id = propVal.memorialisation_id
            this.description = propVal.description
            this.shortDescription = propVal.short_description
            this.id = propVal.id
            this.sku = propVal.sku
            this.site_id = propVal.site_id
            this.featured = String(propVal.featured)
            this.POA = String(propVal.POA)
            this.thumbnailImage = propVal.thumbnail_image
            this.url = this.thumbnailImage
        }
    }

})
</script>
