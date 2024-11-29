<template>
    <div class="px-4 show-deceased">
        <v-row>
            <v-col class="col-2">
                <v-card elevation="2">
                    <v-card-text>
                        <p class="text-h6">Actions</p>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title class="pb-4">
                                    <span class="actionLink" @click="editDeceasedDialog = true;">
                                        <v-icon left>mdi-pencil</v-icon>
                                        Edit
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
            </v-col>
            <v-col class="col-10">
                <LoadingComponent  v-if="!deceased" />
                <div v-if="deceased">
                    <v-row>
                        <v-col>
                            <v-card elevation="2">
                                <v-card-title>
                                    <p>Deceased's Details</p>
                                </v-card-title>
                                <v-card-text>
                                    <v-row>
                                        <v-col class="col-3">
                                            <p class="font-weight-bold">Name</p>
                                            {{ deceased.firstname }} {{ deceased.middlename }} {{ deceased.lastname }}
                                        </v-col>                                  
                                        <v-col class="col-3" v-if="deceased.date_of_birth">
                                            <p class="font-weight-bold">Date of Birth</p>
                                            <span v-if="deceased.date_of_birth">{{ moment(deceased.date_of_birth).format('Do MMMM YYYY') }}</span>
                                            <i v-else>-</i>
                                        </v-col>
                                        <v-col class="col-3">
                                            <p class="font-weight-bold">Date of Death</p>
                                            <span v-if="deceased.date_of_death">{{ moment(deceased.date_of_death).format('Do MMMM YYYY') }}</span>
                                            <i v-else>-</i>
                                        </v-col>
                                        <v-col class="col-3">
                                            <p class="font-weight-bold">Date of Cremation</p>
                                            <span v-if="deceased.date_of_cremation">{{ moment(deceased.date_of_cremation).format('Do MMMM YYYY') }}</span>
                                            <i v-else>-</i>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>               
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-card elevation="2">
                                <v-card-title>
                                    <p>Primary Contact Details</p>
                                </v-card-title>
                                <v-card-text>
                                    <v-row>
                                        <v-col class="col-3">
                                            <p class="font-weight-bold">First Name</p>                                    
                                            <span v-if="deceased.contact_firstname">{{ deceased.contact_firstname }}</span>
                                            <i v-else>-</i>
                                        </v-col>
                                        <v-col class="col-3">
                                            <p class="font-weight-bold">Last Name</p>
                                            <span v-if="deceased.contact_lastname">{{ deceased.contact_lastname }}</span>
                                            <i v-else>-</i>
                                        </v-col>
                                        <v-col class="col-3">
                                            <p class="font-weight-bold">Email</p>
                                            <span v-if="deceased.contact_email">{{ deceased.contact_email }}</span>
                                            <i v-else>-</i>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>               
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-card elevation="2">
                                <v-card-title>
                                    <p>Site</p>
                                </v-card-title>
                                <v-card-text>
                                    <v-row>
                                        <v-col class="col-3">
                                            <p class="font-weight-bold">Site Name</p>
                                            <span v-if="deceased.site.name">{{ deceased.site.name }}</span>
                                        </v-col>
                                        <v-col class="col-3" v-if="deceased && deceased.site">
                                            <p class="font-weight-bold">Site Address</p>
                                            <span>{{ deceased.site.street }}</span><br>
                                            <span>{{ deceased.site.city }}</span> <br>
                                            <span>{{ deceased.site.county }}</span><br>
                                            <span>{{ deceased.site.postcde }}</span>
                                        </v-col>
                                        <v-col class="col-3">
                                            <p class="font-weight-bold">Funeral Director</p>
                                            <span v-if="deceased.funeral_director">{{ deceased.funeral_director }}</span>
                                            <i v-else>-</i>
                                        </v-col>
                                        <v-col class="col-3">
                                            <p class="font-weight-bold">Cremation Number</p>
                                            <span v-if="deceased.cremation_number">{{ deceased.cremation_number }}</span>
                                            <i v-else>-</i>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-card elevation="2">
                                <v-card-title>
                                    <p>Memorialisation Link</p>
                                </v-card-title>
                                <v-card-text>
                                    <v-row>
                                        <v-col class="col-2">
                                            <p class="font-weight-bold">QR Code</p>
                                            <div id="qrCode" v-html="qrCode"></div>
                                        </v-col>                                    
                                        <v-col class="col-10">
                                            <v-row>
                                                <v-col class="col-6">
                                                    <p class="font-weight-bold">Link</p>
                                                    <pre><code><a :href="deceased.memorialisation_link" target="_blank">{{ deceased.memorialisation_link }}</a></code></pre>
                                                </v-col>
                                                <v-col class="col-2">
                                                    <p class="font-weight-bold">Custom Code</p>
                                                    {{ deceased.landing_code }}
                                                </v-col>
                                                <v-col class="col-2">
                                                    <p class="font-weight-bold">Code Sent?</p>
                                                    {{ deceased.link_printed == 1 ? 'Yes' : 'No' }} <br>                                    
                                                </v-col>
                                                <v-col class="col-2">
                                                    <p class="font-weight-bold">Code Emailed?</p>
                                                    {{ deceased.link_emailed == 1 ? 'Yes' : 'No' }}
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col class="col-4">
                                                    <v-btn
                                                        color="primary"
                                                        text      
                                                        class="pl-0"
                                                        @click="generateQRCode()"
                                                        >
                                                        Print QR Code
                                                    </v-btn>
                                                </v-col>
                                                <v-col class="col-4">
                                                    <v-btn
                                                        color="primary"   
                                                        text      
                                                        class="pl-0"                    
                                                        @click="updateLinkTable(); openLeaflet()"
                                                        >
                                                        Print A4 Leaflet
                                                    </v-btn>                                                
                                                </v-col>
                                                <v-col class="col-4">
                                                    <v-btn
                                                        color="primary"
                                                        text      
                                                        class="pl-0"
                                                        @click="sendCode()"
                                                        >
                                                        Send Email Link to Customer
                                                    </v-btn>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                    </v-row>                                
                                </v-card-text>
                            </v-card>               
                        </v-col>
                    </v-row>
                </div>
            </v-col>
        </v-row>
        <v-snackbar
            v-model="snackbar"
            :timeout="timeout"
            color="primary"
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
        <EditDeceasedComponent
            v-bind:editDeceasedDialog="editDeceasedDialog" 
            v-on:closeForm="closeFormChange($event)"
            v-bind:selectedDeceased="deceased"
            v-on:fetchSingleDeceased="fetchSingleDeceased()"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />
        <vue-html2pdf 
            :show-layout="false" 
            :enable-download="false" 
            :preview-modal="true" 
            :manual-pagination="true" 
            pdf-content-width="90mm" 
            :html-to-pdf-options="htmlToPdfOptions"
            ref="html2Pdf"
            v-if="deceased">
            <section slot="pdf-content" class="qr-code-design">     
                <div class="qr-alignment"></div>
                <div class="qr-heart-curves"></div>
                <div id="qrCode" class="qrCode" v-html="qrCode"></div>
                <div class="label-main-text">
                    <div class="crematorium-header" id="cremHeaderId">
                        <span v-if="deceased && deceased.site">{{ deceased.site.name }}</span>
                    </div>
                </div>
                <div class="titleTextContainer label-deceased-text-container">
                    <div class="">
                        <span class="titleText">Cremated remains of the late</span> <br>
                        <span class="titleText titleTextDeceased">{{ deceased.firstname }} {{ deceased.middlename }} {{ deceased.lastname }}</span> <br>
                    </div>
                </div>
                <hr class="lable-divider lable-divider-1" />
                <hr class="lable-divider lable-divider-2" />
                <div class="label-link-container">
                    <span>For bereavement support<br>
                    &amp; memorial options please<br>
                    scan the QR code or visit<br>
                    </span><span class="label-link-url">rememberalways.co.uk</span><span><br>
                    and enter privacy key:</span><span class="label-landing-code">
                    {{ deceased.landing_code || "UNAVAILABLE" }}</span>
                </div>
                <div class="label-crematorium-details-container">
                    <div class="funeral-director">
                        <span>{{ deceased.funeral_director }}</span>
                    </div>
                    <span class="">Cremation No: {{ deceased.cremation_number }}</span><br>
                    <span class="">Cremation Date: {{ moment(deceased.date_of_cremation).locale('en-gb').format('L') }}</span>
                </div>
            </section>
        </vue-html2pdf>
        <vue-html2pdf 
            :show-layout="false" 
            :enable-download="false" 
            :preview-modal="true" 
            :manual-pagination="true" 
            :html-to-pdf-options="htmlToPdfOptionsA4"
            pdf-orientation="portrait" 
            pdf-content-width="A4" 
            ref="leaflet"
            v-if="deceased">
            <section slot="pdf-content" class="a4-leaflet-design">
                <div class="a4-leaflet a4-background-reference-image"></div>
                <div class="pre-printed-section text-size-medium">
                    <h1 class="header">Bereavement Support &amp; Personalised Memorial Options</h1>
                    <h2 class="tagline">We're here to help</h2>
                    <p>While bereavement is universal, each of us finds different ways to cope.</p>
                    <p>We've created a range of articles which you may find helpful if you are looking for support during this difficult time.</p>
                    <p>We've also set up a dedicated online space where you can view and personalise a range of customisable memorial options.</p>
                    <p>Scan the QR code or follow the link below and enter your unique access code.</p>
                </div>
                <div class="qr-heart-curves-a4"></div>
                <div class="qr-code-a4" v-html="qrCode"></div>
                <div class="scan-me-graphic-a4"></div>
                <div class="link-a4 text-size-medium">rememberalways.co.uk</div>
                <div class="access-code-a4 text-size-medium">Privacy key: {{ (deceased.landing_code) || "UNAVAILABLE" }}</div>
                <div class="sign-off-a4 text-size-medium">Warmest regards,</div>
                <div class="crem-name-a4 text-size-medium">{{ deceased.site.name }}</div>
                <div class="crem-logo-a4"><img v-if="siteStyle && siteStyle.image" :src="siteStyle.image.imageurl"></div>
                <div class="additional-graphic-a4"><!-- additional graphic TBD --></div>
            </section>
        </vue-html2pdf>
    </div>
