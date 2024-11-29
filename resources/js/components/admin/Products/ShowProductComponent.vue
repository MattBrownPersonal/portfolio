<template>
    <div class="px-4">
        <v-row>
            <v-col class="col-2">
                <v-card elevation="2">
                    <v-card-text>
                        <p class="text-h6">Actions</p>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title class="pb-4">
                                    <span class="actionLink" @click="editProductDialog = true;">
                                        <v-icon left>mdi-pencil</v-icon>
                                        Edit
                                    </span>
                                </v-list-item-title>
                                <v-list-item-title class="pb-4">
                                    <span class="actionLink" @click="deleteProductDialog = true;">
                                        <v-icon left>mdi-delete</v-icon>
                                        Delete
                                    </span>
                                </v-list-item-title>
                                <v-list-item-title>
                                    <v-icon left>mdi-keyboard-backspace</v-icon>
                                    <span @click="$router.go(-1)" class="actionLink">Back</span>
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col class="col-10">
                <v-row>
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Product Details</p>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col" v-if="product.product">
                                        <p class="font-weight-bold">Product Name</p>
                                        {{ product.product.name }}
                                    </v-col>                                   
                                    <v-col class="col">
                                        <p class="font-weight-bold">Supplier Name</p>
                                        <span v-if="product.supplier">{{ product.supplier.name }}</span>                                    
                                    </v-col>
                                    <v-col class="col">
                                        <p class="font-weight-bold">Price</p>
                                        £{{ product.price }}
                                    </v-col>                                   
                                </v-row>
                            </v-card-text>
                        </v-card>               
                    </v-col>
                </v-row>
                 <v-row>
                    <v-col>
                        <v-card elevation="2">
                            <v-card-text>
                                <v-card-title>
                                    Sites Using This Product
                                <v-spacer></v-spacer>
                                 <v-text-field
                                    v-model="sitesSearch"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                />
                                </v-card-title>
                                    <v-data-table
                                    :headers="sitesHeaders"
                                    :items="sites"
                                    :items-per-page="10"
                                    class="elevation-1"
                                    :loading="sitesTableLoading"
                                    loading-text="Loading... Please wait"
                                    :search="sitesSearch"
                                    >                                                     
                                </v-data-table>
                            </v-card-text>
                        </v-card>               
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
         <v-snackbar
            v-model="snackbar"
            :timeout="timeout"
            color="green"
        >
            {{ snackbarText }}

            <template v-slot:action="{ attrs }">
            <v-btn
                color="white"
                text
                v-bind="attrs"
                @click="snackbar = false"
            >
                Close
            </v-btn>
            </template>
        </v-snackbar>
        <EditProductComponent
            v-bind:editProductDialog="editProductDialog" 
            v-on:closeForm="closeForm($event)"
            v-bind:selectedProduct="product"
            v-on:fetchSingleProduct="fetchSingleProduct()"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />
        <DeleteProductComponent
            v-bind:deleteProductDialog="deleteProductDialog"
            v-on:closeForm="closeForm($event)"
            v-bind:selectedProduct="product"
            v-on:fetchSingleProduct="fetchSingleProduct()"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />
        <AddExampleImageComponent
            v-bind:addExampleImageDialog="addExampleImageDialog"
            v-on:closeForm="closeForm($event)"
            v-bind:selectedProduct="product"
            v-bind:customImage="customImage"
            v-on:fetchSingleProduct="fetchSingleProduct()"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />
    </div>
</template>
<script>
import EditProductComponent from './modals/EditProductComponent'
import DeleteProductComponent from './modals/DeleteProductComponent'
import AddExampleImageComponent from './modals/AddExampleImageComponent'

export default {
    components: {
        EditProductComponent,
        DeleteProductComponent,
        AddExampleImageComponent
    },
    data () {
        return {
            product: [],
            image: null,
            items: null,
            allImages: null,
            snackbar: false,
            snackbarText: '',
            timeout: 4000,          
            editProductDialog: false,
            deleteProductDialog: false,
            showUploadImageButton: false,
            addExampleImageDialog: false,
            customImage: null,
            sites: null,
            sitesHeaders: [
                { text: 'Name', value: 'site.name'},               
            ],
            sites: [],
            sitesSearch: '',
            snackbar: false,
            snackbarText: '',
            timeout: 4000,
            sitesTableLoading: false,
            }
    },
    methods: {
        fetchSingleProduct() {
            axios.get(`/api/products/${this.$route.params.id}`)
            .then(res => {
                this.product = res.data;
                this.allImages = res.data.images;
                this.fetchCustomImage();
                this.fetchSitesByProduct();
            })
        },
        fetchSitesByProduct() {
            axios.get(`/api/productsBySite/${this.$route.params.id}`)
            .then(res => {
                this.sites = res.data;                
            })
        },
        triggerSnackBar($event) {
            this.snackbarText = $event
            this.snackbar = true;
        },
        closeForm(state) {
            this.editProductDialog = state; 
            this.deleteProductDialog = state;       
            this.addExampleImageDialog = state;  
        },
        fileSelected() {
            this.showUploadImageButton = true;
        },
        updateAttributeTypes() {
            let attributeTypes = new FormData();
                for (let i = 0; i < this.product.attribute_types.length; i++) {
                    attributeTypes.append('attributeTypes[]', this.product.attribute_types[i]);
                }
                attributeTypes.append('type', 'attribute_types');
                attributeTypes.append('id', this.product.id);
                attributeTypes.append('_method', 'PUT');
            axios.post(`/api/updateSiteproduct/${this.$route.params.site}/${this.$route.params.id}`, attributeTypes)
            .then( res => {
                this.fetchSingleProduct();
            })
        },
        fetchAllAttributeTypes() {
            axios.get('/api/attributeTypes')
            .then(res => {
                this.items = res.data;
            })
        },
        fetchCustomImage() {
            axios.get(`/api/getCustomImage/${this.product.id}`)
            .then( res => {
                this.customImage = res.data
            });
        },
        uploadProductImage() {
            const imageData = new FormData();
            imageData.append('file', this.image);
            imageData.append('type', 'imageUpload');
            imageData.append('_method', 'PUT');
            
            axios.post(`/api/updateSiteproduct/${this.$route.params.site}/${this.$route.params.id}`, imageData,{headers: {"Content-Type": "multipart/form-data"}})
            .then( res => {
                this.fetchSingleProduct(),
                this.showUploadImageButton = false;
                this.image = null;
            });
        },
        deleteImage($id) {
            axios.delete(`/api/deleteProductImage/${$id}`)
            .then(res => {
                this.fetchSingleProduct()           
            })
        }
    },
    mounted () {
        this.fetchSingleProduct();
        this.fetchAllAttributeTypes();
    }
}
</script>
<style scoped>
.crudMenu{
    list-style-type:none;
}
.actionLink {
    cursor: pointer;
}
</style>