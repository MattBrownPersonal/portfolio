<template>
    <div class="col-12 col-md-4 offset-0 offset-md-1 px-0 px-md-3 text-size-tiny">
        <p>If you have any special requests, you can contact the venue on <a :href="`tel:${phonenumber}`" class="phone-link">{{ phonenumber }}</a><br />
        Please note that exact placement of your memorialisation can be confirmed with the venue after purchase.</p>
        <hr class="my-10">
        <v-form 
            ref="form"
            v-model="valid"
            id="payment-form"
        >
            <h6 class="classic mt-5">Your details</h6>
            <v-row>
                <v-col
                cols="12"
                class="pb-0"
                >
                <v-text-field
                label="Your name"
                aria-label="name"
                outlined
                rounded
                v-model="customerName"
                :rules="[rules.required]"
                @keyup="setCustomerDetails()"
                ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    class="py-0"
                    >
                    <v-text-field
                    label="Your email"
                    aria-label="email"
                    outlined
                    rounded
                    v-model="customerEmail"
                    :rules="[rules.required, rules.email]"
                    @keyup="setCustomerDetails()"
                    ></v-text-field>
                </v-col>
                <v-col
                    cols="12"
                    class="py-0"
                    >
                    <v-text-field
                    label="Your contact number"
                    aria-label="phone"
                    outlined
                    rounded
                    type="number"
                    v-model="customerPhoneNumber"
                    :rules="[rules.required, () => (this.customerPhoneNumber.length == 11) || 'Invalid phone number']"
                    @keyup="setCustomerDetails()"
                    ></v-text-field>
                </v-col>
            </v-row>
            <h6 class="mt-5 classic">Payment details</h6>
            <div ref="card" id="cardPayment"></div>   
            <div class="row mt-4">
                <div class="col-6">                  
                    <v-btn      
                    block
                    class="plainButton cancel-button btn-outline"
                    @click="back()"
                    outlined
                    >
                    Cancel</v-btn>
                </div>
                <div class="col-6">
                    <button 
                        :disabled="!valid"
                        class="btn btn-block" 
                        :style="{color: styles.secondary_colour, backgroundColor: styles.primary_colour}"
                        id="submit"
                    >
                        <div class="spinner hidden" id="spinner"></div>
                        Pay now
                    </button>
                </div>
            </div>
            <div id="payment-message" class="error_text hidden"></div>
        </v-form>
    </div>
</template>
<script>
import axios from 'axios';
import { getCodeFromParams } from '../../../../deceased';
export default {
    props: ['product','chosenProductSpecs', 'customImageDetails', 'totalPrice'],
    data () {
    return {
            prices: [],
            message: null,
            selectedProduct: null,
            lines: null,
            productDetails: null,
            newImage: null,
            siteProduct: [],
            tokenId: null,
            customerName: '',
            customerEmail: '',
            customerPhoneNumber: '',
            url: null,
            phonenumber: null,
            rules: {
                required: value => !!value || 'Required.',
                email: value => {
                    const pattern = /.+@.+\..+/
                    return pattern.test(value) || 'Invalid e-mail.'
                },
            },  
            stripeKey: null,
            snackbar: false,
            snackbarText: '',
            timeout: 6000,
            valid: false
        }
    },
    methods: {
        setCustomerDetails() {
            localStorage.setItem('customerName', this.customerName);
            localStorage.setItem('customerEmail', this.customerEmail);
            localStorage.setItem('customerPhoneNumber', this.customerPhoneNumber);
        },
        getContactDetails() {
            axios.get(`/api/contactdetails/${getCodeFromParams(this.$route.params)}`)
                .then(res => {
                    this.phonenumber = res.data.phone
                })
                .catch(res => {
                    this.requestSending = false
                    this.numberError = res.response.data.error;
                });
        },
        getStripeKey() {
            axios.get('/api/getStripeKey').then(res => {
                this.stripeKey = res.data
                this.getUrl();
            })
            .catch(err => {
                this.snackbar = true;
                this.snackbarText = err.response.data.message;
            });
        },
        getUrl() {
            axios.get('/api/getAppUrl').then(res => {
                this.url = res.data.url
                this.setCard()
            });
        },
        back(){
            this.$emit('back');
        },
        setCard() {
            const stripe = Stripe(this.stripeKey);

            const specs =  this.chosenProductSpecs;
            const price = this.totalPrice;

            let queryType = '';

            if (this.deceased) {
                queryType = this.deceased.code+'-'+this.deceased.firstname+'-'+this.deceased.lastname;
            } else {
                queryType = this.$route.params.code;
            }
            
            const product = this.product.id;
            const url = this.url

            let elements;

            initialize();
            checkStatus();

            document
            .querySelector("#payment-form")
                .addEventListener("submit", handleSubmit);


            async function initialize() {
                const { clientSecret } = await fetch("/api/checkout", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ specs, price }),
                }).then((r) => r.json());
                elements = stripe.elements({ clientSecret });
                const paymentElement = elements.create("payment");
                paymentElement.mount("#cardPayment");
            }

            async function handleSubmit(e) {
                e.preventDefault();
                document.querySelector("#submit").disabled = true;
                await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        return_url: url+`paymentConfirmed/${queryType}/${product}`,
                    },
                })
                .then(function(result) {
                    if (result.error) {
                        if (result.error.type === "card_error" || result.error.type === "validation_error") {
                            showMessage(result.error.message);
                        } else {
                            showMessage("An unexpected error occured.");
                        }
                    } else {
                        axios.post('/api/saveorder');
                    }
                    document.querySelector("#submit").disabled = false;
                });
            }

            async function checkStatus() {
                const clientSecret = new URLSearchParams(window.location.search).get(
                    "payment_intent_client_secret"
                );

                if (!clientSecret) {
                    return;
                }

                const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

                switch (paymentIntent.status) {
                    case "succeeded":                
                        showMessage("Payment succeeded!");
                    break;
                    case "processing":
                        showMessage("Your payment is processing.");
                    break;
                    case "requires_payment_method":
                        showMessage("Your payment was not successful, please try again.");
                    break;
                    default:
                        showMessage("Something went wrong.");
                    break;
                }
            }

            function showMessage(messageText) {
                const messageContainer = document.querySelector("#payment-message");
                messageContainer.scrollIntoView();
                messageContainer.classList.remove("hidden");
                messageContainer.textContent = messageText;

                setTimeout(function () {
                    messageContainer.classList.add("hidden");
                    messageContainer.textContent = "";
                }, 4000);
            }
        },
    },
    computed: { 
        deceased() {
            return this.$store.state.deceased;
        },
        styles() {
            return this.$store.state.styles;
        }
    },
    mounted() {
        this.getStripeKey();
        this.getContactDetails();
    }
    
}
</script>