</template>
<script>
import LoadingComponent from '../LoadingComponent'
import EditDeceasedComponent from './modals/EditDeceasedComponent'
import VueHtml2pdf from 'vue-html2pdf'

export default {
    components: {
        EditDeceasedComponent,
        LoadingComponent,
        VueHtml2pdf,
    },
    data () {
        return {
            deceased: undefined,
            qrCode: '',
            firstname: 'John',
            snackbar: false,
            snackbarText: '',
            timeout: 4000,          
            editDeceasedDialog: false,
            htmlToPdfOptions: {
                margin: 0,

                filename: `QR Code.pdf`,

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
                    format: [90, 90],
                    orientation: 'landscape'
                }
            },
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
            },
            siteStyle: undefined
        }
    },
    methods: {
        generateQRCode() {
            this.$refs.html2Pdf.generatePdf()
        },
        openLeaflet() {
            this.$refs.leaflet.generatePdf()
        },
        fetchSingleDeceased() {
            axios.get(`/api/deceased/${this.$route.params.id}`)
            .then(res => {
                this.deceased = res.data.deceased;
                this.fetchQrCode();
                this.fetchSiteStyles();
            })
        },
        fetchQrCode() {
            const deceased = new FormData();
            deceased.append('code', this.deceased.code);
            deceased.append('firstname', this.deceased.firstname); 
            deceased.append('middlename', this.deceased.middlename); 
            deceased.append('lastname', this.deceased.lastname);          
            axios.post('/api/qr', deceased)
            .then(res => {
                this.qrCode = res.data;
            })
        },
        fetchSiteStyles() {
            axios.get(`/api/siteStyles/${this.deceased.site.id}`)
            .then(res => {
                this.siteStyle = res.data;
            })
        },
        updateLinkTable() {
            axios.post('/api/qrCodePrinted', {id: this.deceased.id})
            .then(this.fetchSingleDeceased());
        },
        sendCode() {
            axios.post('/api/sendApplicantMail', { deceased: this.deceased })
                .then(res => {
                    this.snackbarText = res.data.message;
                    this.snackbar = true;
                    this.fetchSingleDeceased();
                })
                .catch(res => {
                    if (res.response.data.error) {
                        console.error(res.response.data.error);
                        this.snackbarText = res.response.data.message;
                        this.snackbar = true;
                    }
                });
        },
        triggerSnackBar($event) {
            this.snackbarText = $event
            this.snackbar = true;
        },
        closeFormChange(state) {
            this.editDeceasedDialog = state;
        },
    },
    mounted() {
        this.fetchSingleDeceased();
    }
}
</script>
<style>
.crudMenu {
    list-style-type: none;
}

