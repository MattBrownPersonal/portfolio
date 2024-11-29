<template>
    <v-dialog
      v-model="contactDetailsDialogue"
      max-width="1000px"
      persistent
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit Contact Details</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{warningMessage}}
            </v-alert>
            <v-form id="contactDetails">
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="4">
                                <v-text-field
                                    label="Contact name*"
                                    required
                                    v-model='name'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Email Address*"
                                    required
                                    v-model='email'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Phone Number*"
                                    required
                                    v-model='phoneNumber'
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols>
                                <v-text-field
                                    label="Street*"
                                    required
                                    v-model='street'
                                ></v-text-field>
                            </v-col>
                            <v-col cols>
                                <v-text-field
                                    label="City*"
                                    required
                                    v-model='city'
                                ></v-text-field>
                            </v-col>
                            <v-col cols>
                                <v-text-field
                                    label="County*"
                                    required
                                    v-model='county'
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>                            
                            <v-col cols="4">
                                <v-text-field
                                    label="Country*"
                                    required
                                    v-model='country'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Postcode*"
                                    required
                                    v-model='postcode'
                                ></v-text-field>
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
                    @click="closeForm(); clearForm()"
                    >
                    Close
                    </v-btn>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="sendSiteForm(); clearForm()"
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
    props: ['contactDetailsDialogue', 'site'],
    data: function() {
        return {
            name: null,
            email: null,
            phoneNumber: null,
            warningMessage: false,
            street: null,
            city: null,
            county: null,
            country: null,
            postcode: null,
            id: null
        }
    },
    methods: {
        sendSiteForm(){
            let siteContactDetails = new FormData();
                siteContactDetails.append('name', this.name);
                siteContactDetails.append('email', this.email);
                siteContactDetails.append('phoneNumber', this.phoneNumber);
                siteContactDetails.append('street', this.street);
                siteContactDetails.append('city', this.city);
                siteContactDetails.append('county', this.county);
                siteContactDetails.append('country', this.country);
                siteContactDetails.append('postcode', this.postcode);
                siteContactDetails.append('id', this.id);
                siteContactDetails.append('_method', 'PUT');
            axios.post(`/api/sitecontactdetails/${this.id}`, siteContactDetails)
            .then(res => {
                this.closeForm();
                this.$emit('fetchSites');
                this.$emit('triggerSnackBar', res.data.message);              
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
            });
        },
        clearForm() {
            this.name = null;
        },
        closeForm() {
            this.$emit('closeForm', false)
        }
    },
    watch: {
        site: function(propVal) {
            this.name = propVal.primary_contact_name;
            this.email = propVal.primary_contact_email;
            this.street = propVal.street;
            this.city = propVal.city;
            this.county = propVal.county;
            this.country = propVal.country;
            this.postcode = propVal.postcode;
            this.phoneNumber = propVal.phone;
            this.id = propVal.id
        }
    }
})
</script>
