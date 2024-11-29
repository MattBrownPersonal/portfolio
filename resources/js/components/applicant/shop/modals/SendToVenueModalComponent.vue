<template>
    <v-dialog
        v-model="sendToVenueDialogue"
        persistent
        max-width="621px"
        @click:outside="closeForm"
        scrollable
        class="send-email-modal"
    >
        <v-card :height=cardHeight>
            <v-card-text class="share-container">
                <div class="row float-end p-3">
                    <v-icon @click="closeForm()">mdi-close</v-icon>
                </div>  
                <div v-if="showDetails" class="share-image-container">                    
                    <div class="row">
                        <div class="col-10">
                            <h5 class="mb-0" v-if="recipient == 'other'">Do you wish to share this design with friends and family?</h5>
                            <h5 class="mb-0" v-else>Do you wish to share this design with the crematorium? </h5> 

                        </div>
                    </div>
                    <div class="row share-header pt-0">                   
                        <div class="col-12 text-center" v-if="newProduct">
                            <p class="h7 mb-0">{{ newProduct.name }}</p>
                        </div>
                    </div>
                    <div class="row attributes-container">
                        <div class="col-md-6 col-6 pb-0">
                            <div class="row">
                                <div class="col-12 text-left px-0 pb-0">
                                    <h5 class="mb-0">Attributes</h5>
                                </div> 
                            </div>
                            <div class="row mt-0 share-attributes">
                                <div class="col-12 text-left px-0" v-if="newProduct">
                                    <p class="text-size-small text-left" v-for="[attribKey, attribute] in chosenProductSpecs" :key="attribKey">
                                        <span v-if="attribute">
                                            {{ attribute.type_name}} :
                                            {{ attribute.name }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-6 pb-0">
                            <div class="row">
                                <div class="col-12 text-left px-0 pb-0">
                                    <h5 class="mb-0">Personalisations</h5>
                                </div>
                            </div>
                            <div class="row mt-0 share-attributes">
                                <div class="col-12 text-center px-0">
                                    <div v-for="text in productTextFields" :key="text.id">
                                        <p v-if="text.text || recipient == 'crematorium'" class="text-size-small text-left">{{ text.type.charAt(0).toUpperCase() + text.type.slice(1) }}: </p>
                                        <p class="text-size-small text-left">{{ text.text }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <v-form ref="form" v-model="valid" id="send-email-form">
                      <div class="row">
                          <div class="col-12">
                              <v-text-field
                              outlined
                              rounded
                                  v-model="customerEmail"
                                  label="Your email address*"
                                  :error-messages="emailError"
                                  @click="emailError = null" 
                                  :rules="[rules.required, rules.email]"
                                  name="customer-email"
                              ></v-text-field>
                              <v-text-field
                                outlined
                                rounded
                                label="Email address*"
                                v-if="recipient == 'other'"
                                v-model="sendTo"
                                :rules="[rules.required, rules.email]"
                                ></v-text-field>
                                <v-textarea
                                outlined
                                rounded
                                label="Message (Optional)"
                                v-model="customMessage"
                                name="customer-message"
                              >
                              </v-textarea>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-12 col-md-6 text-center ">
                          <v-btn
                          block
                          outlined                            
                          class="btn-outline"
                          color="grey"
                          @click="back()">Back</v-btn>
                      </div>                        
                          <div class="col-12 col-md-6 text-center order-first order-md-last">
                              <v-btn
                              block
                              class="btn"
                              @click="emailVenue()">Send</v-btn>
                          </div>
                      </div>
                    </v-form>       
                </div>      
                <div v-else>
                    <h5 v-if="recipient == 'other'">Do you wish to share this design with friends and family?</h5>
                    <h5 v-else>Do you wish to share this design with the crematorium? </h5> 
                    <div>
                        <v-img :src="editedImage" class="thumbnail-image"></v-img>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 col-md-6 text-center">
                            <v-btn
                            block
                            outlined                            
                            class="btn-outline"
                            color="grey"
                            @click="closeForm()">Cancel</v-btn>
                        </div>
                        <div class="col-12 col-md-6 text-center order-first order-md-last">
                            <v-btn 
                            class="btn btn-share-next"
                            block
                            @click="submitShowDetails()"
                            >Next</v-btn>
                        </div>
                    </div>
                </div>
            </v-card-text>                
        </v-card>
    </v-dialog>
</template>
<script>
import { getCodeFromParams } from '../../../../deceased';
export default {
    props: ['sendToVenueDialogue', 'chosenProductSpecs', 'customImageDetails', 'product', 'editedImage', 'recipient', 'productTextFields'],
    data () {
        return {
            primaryColor: '#0095a6',
            secondaryColor: '#ffffff',   
            name: null,
            contactNumber: null,
            reason: null,
            newChosenProductSpecs: null,
            newCustomImageDetails: null,
            newProduct: null,
            newEditedImage: null,
            showDetails: false,
            customMessage: null,
            sendTo: null,
            siteEmail: null,
            customerEmail: null,
            customText: null,
            cardHeight: '625px',
            emailError: null,
            rules: {
                required: value => !!value || 'Required.',
                email: value => {
                    const pattern = /.+@.+\..+/
                    if(!value) return true;
                    return pattern.test(value) || 'Invalid e-mail.'
                },
            }, 
            valid: false,
        }
    },
    methods: {

        back() {
          this.showDetails = false;
          this.cardHeight = '625px';
        },
        closeForm() {
            this.$emit('closeForm', false);
            this.showDetails = false;
            this.cardHeight = '625px';
            this.name = null;
            this.contactNumber = null;
            this.reason =  null;
            this.showDetails = false;
            this.customMessage = null;
            this.sendTo = null;
            this.customerEmail = null;
        },
        emailVenue() {
          if(this.$refs.form.validate()) {
            if (this.customerEmail == null) {
                this.emailError = 'Please enter your email address';
                return;
            }
            const shareWithCrem = this.recipient == 'crematorium';
            this.$emit('emailVenue', { message: this.customMessage, customRecipient: this.sendTo, customerEmail: this.customerEmail, shareWithCrem: shareWithCrem })  
            this.showDetails = false;
            this.closeForm();
          }
        },
        getContactDetails() {
            axios.get(`/api/contactdetails/${getCodeFromParams(this.$route.params)}`)
            .then( res => {
                this.siteEmail = res.data.primary_contact_email
            })
        },
        submitShowDetails() {
            this.showDetails = true;
            this.cardHeight = '770px';
        }
    },
    watch: {
        chosenProductSpecs: function(propVal) {
            this.newChosenProductSpecs = propVal;
        },
        customImageDetails: function(propVal) {
            this.newCustomImageDetails = propVal;
            this.customText = JSON.stringify(localStorage.getItem('customText'))
            this.getContactDetails();
        },
        product: function(propVal) {
            this.newProduct = propVal;
        },
        editedImage: function(propVal) {
            this.newEditedImage = propVal;
        },
    },
}
</script>