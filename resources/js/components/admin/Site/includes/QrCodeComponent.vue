<template>
    <v-row>
        <v-col>
            <v-card>
                <v-card-title>
                    <p v-if="site">QR Codes</p>                    
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col class="col" v-for="qrCode in qrCodes">
                            <p class="font-weight-bold">{{qrCode.location}}</p>
                            <div class="qrCodeThumbnail" v-html="qrCode.qrCode"></div>
                            <canvas id="myCanvas" width="1180" height="1180" style="display: none;"></canvas>
                            <br>
                            <v-btn class="error" @click="deleteQrCode(qrCode.qrId)" small>Delete QR Code</v-btn> 
                            <v-btn class="primary" @click="editQrCode(qrCode)" small>Edit QR Code</v-btn>
                            <br>
                            <br>
                            <v-btn class="primary" @click="setQrOnCanvas(qrCode.qrCode, 'QRCODE: '+qrCode.location)" small>Download QR Code</v-btn>                            
                            <v-btn class="primary" @click="printLeaflet(qrCode)" small>Print as leaflet</v-btn>
                        </v-col>   
                    </v-row>
                    <v-row>                     
                        <v-col class="col">
                            <v-btn class="primary" @click="qrCode()">Add New QR Code</v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
        <DeleteQrCodeComponent
            v-bind:deleteQrCodeDialogue="deleteQrCodeDialogue" 
            v-bind:qrCodeId="qrCodeId"
            v-on:closeForm="closeFormChange($event)"
            v-on:fetchSiteQrCodes="fetchSiteQrCodes()"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />
        <EditQRCodeComponent 
            v-bind:editQrCodeDialog="editQrCodeDialog"
            v-bind:selectedCode="selectedCode"          
            v-bind:slug="slug"  
            v-on:closeForm="closeFormChange($event)"
            v-on:triggerSnackBar="triggerSnackBar($event)"
            v-on:fetchSiteQrCodes="fetchSiteQrCodes()"
        />
        <PrintLeafletComponent
            v-bind:site="site"
            v-bind:location_id="location_id"
            v-bind:launchPdf = "launchPdf"
            v-bind:selectedCode="selectedCode"
            v-on:resetLocation="resetLocation()"
        />
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
    </v-row>
</template>
<script>
import DeleteQrCodeComponent from '../modals/DeleteQrCodeComponent.vue'
import EditQRCodeComponent from '../modals/EditQRCodeComponent.vue'
import PrintLeafletComponent from './PrintLeafletComponent.vue'

export default {
    props: ['site', 'qrCodes'],
    components: {
        DeleteQrCodeComponent,
        EditQRCodeComponent,
        PrintLeafletComponent
    },
    data() {
        return {
            qrCodeId: null,
            selectedCode: null,
            deleteQrCodeDialogue: false,
            editQrCodeDialog: false,
            slug: null,
            snackbar: false,
            snackbarText: '',
            timeout: 4000,
            location_id: '',
            launchPdf: null
        }
    },
    methods: {
        qrCode() {
            this.$emit('qrCode', true)
        },
        printLeaflet(qrCode) {
            this.launchPdf = true
            this.location_id = qrCode.qrId;
        },
        fetchSiteQrCodes(){
            this.$emit('fetchSiteQrCodes');
        },
        deleteQrCode($id) {
            this.qrCodeId = $id;
            this.deleteQrCodeDialogue = true;
        },
        editQrCode($code) {
            this.selectedCode = $code;
            this.slug = this.site.slug;
            this.editQrCodeDialog = true;
        },
        closeFormChange($event) {
            this.deleteQrCodeDialogue = $event;
            this.editQrCodeDialog = $event;
        },
        setQrOnCanvas(qrCode, location) {
            var canvas = document.getElementById("myCanvas");
            var ctx = canvas.getContext("2d");
            var DOMURL = self.URL || self.webkitURL || self;
            var img = new Image();
            var svg = new Blob([qrCode], {
            type: "image/svg+xml;charset=utf-8"
            });
            var url = DOMURL.createObjectURL(svg);

            img.onload = function() {
                ctx.drawImage(img, 0, 0);
                var imgURL = canvas.toDataURL("image/png");
                DOMURL.revokeObjectURL(imgURL);
                var dlLink = document.createElement('a');
                dlLink.download = "QR code for " + location;
                dlLink.href = imgURL;
                dlLink.dataset.downloadurl = ["image/png", dlLink.download, dlLink.href].join(':');
                document.body.appendChild(dlLink);
                dlLink.click();
                document.body.removeChild(dlLink);
            }
            img.src = url;
            ctx.drawImage(img,0,0);
        },
        triggerSnackBar($event) {
            this.snackbarText = $event
            this.snackbar = true;
        },
        resetLocation() {
            this.launchPdf = null
        }
    },
}
</script>
<style>
.qrCodeThumbnail svg {
    max-width: 110px;
    max-height: 110px;
}
</style>