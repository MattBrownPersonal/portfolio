<template>
    <div class="px-4">
        <v-row>
            <v-col class="col-2">
                <ActionsComponent
                    v-on:editSiteStyleDetails="editSiteStyleDetails($event)"
                />
                <UsefulLinksComponent 
                    v-bind:site="site" 
                />
            </v-col>
            <v-col class="col-10">
                <LoadingComponent v-if="!site"></LoadingComponent>
                <div v-if="site">
                    <SiteDetailsComponent
                        v-on:editSiteDetails="editSiteDetails($event)"
                        v-bind:site="site"                    
                    />
                    <QrCodeComponent
                        v-bind:site="site" 
                        v-bind:qrCodes="qrCodes"
                        v-on:qrCode="qrCode($event)"
                        v-on:fetchSiteQrCodes="fetchSiteQrCodes()"
                        v-on:editQrCode="editQrCode($event)"
                        v-on:triggerSnackBar="triggerSnackBar($event)"
                    />
                    <ContactDetailsComponent
                        v-on:contactDetails="contactDetails($event)"
                        v-bind:site="site"
                    />
                    <SiteStylesComponent 
                        v-bind:siteStyle="siteStyle"   
                        v-on:fetchSite="fetchAllStyles()"                 
                    />
                </div>
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
        <EditSiteComponent
            v-bind:editSiteDetailsDialog="editSiteDetailsDialog" 
            v-on:closeForm="closeFormChange($event)"
            v-bind:selectedSite="site"
            v-on:fetchSites="fetchSites()"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />
        <EditSiteStyleComponent
            v-bind:editSiteStyleDialog="editSiteStyleDialog" 
            v-on:closeForm="closeFormChange($event)"
            v-bind:siteStyle="siteStyle"
            v-on:fetchSites="fetchAllStyles"
            v-bind:selectedSite="site"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />
        <EditContactDetailsComponent
            v-bind:contactDetailsDialogue="contactDetailsDialogue" 
            v-on:closeForm="closeFormChange($event)"
            v-bind:site="site"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />
        <AddNewQrCodeComponent 
            v-bind:newQrCodeDialog="newQrCodeDialog"
            v-bind:site="site"            
            v-on:closeForm="closeFormChange($event)"
            v-on:triggerSnackBar="triggerSnackBar($event)"
            v-on:fetchSiteQrCodes="fetchSiteQrCodes()"
        />        
    </div>
</template>
<script>
import EditSiteStyleComponent from './modals/EditSiteStyleComponent'
import EditSiteComponent from './modals/EditSiteComponent'
import EditContactDetailsComponent from './modals/EditContactDetailsComponent.vue'
import ActionsComponent from './includes/ActionsComponent'
import SiteDetailsComponent from './includes/SiteDetailsComponent'
import ContactDetailsComponent from './includes/ContactDetailsComponent'
import QrCodeComponent from './includes/QrCodeComponent'
import SiteStylesComponent from './includes/SiteStylesComponent'
import UsefulLinksComponent from '../UsefulLinksComponent.vue'
import LoadingComponent from '../LoadingComponent.vue'
import AddNewQrCodeComponent from '../Site/modals/AddNewQRCodeComponent.vue'

export default {
    components: {
        EditSiteStyleComponent,
        EditContactDetailsComponent,
        ActionsComponent,
        SiteDetailsComponent,
        ContactDetailsComponent,
        SiteStylesComponent,
        UsefulLinksComponent,
        LoadingComponent,
        QrCodeComponent,
        AddNewQrCodeComponent,
        EditSiteComponent
    },
    data () {
        return {
            allStaff: [],
            siteStyle:{
                primary_colour: '#25FF74',
                secondary_colour: '#FDF12B',
                primary_font_colour: '#082DFF',
                secondary_font_colour: '#FF2222'
            },
            site: null,
            snackbar: false,
            snackbarText: '',
            timeout: 4000,
            editSiteStyleDialog: false,  
            contactDetailsDialogue: false,    

            editSiteDetailsDialog: false,
            newQrCodeDialog: false,
            qrCodes: null,
            htmlToPdfOptionsA4: {
                margin: 0,

                filename: `A4 Leaflet.pdf`,

                image: {
                    type: 'png',
                },

                enableLinks: false,

                html2canvas: {
                    dpi: 203,
                    scale: 4,
                    useCORS: true
                },

                jsPDF: {
                    unit: 'mm',
                    format: [210, 297],
                    orientation: 'portrait'
                }
            }
        }
    },
    methods: {
        fetchAllStaff() {
            axios.get(`/api/siteStaff/${this.$route.params.id}`)
            .then(res => {
                this.sitesTableLoading = false;
                this.allStaff = res.data.staff;
            })
            .catch(err => {
                this.snackbar = true;
                this.snackbarText = err.response.data.message;
            });
        },
        editSiteStyleDetails($event) {
            this.editSiteStyleDialog = $event;
            this.fetchSiteInfo();
            this.fetchAllStyles();
        },
        contactDetails($event) {            
            this.contactDetailsDialogue = $event;
        },
        editSiteDetails($event) {
            this.editSiteDetailsDialog = $event;
        },
        qrCode($event) {
            this.newQrCodeDialog = $event;
        },
        fetchSiteQrCodes() {
            axios.get(`/api/qrCodes/${this.$route.params.id}`)
            .then(res => {
                if(res.data) {
                    this.qrCodes = res.data;
                }                
            })
            .catch(err => {
                this.snackbar = true;
                this.snackbarText = err.response.data.message;
            });
        },
        fetchSiteInfo() {
            axios.get(`/api/sites/${this.$route.params.id}`)
            .then(res => {
                this.sitesTableLoading = false;
                this.site = res.data.site;
            })                      
            .catch(err => {
                this.snackbar = true;
                this.snackbarText = err.response.data.message;
            });
        }, 
        fetchAllStyles() {
            axios.get(`/api/siteStyles/${this.$route.params.id}`)
            .then(res => {
                if (res.data) {
                    this.sitesTableLoading = false;
                    this.siteStyle = res.data;
                }
            })
            .catch(err => {
                this.snackbar = true;
                this.snackbarText = err.response.data.message;
            });
        },
        triggerSnackBar($event) {
            this.snackbarText = $event
            this.snackbar = true;
        },
        closeFormChange(state) {
            this.editDeceasedDialog = state;  
            this.editSiteStyleDialog = state;  
            this.contactDetailsDialogue = state;   
            this.newArticleDialogue = false;
            this.newQrCodeDialog = state
            this.editQrCodeDialog = state
            this.editSiteDetailsDialog = state
            this.fetchSiteInfo();
        },
    },
    mounted () {
        this.fetchAllStaff();
        this.fetchSiteInfo();
        this.fetchAllStyles();
        this.fetchSiteQrCodes();
    }
}
</script>
