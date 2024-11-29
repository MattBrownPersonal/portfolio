<template>
    <v-dialog
      v-model="newProductDialog"
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
            <v-form id="newUser">
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col
                            cols="12"
                            sm="6"
                            >
                            <v-text-field
                                label="Product name*"
                                required
                                :rules="[() => !!product.name || 'This field is required']"
                                :error-messages="errorMessages"
                                v-model='product.name'
                            ></v-text-field>
                            </v-col>
                            <v-col
                            cols="12"
                            sm="6"
                            >
                            <v-text-field
                                label="Product Price*"
                                prepend-inner-icon="mdi-currency-gbp"
                                required
                                :rules="[() => !!product.price || 'This field is required']"
                                :error-messages="errorMessages"
                                v-model='product.price'
                            ></v-text-field>
                            </v-col> 
                            <v-col
                            cols="12"
                            sm="6"
                            >
                            <v-autocomplete
                                :items="allSuppliers"
                                label="Supplier*"
                                required
                                :rules="[() => !!product.supplier_id || 'This field is required']"
                                :error-messages="errorMessages"
                                v-model='product.supplier_id'
                                item-text="name"
                                item-value="id"
                            ></v-autocomplete>
                            </v-col>
                            <v-col
                            cols="12"
                            sm="6"
                            >
                            <v-autocomplete
                                :items="allSites"
                                label="Site*"
                                required
                                :rules="[() => !!product.site_id || 'This field is required']"
                                :error-messages="errorMessages"
                                v-model='product.site_id'
                                item-text="name"
                                item-value="id"
                            ></v-autocomplete>
                            </v-col>
                            <v-col
                            cols="12"
                            >
                            <v-textarea
                                label="Description*"
                                required
                                :rules="[() => !!product.description || 'This field is required']"
                                :error-messages="errorMessages"
                                v-model='product.description'
                            ></v-textarea>
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
                    @click="closeForm()"
                    >
                    Close
                    </v-btn>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="sendProductDetails()"
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
    props: ['newProductDialog', 'selectedCategory'],
    data: function() {
        return {
            warningMessage: null,
            allSuppliers: [],
            allCategories: [],
            allSites: [],
            allAttributeTypes: [],
            product: [],
            selectedAttributeTypes: [],
            errorMessages: '',
        }
    },
    methods: {
        sendProductDetails(){
            const newProduct = new FormData();
            newProduct.append('name', this.product.name);
            newProduct.append('price', this.product.price);
            newProduct.append('supplier', this.product.supplier_id);
            newProduct.append('site_id', this.product.site_id);
            newProduct.append('category', this.selectedCategory.id);
            newProduct.append('description', this.product.description);
            newProduct.append('selectedAttributeTypes', this.selectedAttributeTypes);

            axios.post('/api/products/', newProduct)
            .then(res => {
                this.closeForm();
                this.$emit('fetchCategory');
                this.$emit('triggerSnackBar', res.data.message); 
            })
            .catch(err => {
                this.warningMessage = false;
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
            axios.get('/api/categories')
            .then(res => {
                this.allCategories = res.data;
            })
            .catch(err => {
                this.errorMessages = err.response.data.message;
            });
        },
        fetchAttributeTypes() {
            axios.get('/api/attributeTypes')
            .then( res => {
                this.allAttributeTypes = res.data
            });
        },
        closeForm() {
            this.$emit('closeForm', false)
        },
    },
    mounted() {
        this.fetchAllSuppliers();
        this.fetchAllCategories();
        this.fetchAttributeTypes();
        this.fetchAllSites();
    },
})
</script>
