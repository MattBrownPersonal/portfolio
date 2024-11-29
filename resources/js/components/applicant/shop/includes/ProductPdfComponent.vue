<template>
  
  <vue-html2pdf 
    :show-layout="false" 
    :enable-download="true" 
    :preview-modal="true"
    :paginate-elements-by-height="1400" 
    filename="Memorialisation Design" 
    :pdf-quality="2"
    :html-to-pdf-options="{ 
      margin: 0, 
      filename:'Memorial Design.pdf', 
      image: { type: 'jpeg', quality: 0.98 },
        enableLinks: false, 
        html2canvas: { scale: 1, useCORS: true }, 
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' } 
      }"
    :manual-pagination="false" 
    pdf-format="a4" 
    pdf-orientation="portrait" 
    pdf-content-width="800px"
    ref="html2Pdf">
    <section class="pdf-page" slot="pdf-content">
        <div class="row pdf-header-banner">
            <div class="col-12 text-center">
                <p class="pdf-header-text w-100">Here is your personalised design on your chosen memorial.</p>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-10 offset-1 pt-5 px-0">
                    <img v-if="editedImage" :src="editedImage" class="w-100" style="border-radius: 10px;">
                </div>
            </div>
            <div class="row">
                <div class="col-10 offset-1 px-0">
                    <p class="text-size-tiny">
                        You can revisit your personalised memorial page at any time to make further changes to your design.
                    </p>
                    <div class="row pt-4">
                        <div class="col-6 pe-2">
                            <div class="row">
                                <div class="col-12">
                                    <span class="heading-h6">Custom text</span>
                                </div>
                            </div>
                            <div v-for=" (details, index)  in  productTextFields " :key=" details.id " v-if="details.text">
                                <div class="row pt-0 pb-0 mt-4 mb-2 pb-2 pt-2" style="border-bottom: solid 1px rgba(0,0,0,0.1); margin-right: 10px !important">
                                    <div class="col-3 pt-0 pb-0" style="">Line: {{ index + 1 }}: </div>
                                    <div class="col-9 pt-0 pb-0">{{ details.text }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 pb-2">
                            <div class="row">
                                <div class="col-12">
                                    <span class="heading-h6">Further Info</span>
                                </div>
                            </div>
                            <div class="row pt-0 pb-0 mt-4 mb-2 pb-2 pt-2" style="border-bottom: solid 1px rgba(0,0,0,0.1)">
                                <div class="col-3 pt-0 pb-0" >Details:</div>
                                <div class="col-9 pt-0 pb-0">  
                                  <span v-for="[attribKey, attribute] in productSpecs" :key="attribKey" v-if="attribute">
                                    {{ attribute.type_name }}:{{ attribute.name }}
                                  </span>                  
                                </div>
                            </div>
                            <div class="row pt-0 pb-0 mt-4 mb-2 pb-2 pt-2" style="border-bottom: solid 1px rgba(0,0,0,0.1)">
                                <div class="col-3 pt-0 pb-0" >Product: </div>
                                <div class="col-9 pt-0 pb-0">{{ product.name }}</div>
                            </div>
                            <div class="row pt-0 pb-0 mt-4 mb-2 pb-2 pt-2" style="border-bottom: solid 1px rgba(0,0,0,0.1)">
                                <div class="col-3 pt-0 pb-0">Price: </div>
                                <div class="col-9 pt-0 pb-0" v-if="!product.POA">£{{ totalPrice }}</div>
                                <div class="col-9 pt-0 pb-0" v-else>Price on Application</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</vue-html2pdf>
</template>
<script>
import VueHtml2pdf from 'vue-html2pdf';
export default { 
  props: ['editedImage','productTextFields','product','totalPrice',],
  data() {
    return {
        productSpecs: new Map(),
    }
  },
  components: {
    VueHtml2pdf
  },
  methods: {
    generatePdf(chosenProductSpecs) {
      this.productSpecs = chosenProductSpecs;
      this.$refs.html2Pdf.generatePdf()
    },
  },
};
</script>
<style scoped>
.pdf-page{
  height: 1110px;
  position: relative;
  overflow:hidden;
}
</style>