<template>
    <div>
        <div class="secondary-background-colour">
            <div class="container">
                <TopMenuComponent
                    v-on:launchCallbackDialogue="launchCallbackDialogue($event)"
                    v-bind:items="items"
                />

                <div class="row py-4 my-lg-5 text-lg-center">
                    <div class="col-12 pt-0 pl-2">
                        <h1 class="h1-thankyou">Thank you for your order</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white">
            <div class="container text-font-soleil">
                <div class="row mt-3">
                    <div class="col-12 col-md-6">
                        <p class="h7">Next steps</p>
                        <p class="text-size-tiny">We will shortly be in touch to discuss your order in more detail and confirm your final design.</p>
                        <hr class="my-10">
                        <p class="h7">Order details</p>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        <p>Order number:</p>
                                        <p v-for="detail in productDetails">{{ detail.type_name | stringSentenceCase }}:</p>
                                        <p>Total price: </p>
                                    </div>
                                    <div class="col">
                                        <p>{{ orderNumber }}</p>
                                        <p v-for="detail in productDetails">{{ detail.name }}</p>
                                        <p>£{{ totalPrice }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xl-8">
                                <v-img :src="productImage"></v-img>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 pt-5">
                        <p class="h7">Your details</p>
                        <div class="row">
                            <div class="col-4 col-md-5">
                                <p>Your name:</p>
                                <p>Email address:</p>
                                <p>Contact no:</p>
                            </div>
                            <div class="col-8 col-md-7">
                                <p>{{ customerName }}</p>
                                <p>{{ customerEmail }}</p>
                                <p>{{ customerPhoneNumber }}</p>
                            </div>
                        </div>
                        <p>If your contact details are incorrect, please get in touch with us using the information below.</p>
                        <hr class="my-10">
                        <p class="h7">Venue details</p>
                        <div class="row">
                            <div class="col-4">
                                <p>Venue name:</p>
                                <p>Email address:</p>
                                <p>Contact no:</p>
                            </div>
                            <div class="col-8" v-if="product">
                                <p>{{ product.site.name }}</p>
                                <p>{{ product.site.primary_contact_email }}</p>
                                <p>{{ product.site.phone }}</p>
                            </div>
                        </div>
                        <hr class="my-10">
                        <v-btn
                            rounded
                            block
                            :style="{color: styles.primary_colour}"
                            :color="styles.primary_colour"
                            class="btn"
                            @click="generateReport()">
                            Download</v-btn>
                    </div>
                </div>
            </div>
        </div>
        <CallbackModalComponent v-on:closeForm="closeForm($event)" v-bind:callbackDialogue="callbackDialogue" />
        <ProductPdfComponent 
            v-bind:product="product"
            v-bind:productTextFields="customerText"
            v-bind:totalPrice="totalPrice"
            v-bind:editedImage="productImage"
            ref="productPdfComponent" />
    </div>
</template>
<script>
import CallbackModalComponent from './modals/CallbackModalComponent'
import TopMenuComponent from './includes/TopMenuComponent'
import moment from 'moment'
import VueHtml2pdf from 'vue-html2pdf'
import { bus } from '../../../app'
import ProductPdfComponent from './includes/ProductPdfComponent.vue'
export default {
    components: {
    CallbackModalComponent,
    TopMenuComponent,
    VueHtml2pdf,
    ProductPdfComponent,
},
    data() {
        return {
            callbackDialogue: false, 
            orderNumber: null,
            deceasedDetails: null,
            items: [
                {
                    text: 'Home',
                    link: true,
                    exact: true,
                    disabled: false,
                    to: { name: 'memorials' },
                },
                {
                    text: 'Categories',
                    link: true,
                    exact: true,
                    disabled: false,
                    to: { name: 'categories' },
                },
                {
                    text: 'Product',
                    link: true,
                    exact: true,
                    disabled: true,
                    to: { name: 'product' },
                },
                {
                    text: 'Confirmation',
                    link: true,
                    exact: true,
                    disabled: true,
                    to: { name: '' },
                },
            ],
            lines: '',
            productImage: '',
            product: '',
            customTextColour: '',
            productDetails: '',
            customerText: '',
            site_id: '',
            totalPrice: '',
            material: null,
            customerEmail: null,
            customerPhoneNumber: null,
            customerName: null
        }
    },
    methods: {
        generateReport() {
          const chosenProductSpecs = new Map();
          for(const key in this.productDetails)
            chosenProductSpecs.set(key, this.productDetails[key]);
           this.$refs.productPdfComponent.generatePdf(chosenProductSpecs);
        },
        getOrderDetails() {
            this.lines = JSON.parse(localStorage.getItem('lines'));
            this.productImage = localStorage.getItem('editedImage');
            this.product = JSON.parse(localStorage.getItem('product'));
            this.productDetails = JSON.parse(localStorage.getItem('chosenProductSpecs'));
            this.customerText = JSON.parse(localStorage.getItem('customText'));
            this.site_id = this.product.site.id;
            this.totalPrice = localStorage.getItem('totalPrice');
        },
        sendOrder() {
            const cartItems = new FormData();
            cartItems.append('customerEmail', localStorage.getItem('customerEmail'));
            cartItems.append('name', localStorage.getItem('customerName'));
            cartItems.append('contactNumber', localStorage.getItem('customerPhoneNumber'));
            cartItems.append('lines', localStorage.getItem('lines'));
            cartItems.append('productImage', localStorage.getItem('editedImage'));
            cartItems.append('product', localStorage.getItem('product'));
            cartItems.append('customTextColour', localStorage.getItem('textColour'));
            cartItems.append('productDetails', localStorage.getItem('chosenProductSpecs'));
            cartItems.append('customerText', localStorage.getItem('customText'));
            if (JSON.parse(localStorage.getItem('deceased'))) {
                cartItems.append('id', JSON.parse(localStorage.getItem('deceased')).id);
            }
            cartItems.append('order_date', moment().format("YYYY-MM-DD"));
            cartItems.append('site_id', this.product.site.id);
            cartItems.append('total_price', this.totalPrice);
            axios.post('/api/saveorder', cartItems).then(res => {
                this.orderNumber = res.data.order_number;
                localStorage.clear();
            }
            ).catch(res => {
                if (res.response.data.error) {
                    console.error(res.response.data.error);
                    this.orderNumber = res.response.data.order_number;
                    localStorage.clear();
                }
            });
        },
        launchCallbackDialogue(state) {
            this.callbackDialogue = state;
        }
    },
    mounted() {
        this.getOrderDetails();
        this.$nextTick(() => {
            this.sendOrder();
            this.customerEmail = localStorage.getItem('customerEmail');
            this.customerName = localStorage.getItem('customerName');
            this.customerPhoneNumber = localStorage.getItem('customerPhoneNumber');
        });
        bus.$on('launchCallbackDialogue', (data) => {
            this.callbackDialogue = data
        });
        
    },
    filters: {
        stringSentenceCase: function(string) {
            return string.replace(/\.\s+([a-z])[^\.]|^(\s*[a-z])[^\.]/g, s => s.replace(/([a-z])/,s => s.toUpperCase()))
        }
    },
    computed: {
        memorialisations() {
            return this.$store.state.memorialisations;
        },
        deceased() {
            return this.$store.state.deceased;
        },
        styles() {
            return this.$store.state.styles;
        }
    },
}
</script>
<style scoped>
.h1-thankyou {
  text-align:left;
  color: #333538;
}
</style>