.actionLink {
    cursor: pointer;
}

.outerCircle {
    height: 1000mm;
    width: 1000mm;
}

.urlText {
    color: black;
    display: block;
    margin-top: 3.9mm;
    margin-left: 4.4mm;
    line-height: 5mm;
}

.label-main-text {
    position: absolute;
    top: 4mm;
    left: 5mm;
    width:80mm;
    color: black;
    border: yellow solid 0px;
}

.label-deceased-text-container {
    position: absolute;
    top: 12mm;
    left: 5mm;
    width:80mm;
    color: black;
    border: yellow solid 0px;
    text-align: center;
}

.label-link-container{
    position: absolute;
    top: 40mm;
    left: 40mm;
    width:50mm;
    color: black;
    border: yellow solid 0px;
    text-align: center;
    font-size: 0.7em;
}

.label-crematorium-details-container{
    position: absolute;
    top: 70mm;
    left: 5mm;
    width:80mm;
    color: black;
    border: yellow solid 0px;
    text-align: center;
    font-size: 0.9em;
}

.lable-divider {    
    position: absolute;
    left: 11mm;
    border-width: 0.25mm !important;
    border-color: black !important;
    width: 68mm;
    margin-top: 0mm;
    margin-left: 0mm;
    padding-top: 0mm;
    padding-left: 0mm;
    display:none;
}
.lable-divider-1 {    
    top: 31mm;
}

