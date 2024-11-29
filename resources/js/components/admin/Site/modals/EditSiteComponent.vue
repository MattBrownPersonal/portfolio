<template>
    <v-dialog
      v-model="editSiteDetailsDialog"
      max-width="800px"
      persistent
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit Site Details</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{warningMessage}}
            </v-alert>
            <v-form id="newUser">
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field
                                    label="Site ID*"
                                    required
                                    v-model='siteId'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                    label="Site URL*"
                                    required
                                    v-model='siteUrl'
                                    @keyup="checkStringForSpaces()"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field
                                    label="Site Type*"
                                    required
                                    v-model='siteType'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                    label="Parent Account*"
                                    required
                                    v-model='parentAccount'
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
    props: ['editSiteDetailsDialog', 'selectedSite'],
    data: function() {
        return {
            siteId: null,
            siteUrl: null,
            siteType: null,
            parentAccount: null,
            warningMessage: false,
        }
    },
    methods: {
        sendSiteForm(){
            let siteDetails = new FormData();
                siteDetails.append('obitus_site_id', this.siteId);
                siteDetails.append('slug', this.siteUrl);
                siteDetails.append('site_type', this.siteType);
                siteDetails.append('parent_account', this.parentAccount);
                siteDetails.append('_method', 'PUT');
            axios.post(`/api/siteDetails/${this.id}`, siteDetails)
            .then(res => {
                this.closeForm();
                this.$emit('fetchStyleName');
                this.$emit('triggerSnackBar', res.data.message);              
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
            });
        },
        checkStringForSpaces() {
            let string = this.siteUrl;
            string = string.replace(/\s+/g, '-').toLowerCase();
            this.siteUrl = string;
        },
        clearForm() {
            this.name = null;
        },
        closeForm() {
            this.$emit('closeForm', false)
        }
    },
    watch: {
        selectedSite: function(propVal) {
            this.siteId = propVal.obitus_site_id,
            this.siteUrl = propVal.slug,
            this.siteType = propVal.site_type,
            this.parentAccount = propVal.parent_account,
            this.id = propVal.id;
        }
    }
})
</script>
