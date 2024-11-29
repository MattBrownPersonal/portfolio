<template>
    <v-dialog
      v-model="newSiteProductDialog"
      max-width="1000px"
      persistent
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Add New Product</span>
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
                            <v-col 
                                cols="12"
                                class="text-center"
                            >
                                <span class="text-h5">
                                    Choose from saved products                               
                                </span>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col 
                                cols="12"
                                class="text-center"
                            >
                                <v-select
                                    :items="savedProducts"
                                    v-model="selectedProduct"
                                    label="Standard"
                                    item-text="name"
                                    item-value="id"
                                    clearable
                                ></v-select>
                            </v-col>
                        </v-row>
                        <span v-if="!selectedProduct">
                            <v-row>
                                <v-col
                                >
                                <v-text-field
                                    label="Product*"
                                    required
                                    v-model='name'
                                    @change="productError = null"
                                    :error-messages="productError"
                                    :rules="rules"
                                    name="product"
                                ></v-text-field>                           
                                </v-col>
                                <v-col
                                v-if="usesCategories === 1"
                                >
                                <v-select
                                    :items="allCategories"
                                    label="Category*"
                                    required
                                    :rules="[v => !!v || 'Category is required']"
                                    v-model='selectedCategory'
                                    item-text="memorialisation.name"
                                    item-value="memorialisation_id"
                                    name="category"
                                ></v-select>
                                </v-col>
                                <v-col
                                >                           
                                <v-text-field
                                    label="Product Price*"
                                    prepend-inner-icon="mdi-currency-gbp"
                                    required
                                    v-model='price'
                                    :error-messages="priceError"
                                    @change="priceError = null"
                                    :rules="rules"
                                    name="price"
                                ></v-text-field>
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
                        </span>   
                        <v-row v-else>
                            <v-col
                            cols="12"
                            v-if="usesCategories === 1"
                            >
                                <v-select
                                    :items="allCategories"
                                    label="Category*"
                                    required
                                    v-model='selectedCategory'
                                    item-text="memorialisation.name"
                                    item-value="memorialisation_id"
                                ></v-select>
                            </v-col>
                        </v-row>
                    </v-container>
                <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="close()"
                    >
                    Close
                    </v-btn>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="send()"
                    >
                    Save
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>

export default ({
    props: ['newSiteProductDialog', 'siteId'],
    data: function() {
        return {
            warningMessage: null,
            name: null,
            price: null,
            description: null,
            shortDescription: null,
            productError: null,
            priceError: null,
            descriptionError: null,
            allCategories: [],
            selectedCategory: null,
            savedProducts: [],
            selectedProduct: null,
            usesCategories: null,
            rules: [
                v => !!v || 'Field cannot be left blank',
                v => (v && v.length <= 255) || 'Input must be less than 255 characters',
            ], 
            valid: true, 
        }
    },
    methods: {
        send() {
            if (!this.$refs.form.validate()) {
                return;
            } else {
                if (this.selectedProduct) {
                    this.createExisitingProduct();
                } else {
                    this.createNewProduct();
                }
            }
        },
        createNewProduct() {
            if(!this.name){
                this.productError = 'Please Provide a Product Name'
                return;
            } else if(!this.price){
                this.priceError = 'Please Provide a Price'
                return;
            }  else if(!this.description){
                this.descriptionError = 'Please Add a Description'
                return;
            } else {
                const newSiteProduct = new FormData();
                newSiteProduct.append('name', this.name);
                newSiteProduct.append('price', this.price);
                newSiteProduct.append('site', this.siteId);
                newSiteProduct.append('POA', this.price == 0);
                newSiteProduct.append('selectedCategory', this.selectedCategory);
                newSiteProduct.append('description', this.description);
                newSiteProduct.append('shortDescription', this.shortDescription);

                axios.post('/api/storeSiteproducts/', newSiteProduct)
                .then(res => {
                    this.$router.push({ name: 'showproduct', params: { product_id:res.data, site: this.siteId } })
                })
                .catch(err => {
                    this.warningMessage = err.response.data.message;
                });
            }
        },
        createExisitingProduct() {
            const existingSiteProduct = new FormData();
            existingSiteProduct.append('product_id', this.selectedProduct);
            existingSiteProduct.append('site_id', this.siteId);
            existingSiteProduct.append('category_id', this.selectedCategory);
            axios.post('/api/copyProduct/', existingSiteProduct)
                .then(res => {
                this.$router.push({ name: 'showproduct', params: { product_id:res.data.id, site: this.siteId } })
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
            });
        },
        fetchAllCategories() {
            axios.get(`/api/sitememorialisations/${this.$route.params.id}`)
            .then(res => {
                this.allCategories = res.data;
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
            });
        },
        close() {
            this.$emit('closeForm', false)
        },
        fetchSavedProducts() {
            axios.get(`/api/getSavedProducts/${this.$route.params.id}`)
            .then(res => {
                this.savedProducts = res.data;
            }).catch(err => {
                this.warningMessage = err.response.data.message;
            });
        },
        fetchStyleName() {
            axios.get(`/api/sites/${this.$route.params.id}`)
                .then(res => {
                    this.usesCategories = res.data.site.uses_categories;
                })
                .catch(err => {
                    this.warningMessage = err.response.data.message;
                });
        }, 
    },
    mounted() {
        this.fetchAllCategories();
        this.fetchStyleName();
    },
    watch: {
        newSiteProductDialog: function(){
            this.$nextTick(() => {
                this.fetchSavedProducts();
            });
        }
    }
})
</script>