.lable-divider-2 {    
    top: 69mm;
}

.qrCode {
    transform: rotate(45deg) scale(0.6);
    position: absolute;
    top:39mm;
    left:9mm;
}

.text-center.heart {
    margin-top: 0mm;
    margin-left: 0mm;
    padding-top: 0mm;
    padding-left: 0mm;

}

.crematorium-header {
    text-align: center;
    font-size: 0.9em;
    white-space: pre-wrap;
    word-break: keep-all;
    line-height: 7mm;
}
.titleTextContainer {
    line-height: 1.5em;
    padding-top: 0mm;
    margin-top: 0mm;
}
.titleText {
    font-size: 1.1em;
}

.titleTextDeceased
{
    font-size: 1.2em;
}
.instructionText {
    font-size: 0.9em;
}

.cremDetails {
    margin-top: 0mm;
    padding-top: 0mm;
    padding-bottom: 0mm !important;
    margin-left: auto;
    margin-right: auto;
    font-size: 1.2em;
}

#cremHeaderId{
    max-width: 100%;
    margin-left: auto;
    margin-right: auto;
    padding-left:0mm;
    padding-right:0mm;
}


.qr-heart-curves {
    position: absolute;
    top:0;
    left:0;
    width:90mm;
    height: 90mm;
    background-image: url(/svg/qr_heart_curves.svg);
    display: none;
}

.qr-alignment {
    position: absolute;
    top:0;
    left:0;
    width:90mm;
    height: 90mm;
    background-image: url(/svg/qr_alignment.svg);
    display: none;
}

.qr-divider-2 {
    margin-top: 10mm;
}

.cremDetailsSection {
    margin-top: 1mm;
    padding-top: 0mm;
    text-align: center;
    line-height: 6mm;
}

.cremDetails.funeral-director{
    text-align: center;
    margin-top: 0mm;
    font-size: 1.0em;
}


.qr-code-design {
    zoom:100%;
    width:90mm;
    height: 90mm;
}

.label-link-url{
    font-weight: bold;
}

.label-landing-code{
    font-weight: bold;

}

.a4-leaflet-design {
    zoom:100%;
    width:210mm;
    height:296mm; /* if you set this to 297mm then you overflow to a second page */
}

.a4-background-reference-image {
    background-image: url(/images/Vivedia%20A4%20leaflet%20reference.png);
    position: absolute;
    top:0;
    left:0;
    width:210mm;
    height: 297mm;
    background-size: contain;
    background-repeat: no-repeat;
    display:none;
}

.qr-heart-curves-a4 {
    position: absolute;
    transform: scale(0.67);
    top: 234px;
    left: 401px;
    width: 100mm;
    height: 100mm;
    background-image: url(/svg/a4_heart_curves_.svg);
}
.qr-code-a4 {
    position: absolute;
    top: 488px;
    left: 399px;
    width: 100mm;
    height: 100mm;
    transform: rotate(45deg) scale(1.05);
}
.qr-code-a4 g g path {
    fill: #005039
}
.scan-me-graphic-a4 {
    position: absolute;
    transform: scale(0.67);
    top: 463px;
    left: 561px;
    width: 100mm;
    height: 100mm;
    background-image: url(/images/scan-me.png);
}
.link-a4 {
    position: absolute;
    top: 796px;
    left: 60px;
}

.access-code-a4 {
    position: absolute;
    top: 821px;
    left: 60px;
}
.sign-off-a4 {
    position: absolute;
    top: 876px;
    left: 60px;
}

.crem-name-a4 {
    position: absolute;
    top: 902px;
    left: 60px;
}
.crem-logo-a4 {
    position: absolute;
    top: 1003px;
    left: 60px;
}
.crem-logo-a4 img {
    max-width: 300px;
}
.pre-printed-section{
    position: absolute;
    top: 125px;
    left: 60px;
}

.pre-printed-section h1 {
    max-width: 683px;
}

.pre-printed-section h2 {
    max-width: 683px;
    position: absolute;
    top: 211px;
}

.pre-printed-section p:nth-child(3){
    max-width: 89mm;
    position: absolute;
    top: 267px;
}

.pre-printed-section p:nth-child(4){
    max-width: 89mm;
    position: absolute;
    top: 350px;
}

.pre-printed-section p:nth-child(5){
    max-width: 90mm;
    position: absolute;
    top: 455px;
}
.pre-printed-section p:nth-child(6){
    max-width: 89mm;
    position: absolute;
    top: 589px;
}
</style>
