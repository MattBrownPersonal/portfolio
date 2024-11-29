<template>
    <v-dialog
      v-model="newQrCodeDialog"
      persistent
      max-width="600px"
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Add New QR Code</span>
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
                            <v-col>
                                <v-text-field
                                    label="Location Name*"
                                    required
                                    :rules="[() => !!locationName || 'This field is required']"
                                    :error-messages="errorMessages"
                                    v-model='locationName'
                                    @keyup="checkLocationForSpaces()"
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
    props: ['newQrCodeDialog', 'site'],
    data() {
        return {
            valid: true,
            siteId: null,
            locationName: null,            
            rules: [
                v => !!v || 'Field cannot be left blank',
                v => (v && v.length <= 255) || 'Input must be less than 255 characters',
            ], 
            warningMessage: '',
            errorMessages: '',
        }
    },
    methods: {
        submit() {
            if (this.$refs.form.validate()) {
                const siteQr = new FormData();
                siteQr.append('location', this.locationName);
                siteQr.append('slug', this.site.slug);
                siteQr.append('id', this.site.id);
                axios.post("/api/qrCodes", siteQr)
                    .then(res => {
                        this.close();
                        this.$emit('fetchSiteQrCodes');
                        this.$emit('triggerSnackBar', 'QR Code Added Successfully');
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
        checkLocationForSpaces() {
            let locationString = this.locationName;
            this.locationName = locationString.replace(/\s+/g, '-').toLowerCase()
        },
    }

})
</script>
