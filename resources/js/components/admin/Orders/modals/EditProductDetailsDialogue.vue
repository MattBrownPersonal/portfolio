<template>
    <v-dialog v-model="editProductDetailsDialogue" max-width="800px" persistent>
        <v-card>
            <v-card-title>
                <span class="text-h5">Products</span>
            </v-card-title>           
            <v-form ref="form">
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="6">
                                <v-select
                                :items="allCategories"
                                label="Select Category"
                                item-text="memorialisation.name"
                                item-value="memorialisation.id"
                                @input="fetchProducts($event)"
                                ></v-select>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-select
                                :items="allProducts"
                                label="Select Product"                                
                                item-text="name"
                                item-value="id"
                                @input="setProduct($event)"
                                ></v-select>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeForm();">
                        Close
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="submit();">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>

export default ({
    props: ['editProductDetailsDialogue', 'order'],
    data: function () {
        return {
            allCategories: [],
            allProducts: [],      
            selectedProduct: []
        }
    },
    methods: {
        submit() {
            const product = new FormData();
            product.append('product', JSON.stringify(this.selectedProduct));
            product.append('type', 'editProduct');

            axios.post(`/api/updateOrder/${this.order.id}`, product)
                .then(res => {
                    this.closeForm();
                    this.$emit('fetchOrder');
                    this.$refs.form.reset();
                })
                .catch(err => {
                    this.warningMessage = err;
                });
        },
        fetchCategories() {
            axios.get(`/api/getSiteCategoriesForOrder/${this.order.site.id}`)
                .then(res => {
                    this.allCategories = res.data;
                })
        },
        fetchProducts($event) {
            axios.get(`/api/allProductsForOrder/${$event}/${this.order.site.id}`)
                .then((res) => {
                    this.allProducts = res.data;
                });
        },
        setProduct($event) {
            this.selectedProduct = this.allProducts.find( x => x.id === $event )
        },
        closeForm() {
            this.$emit('closeForm', false)
        },
    },
    watch: {
        editProductDetailsDialogue: function (propVal) {
            this.fetchCategories();
        }
    }
})
</script>
