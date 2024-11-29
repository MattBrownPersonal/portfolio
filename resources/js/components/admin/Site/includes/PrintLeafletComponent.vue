<template>
    <vue-html2pdf 
        :show-layout="false" 
        :enable-download="false" 
        :preview-modal="true" 
        :manual-pagination="true" 
        :html-to-pdf-options="{ 
            margin: 0, 
            filename: site.name+' Leaflet.pdf', 
            image: { type: 'jpeg', quality: 0.98 },
                enableLinks: false, 
                html2canvas: { scale: 1, useCORS: true }, 
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' } 
            }"
        pdf-orientation="portrait" 
        pdf-content-width="A4" 
        ref="leaflet">
        <section slot="pdf-content" class="a4-leaflet-design">
            <div class="a4-leaflet a4-background-reference-image"></div>
            <div class="pre-printed-section text-size-medium">
                <h1 class="header">Bereavement Support &amp; Memorial Options</h1>
                <h2 class="tagline">We're here to help</h2>
                <p>While bereavement is universal, each of us finds different ways to cope.</p>
                <p>We've created a range of articles which you may find helpful if you are looking for support during this difficult time.</p>
                <p>We've also set up a dedicated online space where you can view and personalise a range of customisable memorial options.</p>
                <p>Scan the QR code or access the link below to enter our site</p>
            </div>
            <div class="qr-heart-curves-a4"></div>
            <div v-if="qrCode" class="qr-code-a4" v-html="qrCode"></div>
            <div class="scan-me-graphic-a4"></div>
            <div class="link-a4 text-size-medium">{{ 'rememberalways.co.uk/m/' + site.slug }}</div>
            <div class="sign-off-a4 text-size-medium">Warmest regards,</div>
            <div class="crem-name-a4 text-size-medium">{{ site.name }}</div>
            <div class="crem-logo-a4"><img v-if="siteStyle && siteStyle.image" :src="siteStyle.image.imageurl"></div>
            <div class="additional-graphic-a4"><!-- additional graphic TBD --></div>
        </section>
    </vue-html2pdf>
</template>
<script>
import VueHtml2pdf from 'vue-html2pdf'
export default {
    props: ['siteStyle', 'site', 'location_id', 'launchPdf'],
    components: {
        VueHtml2pdf,
    },
    data() {
        return {
            qrCode: null,
            slug: '',
            selectedLocation_id:''
        }
    },
    methods: {
        openLeaflet() {
            this.$refs.leaflet.generatePdf()
        },
        fetchQrCode() {
            const site = new FormData();
            site.append('slug', this.site.slug);
            site.append('location_id', this.selectedLocation_id);       
            axios.post('/api/qrLeaflet', site)
            .then(res => {
                this.qrCode = res.data;
                this.openLeaflet();
                this.$emit('resetLocation')
            })
        },
    },
    watch: {
        launchPdf: function (propVal) {
            this.selectedLocation_id = this.location_id;
            this.fetchQrCode();
        }
    }
}
</script>