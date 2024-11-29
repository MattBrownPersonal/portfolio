<template>
    <div class="px-4">
        <v-row>
            <v-col class="col-3 col-lg-2">
                <v-card elevation="2">
                    <v-card-text>
                        <p class="text-h6">Actions</p>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title class="pb-4">
                                    <span class="actionLink" @click="product ? editProductDialog = true : {}">
                                        <v-icon left>mdi-pencil</v-icon>
                                        Edit
                                    </span>
                                </v-list-item-title>
                                <v-list-item-title class="pb-4">
                                    <span class="actionLink" @click="product ? deleteProductDialog = true : {}">
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
                <v-btn v-if="product.draft == 1" block class="mt-5" color="orange" @click="saveProductAsDraft(0)">Product Hidden</v-btn>
                <v-btn v-else block class="mt-5" color="primary" @click="saveProductAsDraft(1)">Hide Product</v-btn>

                <v-btn v-if="product.saved == 1" block class="mt-5" color="orange">Saved as Template</v-btn>
                <v-btn v-else block class="mt-5" color="primary" @click="saveProduct()">Save as Template</v-btn>
                <div v-if="site">
                    <UsefulLinksComponent v-bind:site="site" />
                </div>

            </v-col>
            <v-col class="col-9 col-lg-10">
                <LoadingComponent v-if="!site || !product" />
                <v-row v-if="site">
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Product Details</p>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col">
                                        <p class="font-weight-bold">Product Name</p>
                                        {{ product.name }}
                                    </v-col>
                                    <v-col class="col">
                                        <p class="font-weight-bold">Site Name</p>
                                        <span v-if="product.site">{{ product.site.name }}</span>
                                    </v-col>
                                    <v-col class="col">
                                        <p class="font-weight-bold">Supplier Name</p>
                                        <span v-if="product.supplier">
                                        {{ product.supplier.name }}</span>
                                    </v-col>
                                    <v-col class="col">
                                        <p class="font-weight-bold">Price</p>                                        
                                        £{{ parseFloat(product.price).toFixed(2) }}
                                    </v-col>
                                    <v-col class="col">
                                        <p class="font-weight-bold">POA</p>                                        
                                        {{ product.POA == 1 ? 'Yes' : 'No' }}
                                    </v-col>
                                    <v-col class="col" v-if="site.uses_categories === 1">
                                        <p class="font-weight-bold">Category</p>
                                        <span v-if="product.memorialisation">
                                            {{ product.memorialisation.name }}
                                        </span>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-if="product">
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Short Description</p>
                            </v-card-title>
                            <v-card-text>
                                <span v-html="product.short_description"></span>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-if="product">
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Description</p>
                            </v-card-title>
                            <v-card-text>
                                <span v-html="product.description"></span>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-if="product">
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Thumbnail Image</p>
                            </v-card-title>
                            <v-card-text>
                                <v-img :src="product.thumbnail_image" width="200"></v-img>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-if="product">
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p @click="editAttributeTypesDialog = true">
                                    Attribute Details (Click Here to Add/Remove Attribute Type)
                                </p>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col v-for="attributeType in product.attribute_types" :key="attributeType.id"
                                        v-if="attributeType.visible == 1">
                                        <p class="font-weight-bold">
                                            {{ attributeType.name }}
                                        </p>
                                        <div v-for="attributeProductSite in attributeType.attributes"
                                            :key="attributeProductSite.id" v-if="attributeProductSite.visible == 1">
                                            {{ attributeProductSite.name }} -
                                            £{{ attributeProductSite.price }}
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-if="product">
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Material Types</p>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col text-center" v-for="material in product.materials"
                                        :key="material.id">
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on, attrs }">
                                                <img :src="material.imageurl" v-bind="attrs" v-on="on"
                                                    class="single-product-material" alt="" />
                                            </template>
                                            <span>{{ material.type }}</span>
                                        </v-tooltip>
                                        <br />
                                            <div class="font-weight-bold my-3">Price: £{{ material.price }}</div>
                                        <br />
                                        <v-btn @click="toggleMaterialVisibility(material.id, 0)"
                                            v-if="material.visible == 1" color="error" small>Hide Material
                                        </v-btn>
                                        <v-btn @click="toggleMaterialVisibility(material.id, 1)" v-else
                                            color="primary" small>Show Material</v-btn>
                                        <v-btn @click="editMaterial(material)" color="primary" small>Edit Details
                                            </v-btn>
                                    </v-col>
                                    <v-col class="col">
                                        <div class="addMaterial" @click="AddNewProductMaterialDialogue = true">
                                            <h1>+</h1>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-if="product">
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Predefined Images</p>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col text-center" v-for="predefinedImage in product.predefined_images"
                                        :key="predefinedImage.id">
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on, attrs }">
                                                <img :src="predefinedImage.imageurl" v-bind="attrs" v-on="on"
                                                    class="single-product-material" alt="" />
                                            </template>
                                            <span>{{ predefinedImage.name }}</span>
                                        </v-tooltip>
                                        <br />
                                        <div class="font-weight-bold my-3">Price: £{{ predefinedImage.price }}</div>
                                        <v-btn @click="togglePredefinedImageVisibility(predefinedImage.id, 0)" v-if="predefinedImage.visible == 1" color="error" small>Hide Image
                                        </v-btn>
                                        <v-btn @click="togglePredefinedImageVisibility(predefinedImage.id, 1)" v-else color="primary" small>Show Image</v-btn>
                                        <v-btn @click="editPredefinedImage(predefinedImage)" color="primary" small>Edit Details
                                        </v-btn>
                                    </v-col>
                                    <v-col class="col">
                                        <div class="addMaterial" @click="AddNewPredefinedImageDialogue = true">
                                            <h1>+</h1>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-if="product">
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <div class="row">
                                    <div class="col-4">
                                        <p>Product Images</p>
                                    </div>
                                </div>
                            </v-card-title>
                            <v-card-text>
                                <v-row class="mt-4">
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-3 font-weight-bold">Line Type</div>
                                            <div class="col-3 font-weight-bold">Default Message</div>
                                        </div>
                                        <div class="row" v-for="button in orderedButtons" :key="button.id" v-if="button.removed === 0">
                                            <div class="col-3">{{ button.line_types }}</div>
                                            <div class="col-3">{{ button.custom_message_text }}</div>
                                        </div>
                                        <div class="col-6 offset-3">
                                            <v-btn @click="AddNewLineDialog = true" class="my-5" color="primary">Click
                                                to add/edit lines</v-btn>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-12 font-weight-bold pb-0">
                                                Select Date Format
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 py-0">
                                                <v-radio-group  v-model="selectedDateFormat" mandatory>
                                                    <v-radio v-for="format in dateFormats"
                                                        :key="format.id" 
                                                        :label="format.format" 
                                                        :value="format"
                                                        @click="setDateFormat()"
                                                        >
                                                    </v-radio>
                                                </v-radio-group>
                                            </div>
                                        </div>
                                    </div>
                                </v-row>
                                <v-row>
                                    <v-col class="col-4" v-for="image in allImages" :key="image.key">                                        
                                        <v-img v-if="image.custom_image" :src="image.custom_image.imageurl" max-width="400" color="warning">
                                        </v-img>
                                        <span class="text-center">
                                            <h4 v-if="image.material">{{ image.material.type }}</h4>
                                            <v-alert   
                                                v-if="image.hasUnusedLines"                                             
                                                type="error" 
                                                dense                                              
                                            >Please update lines</v-alert>
                                            <v-btn @click="deleteImage(image.id)" class="my-3" color="error">
                                                Remove Image</v-btn>
                                            <v-btn @click="editImage(image.id)" class="my-3" color="primary">Edit Image
                                            </v-btn>
                                        </span>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="1" class="offset-11">
                                        <v-fab-transition>
                                            <v-btn color="primary" fab large dark class="v-btn--example"
                                                @click="addExampleImageDialog = true">
                                                <v-icon>add</v-icon>
                                            </v-btn>
                                        </v-fab-transition>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-if="product">
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Custom Text Colours</p>
                            </v-card-title>
                            <v-card-text>
                                <div class="row" align="center">
                                    <div class="col-3" v-for="colour in productColours" :key="colour.id">
                                        <div class="colourSwatches" :style="{ backgroundColor: colour.colour }"></div>
                                        <h5>{{ colour.name }}</h5>
                                        <v-btn small color="primary" @click="editSelectedTextColour(colour)">Edit</v-btn>
                                        <v-btn small class="error ml-3" @click="deleteSelectedTextColour(colour.id)">Delete</v-btn>
                                    </div>
                                </div>
                                <v-row>
                                    <v-col cols="1" class="offset-11">
                                        <v-fab-transition>
                                            <v-btn color="primary" fab large dark class="v-btn--example"
                                                @click="AddCustomColourDialog = true">
                                                <v-icon>add</v-icon>
                                            </v-btn>
                                        </v-fab-transition>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-if="product">
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Available Fonts</p>
                            </v-card-title>
                            <v-card-text>
                                <div class="row">
                                    <div class="col-3" v-for="font in product.fonts" :key="font.id">
                                        <h5>{{ font.name }}</h5>
                                        <v-btn @click="removeFont(font.id)" class="my-3" color="error" small>Remove Font</v-btn>
                                    </div>
                                </div>
                                <v-row>
                                    <v-col cols="1" class="offset-11">
                                        <v-fab-transition>
                                            <v-btn color="primary" fab large dark class="v-btn--example"
                                                @click="addFont()">
                                                <v-icon>add</v-icon>
                                            </v-btn>
                                        </v-fab-transition>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
        <v-snackbar v-model="snackbar" :timeout="timeout" color="green">
            {{ snackbarText }}
            <template v-slot:action="{ attrs }">
                <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        <EditProductComponent 
            v-bind:editProductDialog="editProductDialog" 
            v-on:closeForm="closeForm($event)"
            v-bind:selectedProduct="product" 
            v-on:fetchSingleProduct="fetchSingleProduct()"
            v-on:triggerSnackBar="triggerSnackBar($event)" />
        <DeleteProductComponent 
            v-bind:deleteProductDialog="deleteProductDialog" 
            v-on:closeForm="closeForm($event)"
            v-bind:selectedProduct="product" 
            v-on:fetchSingleProduct="fetchSingleProduct()"
            v-on:triggerSnackBar="triggerSnackBar($event)" />
        <DeleteProductImageComponent 
            v-bind:deleteProductImageDialog="deleteProductImageDialog"
            v-on:closeForm="closeForm($event)" 
            v-bind:deleteImageId="deleteImageId"
            v-on:fetchSingleProduct="fetchSingleProduct()" 
            v-on:triggerSnackBar="triggerSnackBar($event)"
            v-on:refreshPage="fetchSingleProduct()"/>
        <ExampleImageComponent 
            v-bind:addExampleImageDialog="addExampleImageDialog"
            v-bind:selectedProduct="product" 
            v-bind:customImage="customImage"
            v-bind:materialTypes="product.materials" 
            v-bind:modalType = "modalType"
            v-bind:editableCustomImage="editableCustomImage"
            v-on:closeForm="closeForm($event)" 
            v-on:fetchSingleProduct="fetchSingleProduct()"
            v-on:triggerSnackBar="triggerSnackBar($event)" />
        <DeleteCustomColourComponent 
            v-bind:deleteCustomColourDialog="deleteCustomColourDialog"
            v-on:closeForm="closeForm($event)" 
            v-bind:selectedColourId="selectedColourId"
            v-on:fetchSingleProduct="fetchSingleProduct()" 
            v-on:triggerSnackBar="triggerSnackBar($event)" 
            v-on:refreshPage="fetchSingleProduct()"/>
        <EditCustomColourComponent 
            v-bind:editCustomColourDialog="editCustomColourDialog"
            v-bind:product="product" 
            v-bind:selectedColour="selectedColour" 
            v-on:closeForm="closeForm($event)" 
            v-on:triggerSnackBar="triggerSnackBar($event)"
            v-on:fetchSingleProduct="fetchSingleProduct()" />
        <AddCustomColourComponent 
            v-bind:AddCustomColourDialog="AddCustomColourDialog"
            v-on:closeForm="closeForm($event)" 
            v-bind:product="product" 
            v-on:triggerSnackBar="triggerSnackBar($event)"
            v-on:fetchSingleProduct="fetchSingleProduct()" />
        <AddNewFontComponent 
            v-bind:AddFontDialog="AddFontDialog"
            v-on:fontsChanged="fontsChanged"
            v-on:closeForm="closeForm($event)" 
            v-bind:product="product" 
            v-on:triggerSnackBar="triggerSnackBar($event)"
            v-on:fetchSingleProduct="fetchSingleProduct()" />
        <AddNewLineComponent 
            v-bind:AddNewLineDialog="AddNewLineDialog" 
            v-bind:product="product" 
            v-bind:lines="allButtons"
            v-bind:productLines="orderedLines"
            v-on:closeForm="closeForm($event)"
            v-on:triggerSnackBar="triggerSnackBar($event)"
            v-on:linesSaved="fetchSingleProduct()" />
        <AddNewProductMaterial 
            v-bind:AddNewProductMaterialDialogue="AddNewProductMaterialDialogue"
            v-bind:product="product" 
            v-on:closeForm="closeForm($event)" 
            v-on:triggerSnackBar="triggerSnackBar($event)"
            v-on:fetchSingleProduct="fetchSingleProduct()" 
            v-bind:site="site" />
        <AddNewAttributeType 
            v-bind:editAttributeTypesDialog="editAttributeTypesDialog"
            v-bind:attribute_types="product.attribute_types" 
            v-on:closeForm="closeForm($event)"
            v-on:triggerSnackBar="triggerSnackBar($event)" 
            v-on:fetchSingleProduct="fetchSingleProduct()" />
        <AddNewPredfinedImage 
            v-bind:AddNewPredefinedImageDialogue="AddNewPredefinedImageDialogue"
            v-bind:product="product" 
            v-on:closeForm="closeForm($event)" 
            v-on:triggerSnackBar="triggerSnackBar($event)"
            v-on:fetchSingleProduct="fetchSingleProduct()" />
        <EditPredfinedImageComponent 
            v-bind:EditPredefinedImageDialogue="EditPredefinedImageDialogue"
            v-bind:predefindedImage="selectedPredefinedImage" 
            v-on:closeForm="closeForm($event)" 
            v-on:triggerSnackBar="triggerSnackBar($event)"
            v-on:fetchSingleProduct="fetchSingleProduct()" />
        <EditProductMaterial 
            v-bind:EditProductMaterialDialogue="EditProductMaterialDialogue"
            v-bind:material="editingMaterial" 
            v-on:closeForm="closeForm($event)" 
            v-on:triggerSnackBar="triggerSnackBar($event)"
            v-on:fetchSingleProduct="fetchSingleProduct()" 
        />
    </div>
