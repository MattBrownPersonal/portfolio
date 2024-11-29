<template>
    <v-dialog
      v-model="newSiteDialog"
      persistent
      max-width="1000px"
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Add New Site</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{ warningMessage }}
            </v-alert>
            <v-form 
            ref="form" 
            v-model="valid"
            >
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="4">
                                <v-text-field
                                    label="Obitus Site ID*"
                                    required
                                    :rules="[() => !!siteId || 'This field is required']"
                                    :error-messages="errorMessages"
                                    v-model='siteId'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Site name*"
                                    required
                                    :rules="[() => !!siteName || 'This field is required']"
                                    :error-messages="errorMessages"
                                    v-model='siteName'
                                    @keyup="checkSiteStringForSpaces()"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Site URL*"
                                    required
                                    :rules="[() => !!siteUrl || 'This field is required']"
                                    :error-messages="errorMessages"
                                    v-model='siteUrl' 
                                    @keyup="checkUrlStringForSpaces()"                                   
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Account Type*"
                                    required
                                    :rules='rules'
                                    :error-messages="errorMessages"
                                    v-model='accountType'
                                ></v-text-field>
                            </v-col>                           
                            <v-col cols="4">
                                <v-text-field
                                    label="Site Type*"
                                    required
                                    :rules='rules'
                                    :error-messages="errorMessages"
                                    v-model='siteType'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Operator Type*"
                                    required
                                    :rules='rules'
                                    :error-messages="errorMessages"
                                    v-model='operatorType'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Parent Account"
                                    required                                  
                                    v-model='parentAccount'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Primary Contact Name"
                                    required
                                    v-model='contactName'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Primary Contact Email"
                                    required
                                    v-model='contactEmail'
                                    :rules="emailRules"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Phone"
                                    required
                                    v-model='phoneNumber'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Street"
                                    required
                                    v-model='street'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="City"
                                    required
                                    v-model='city'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="County"
                                    required
                                    v-model='county'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Country"
                                    required
                                    v-model='country'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Postcode"
                                    required
                                    v-model='postcode'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="Region"
                                    required
                                    v-model='region'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <p>Does this site use product categories?</p>
                                <v-radio-group v-model="usesCategories" row mandatory>
                                    <v-radio label="Yes" value="1"></v-radio>
                                    <v-radio label="No" value="0"></v-radio>
                                </v-radio-group>
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
                    @click="submit()"
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
    props: ['newSiteDialog'],
    data() {
        return {
            valid: true,
            siteId: null,
            siteName: null,
            siteUrl: null,
            accountType: null,
            siteType: null,
            operatorType: null,
            parentAccount: null,
            contactName: null,
            contactEmail: null,
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
            phoneNumber: null,
            street: null,
            city: null,
            county: null,
            country: null,
            postcode: null,
            region: null,
            warningMessage: '',
            errorMessages: '',
            usesCategories: null,
            rules: [
                v => !!v || 'Field cannot be left blank',
                v => (v && v.length <= 255) || 'Input must be less than 255 characters',
            ], 
        }
    },
    methods: {
        submit() {
            if (this.$refs.form.validate()) {
                const site = new FormData();
                site.append('siteId', this.siteId);
                site.append('siteName', this.siteName);
                site.append('siteUrl', this.siteUrl);
                site.append('accountType', this.accountType);
                site.append('siteType', this.siteType);
                site.append('operatorType', this.operatorType);
                site.append('parentAccount', this.parentAccount);
                site.append('contactName', this.contactName);
                site.append('contactEmail', this.contactEmail);
                site.append('phoneNumber', this.phoneNumber);
                site.append('street', this.street);
                site.append('city', this.city);
                site.append('county', this.county);
                site.append('country', this.country);
                site.append('postcode', this.postcode);
                site.append('region', this.region);
                site.append('usesCategories', this.usesCategories);
                axios.post('/api/sites', site)
                    .then(res => {
                        this.close();
                        this.$emit('fetchSites');
                        this.$emit('triggerSnackBar', res.data.message);
                        this.$refs.form.reset();
                    })
                    .catch(err => {
                        this.warningMessage = err.response.data.message;
                        this.$refs.form.reset();
                    });
            }
        },
        close() {
            this.$emit('closeForm', false)
            this.$refs.form.reset();
        },
        validate() {
            this.$refs.form.validate()
        },
        checkSiteStringForSpaces() {
            let siteString = this.siteName;
            this.siteUrl = siteString.replace(/\s+/g, '-').toLowerCase()
        },
        checkUrlStringForSpaces() {
            let urlString = this.siteUrl;
            this.siteUrl = urlString.replace(/\s+/g, '-').toLowerCase();
        }
    }

})
</script>
