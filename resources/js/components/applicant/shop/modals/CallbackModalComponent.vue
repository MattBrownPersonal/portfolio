<template>
    <v-dialog
      v-model="callbackDialogue"
      max-width="474px"
      @click:outside="closeForm"
      class="main-modal"
    >
        <v-card class="text-center pb-5">
            <v-card-text class="contact-us-main">
                <div class="row">
                    <div class="col-1 px-0 py-0 offset-11 text-center">
                        <v-icon @click="closeForm()" style="padding-top: 30px;">mdi-close</v-icon>
                    </div>
                </div>
                <v-form 
                ref="form"
                id="newSite"
                v-model="valid"
                lazy-validation
                v-if="requestSent == false"
                >
                    <h6 class="primary-colour">Contact us</h6>
                    <p class="text-size-regular">Fill out the form below and one of our team will be in touch as soon as possible. Or call us directly on {{phonenumber}}.</p>
                    <v-container>
                        <v-row>
                            <v-col
                            cols="12"
                            class="px-0 px-md-4 pt-0 pb-2"
                            >
                            <v-text-field
                                outlined
                                rounded
                                v-model="name"
                                label="Name*"
                                :error-messages="nameError"
                                @click="nameError = null" 
                                required
                                class="form-line-height pt-0 pb-0"
                                hide-details

                            ></v-text-field>
                            </v-col>
                            <v-col
                            cols="12"
                            class="px-0 px-md-4 pt-0 pb-2"
                            >
                            <v-text-field
                                outlined
                                rounded
                                v-model="contactNumber"
                                label="Phone Number*"
                                required
                                :error-messages="numberError"
                                @click="numberError = null"
                                class="form-line-height"
                                hide-details
                            ></v-text-field>
                            </v-col>
                            <v-col
                            cols="12"
                            class="px-0 px-md-4 pt-0 pb-2"
                            >
                            <v-text-field
                                outlined
                                rounded
                                v-model="customerEmail"
                                label="Email Address*"
                                :error-messages="emailError"
                                @click="emailError = null" 
                                required
                                class="form-line-height"
                                hide-details
                            ></v-text-field>
                            </v-col>
                            <v-col
                            cols="12"
                            class="px-0 px-md-4 pt-0 pb-2 mb-5"
                            >
                            <v-textarea
                                outlined
                                rounded
                                v-model="reason"
                                label="Reason (Optional)"
                                hide-details
                            ></v-textarea>
                            </v-col>
                        </v-row>
                        <v-btn 
                            v-if="terms == false"
                            class="btn"
                            disabled
                            block
                            rounded
                            x-large
                            >Send</v-btn>
                        <v-btn
                        v-else
                        block
                        rounded
                        x-large
                        :color="styles.primary_colour"
                        :style="{color: styles.secondary_colour}"
                        class="btn"
                        @click="sendMessage(); "
                        >
                        <span v-if="requestSending === false">Send</span>
                        <v-progress-circular
                        v-else
                        indeterminate
                        :color="styles.secondary_colour"
                        ></v-progress-circular>
                        </v-btn>
                        <div class="text-center">
                            <v-checkbox
                                v-model="terms"
                                class="terms-checkbox"
                            >
                            <div slot='label' class="terms-link" v-if="deceased">
                                I agree to the <router-link :to=" { name: 'tos', params: { deceased: deceased.name } } " class="footer-link">Terms &amp; Conditions</router-link>
                            </div>
                            </v-checkbox>
                        </div>
                    </v-container>
                </v-form>
                <template v-else>
                    <div class="text-center mt-5" v-if="messageSentOK">
                        <span class="mdi md-100 mdi-checkbox-marked-circle-outline"></span>
                        <h4 style="color: green">Request Sent</h4>
                        <p>Your request for a callback has been sent to the venue <br>
                        A member of staff will be in touch with you soon</p>
                        <div class="modal-footer">
                            <v-btn
                            block
                            :color="styles.primary_colour"
                            :style="{color: styles.secondary_colour}"
                            @click="closeForm()"
                            >
                            Close</v-btn>
                        </div>
                    </div>
                    <div class="text-center mt-5" v-else>
                        <span class="mdi md-100"></span>
                        <h4 style="color: rgb(173, 49, 49)">Request Failed To Send</h4>
                        <p>We are sorry, but you message was not able to be sent at this time<br>
                        Please try again later.</p>
                        <div class="modal-footer">
                            <v-btn
                            block
                            :color="styles.primary_colour"
                            :style="{color: styles.secondary_colour}"
                            @click="closeForm()"
                            >
                            Close</v-btn>
                        </div>
                    </div>
                </template>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
import { getCodeFromParams } from '../../../../deceased';
import moment from 'moment';
export default {
    props: ['callbackDialogue'],
    data () {
        return {
            valid: true,
            requestSending: false,
            requestSent: false,
            primaryColor: '#0095a6',
            secondaryColor: '#ffffff',
            enableSubmitButton: true,
            name: null,
            contactNumber: null,
            reason: null,
            callbackDialogueStatus: null,
            numberError: null,
            nameError: null,
            emailError: null,
            customerEmail: null,
            phonenumber: null,
            terms: false,
            messageSentOK: true
        }
    },
    methods: {
        sendMessage() {
            if(!this.name) {
                this.nameError = 'Please enter your name';
                return;
            }
            if (!this.customerEmail) {
                this.emailError = 'Please enter your email address';
                return;
            }
            this.requestSending = true
            const request = new FormData();
            request.append('name', this.name);
            request.append('contactNumber', this.contactNumber);
            request.append('email', this.email);
            request.append('customerEmail', this.customerEmail);
            request.append('message', this.reason != null ? this.reason : 'No reason given');
            request.append('id', this.deceased.id);
            request.append('order_date', moment().format("YYYY-MM-DD"));
            request.append('site_id', this.styles.site_id);
            request.append('origin', 'contactUs');

            axios.post('/api/sendContactMessage', request)
            .then( res => {
                this.requestSent = true;
                this.numberError = null;
            })
            .catch( res =>{
                if (res.response.status === 422) {
                    this.numberError = true;
                    this.numberError = res.response.data.error;
                    this.requestSent = false;
                    this.requestSending = false;
                } else {
                    this.requestSent = true;
                    this.messageSentOK = false;
                    console.error(res.response.data.error);
                }
            });
        },
        getContactDetails() {
            axios.get(`/api/contactdetails/${getCodeFromParams(this.$route.params)}`)
            .then( res => {
                this.email = res.data.primary_contact_email
                this.phonenumber = res.data.phone
            })
                .catch(res => {
                    this.requestSending = false
                    this.numberError = res.response.data.error;
                });
        },
        closeForm() {
            this.requestSent = false;
            this.requestSending = false;
            this.$emit('closeForm', false);
            this.name = null;
            this.contactNumber = null;
            this.reason = null;
            this.customerEmail = null;
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
    watch: {
        callbackDialogue: function(propVal) {
            this.callbackDialogueStatus = propVal;
            this.getContactDetails();
        },
    },
}
</script>