</template>
<script>
import EditProductComponent from './modals/EditProductComponent'
import DeleteProductComponent from './modals/DeleteProductComponent'
import DeleteProductImageComponent from './modals/DeleteProductImageComponent'
import ExampleImageComponent from './modals/ExampleImageComponent'
import AddCustomColourComponent from './modals/AddCustomColoursComponent'
import EditCustomColourComponent from './modals/EditCustomColoursComponent'
import AddNewLineComponent from './modals/AddNewLineComponent'
import AddNewProductMaterial from './modals/AddNewProductMaterial'
import UsefulLinksComponent from '../UsefulLinksComponent.vue'
import AddNewAttributeType from './modals/NewAttributeTypeComponent.vue'
import AddNewPredfinedImage from './modals/AddNewPredfinedImageComponent.vue'
import DeleteCustomColourComponent from './modals/DeleteCustomColourComponent.vue'
import EditPredfinedImageComponent from './modals/EditPredfinedImageComponent.vue'
import AddNewFontComponent from './modals/AddNewFontComponent.vue'
import EditProductMaterial from './modals/EditProductMaterialComponent.vue';
import LoadingComponent from '../LoadingComponent'
import axios from 'axios'

export default {
    components: {
        EditProductComponent,
        DeleteProductComponent,
        ExampleImageComponent,
        AddCustomColourComponent,
        AddNewLineComponent,
        DeleteProductImageComponent,
        AddNewProductMaterial,
        UsefulLinksComponent,
        AddNewAttributeType,
        AddNewPredfinedImage,
        EditCustomColourComponent,
        DeleteCustomColourComponent,
        EditPredfinedImageComponent,
        AddNewFontComponent,
        EditProductMaterial,
        LoadingComponent
    },
    data() {
        return {
            product: false,
            productColours: [],
            image: null,
            attributeTypes: null,
            allImages: null,
            snackbar: false,
            snackbarText: '',
            timeout: 4000,
            editProductDialog: false,
            deleteProductDialog: false,
            showUploadImageButton: false,
            addExampleImageDialog: false,
            customImage: null,
            AddCustomColourDialog: false,
            AddNewLineDialog: false,
            editableCustomImage: null,
            buttons: null,
            allButtons: null,
            deleteImageId: null,
            deleteProductImageDialog: false,
            materialTypes: [],
            allMaterials: [],
            AddNewProductMaterialDialogue: false,
            EditProductMaterialDialogue: false,
            EditProductAttributesDialog: false,
            selectedattributeType: null,
            editAttributeTypesDialog: false,
            AddNewPredefinedImageDialogue: false,
            dateFormats: [
                { 'format': 'January 01 1970', 'momentLayout': 'MMMM D YYYY' },
                { 'format': 'January 1st 1970', 'momentLayout': 'MMMM Do YYYY' },
                { 'format': 'January 01 70', 'momentLayout': 'MMMM D YY' },
                { 'format': 'January 1st 70', 'momentLayout': 'MMMM Do YY' },
                { 'format': 'Jan 01 1970', 'momentLayout': 'MMM D YYYY' },
                { 'format': 'Jan 1st 1970', 'momentLayout': 'MMM Do YYYY' },
                { 'format': 'Jan 01 70', 'momentLayout': 'MMM D YY' },
                { 'format': 'Jan 1st 70', 'momentLayout': 'MMM Do YY' },
            ],
            selectedDateFormat: '',
            radioGroup: 1,
            site: null,
            editCustomColourDialog: false,
            selectedColour: null,
            deleteCustomColourDialog: false,
            selectedColourId: null,
            editExampleImageDialogue: false,
            modalType: 'add',
            selectedPredefinedImage: null,
            EditPredefinedImageDialogue: false,
            availableFonts: [],
            selectedFonts: [],
            AddFontDialog: false,
            editingMaterial: null,
            orderedLines: []
        }
    },
    methods: {
        setDateFormat() {
            const dateFormat = new FormData();
            dateFormat.append('format', this.selectedDateFormat.momentLayout);
            dateFormat.append('id', this.product.id);
            axios.post(`/api/setDateFormat/${this.$route.params.product_id}/`, dateFormat)
                .then(res => {
                    this.fetchSingleProduct();
                }).catch(error => {
                    this.snackbar = true;
                    this.snackbarText = 'error saving date format';
                })
        },
        fetchSingleProduct() {
            axios.get(`/api/singleSiteproduct/${this.$route.params.product_id}/${this.$route.params.id}`)
                .then(res => {
                    this.product = res.data;
                    this.allImages = res.data.custom_images;
                    this.editableCustomImage = null
                    this.modalType = 'add'
                    this.orderedLines = res.data.custom_product_texts.sort((a, b) => a.order_index - b.order_index)
                    this.getProductColours();
                    this.fetchButtons();
                    this.checkUnusedLines();
                })
        },
        fetchButtons() {
            axios.get(`/api/linetypes/${this.product.id}`)
                .then(res => {
                    this.allButtons = res.data
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
            this.AddCustomColourDialog = state;
            this.AddNewLineDialog = state;
            this.deleteProductImageDialog = state;
            this.AddNewProductMaterialDialogue = state;
            this.EditProductAttributesDialog = state;
            this.editAttributeTypesDialog = state;
            this.AddNewPredefinedImageDialogue = state;
            this.editCustomColourDialog = state;
            this.deleteCustomColourDialog = state;
            this.editExampleImageDialogue = state;
            this.EditPredefinedImageDialogue = state;
            this.editableCustomImage = null
            this.modalType = 'add'
            this.AddFontDialog = state;
            this.EditProductMaterialDialogue = state;
        },
        fileSelected() {
            this.showUploadImageButton = true;
        },
        getProductColours() {
            axios.get(`/api/getproductcolours/${this.product.id}`)
                .then(res => {
                    this.productColours = res.data
                }).catch(error => {
                    this.snackbar = true;
                    this.snackbarText = error;
                })
        },
        uploadProductImage() {
            const imageData = new FormData();
            imageData.append('file', this.image);
            imageData.append('type', 'imageUpload');
            imageData.append('_method', 'PUT');

            axios.post(`/api/updateSiteproduct/${this.$route.params.site}/${this.$route.params.id}`, imageData, { headers: { "Content-Type": "multipart/form-data" } })
                .then(res => {
                    this.fetchSingleProduct(),
                        this.showUploadImageButton = false;
                    this.image = null;
                }).catch(error => {
                    this.snackbar = true;
                    this.snackbarText = error;
                });
        },
        editImage($id) {
            this.modalType = 'edit'
            axios.get(`/api/editProductImage/${$id}/${this.product.id}`)
                .then(res => {
                    this.editableCustomImage = res.data;
                    this.addExampleImageDialog = true;
                }).catch(error => {
                    this.snackbar = true;
                    this.snackbarText = error;
                })
        },
        deleteImage(id) {
            this.deleteProductImageDialog = true;
            this.deleteImageId = id;
            
        },
        saveProduct() {
            axios
                .post(
                    `/api/saveProduct/${this.$route.params.product_id}/${this.$route.params.id}`
                )
                .then((res) => {
                    this.allButtons = res.data;
                    this.fetchSingleProduct();
                }).catch(error => {
                    this.snackbar = true;
                    this.snackbarText = error;
                });
        },
        saveProductAsDraft(flag) {
            const draft = new FormData();
            draft.append('flag', flag);
            axios
                .post(
                    `/api/saveProductAsDraft/${this.$route.params.product_id}/${this.$route.params.id}`, draft
                )
                .then((res) => {
                    this.allButtons = res.data;
                    this.fetchSingleProduct();
                }).catch(error => {
                    this.snackbar = true;
                    this.snackbarText = error;
                });
        },
        triggerSnackBar($event) {
            this.snackbarText = $event;
            this.snackbar = true;
        },
        togglePredefinedImageVisibility(imageId, visibilityStatus) {
            const changePredefinedImageVisibility = new FormData();
            changePredefinedImageVisibility.append("visibility", visibilityStatus);
            changePredefinedImageVisibility.append("_method", "PUT");
            axios
                .post(`/api/updatePredefinedImageVisibility/${imageId}`, changePredefinedImageVisibility)
                .then((res) => {
                    this.fetchSingleProduct();
                })
                .catch((error) => {
                    this.message = error;
                });
        },
        toggleMaterialVisibility(materialId, visibilityStatus) {
            const changeVisibility = new FormData();
            changeVisibility.append("visibility", visibilityStatus);
            changeVisibility.append("_method", "PUT");
            axios
                .post(`/api/updateMaterialVisibility/${materialId}`, changeVisibility)
                .then((res) => {
                    this.fetchSingleProduct();
                })
                .catch((error) => {
                    this.message = error;
                });
        },
        fetchStyleName() {
            axios.get(`/api/sites/${this.$route.params.id}`)
                .then(res => {
                    this.site = res.data.site;
                })
                .catch((error) => {
                    this.message = error;
                });
        }, 
        editSelectedTextColour(colour) {
            this.selectedColour = colour;
            this.editCustomColourDialog = true
        },
        deleteSelectedTextColour(colourId) {
            this.selectedColourId = colourId;
            this.deleteCustomColourDialog = true
        },
        editPredefinedImage(predefindedImage) {
            this.selectedPredefinedImage = predefindedImage;
            this.EditPredefinedImageDialogue = true
        },
        checkUnusedLines() {
            this.allImages.forEach((element, index) => {
                if (element.custom_text_fields.find(e => e.removed === 1)) {
                    this.allImages[index].hasUnusedLines = true;
                }
            });
        },
        addFont() {
          if(this.availableFonts.length > 0) {
            this.AddFontDialog = true;
          } 
          else 
          {
            this.snackbar = true;
            this.snackbarText = "No more fonts available";
          }
        },
        fontsChanged(availableFonts) {
          this.availableFonts = availableFonts;
        },
        removeFont(id) {
            axios.post(`/api/deleteFont/${this.product.id}/${id}`)
                .then(res => {
                    this.snackbar = true;
                    this.snackbarText = res.data.message;
                    this.fetchSingleProduct();
                })
                .catch((error) => {
                    this.message = error;
                });
        },
        editMaterial(material) {
            this.EditProductMaterialDialogue = true;
            this.editingMaterial = material
        },
    },
    computed: {
        orderedButtons: function() {
            return _.orderBy(this.allButtons, 'order_index');
        }
    },
    filters: {
        truncate: function (text, length) {
            return text.substring(0, length)
        },
    },
    mounted() {
        this.fetchSingleProduct();
        this.fetchStyleName();
    },
};
</script>
<style>
.v-input--selection-controls__input+.v-label {
    margin-bottom: 0px;
}

.colourSwatches {
    border-radius: 90px;
    box-shadow: 2px 2px 2px #888888;
    width: 70px;
    height: 70px;
    padding-top: 21px;
    margin-bottom: 10px;
}

.actionLink {
    cursor: pointer;
}

.single-product-material {
    width: 100px;
    border-radius: 200px;
    height: 100px;
}

.addMaterial {
    width: 100px;
    border-radius: 200px;
    height: 100px;
    background-color: #1c836f;
    padding-top: 30px;
    padding-left: 40px;
    color: white;
    cursor: pointer;
}
</style>
