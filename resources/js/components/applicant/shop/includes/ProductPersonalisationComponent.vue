<template>
  <div
    class="col-lg-4 mb-5 pr-md-0 product-personalisation-container"
    v-if="product"
  >
    <div class="row">
      <div class="col-12">
        <p class="h7 d-none d-md-block">Details</p>
        <p class="text-size-tiny" v-html="cleanedDescription"></p>
      </div>
    </div>
    <div class="row">
      <div class="col-12" v-if="productTextFields.length > 0">
        <p class="mb-5 heading-h6">Your chosen text</p>
        <v-form ref="form" v-model="valid">
          <div v-for="(details, index) in productTextFields" :key="details.id">
            <v-text-field
              :ref="index"
              class="pt-2"
              v-model="details.text"
              :label="getLineLabel(index, details.type)"
              :counter="details.letterCount"
              @keyup="
                addToCart();
                setIndex(customTextIndex);
                setFilterOnText(details.type);
              "
              :rules="[
                () =>
                  details.text.length <= details.letterCount ||
                  'This line must be less than ' +
                    details.letterCount +
                    ' characters',
              ]"
              :maxlength="details.letterCount"
              :name="'text-' + index"
            >
            </v-text-field>
          </div>
        </v-form>
        <p class="heading-h6 mb-0" v-if="fontFaces.length">Select a Font</p>
        <div class="row" v-if="fontFaces.length">
          <div class="col-12 pb-0 pt-2">
            <v-radio-group v-model="selectedFontFace">
              <v-radio
                v-for="font in fontFaces"
                :key="font.id"
                :value="font"
                @click="
                  updateFontFace(font.name);
                  addToCart();
                "
              >
                <template v-slot:label>
                  <div>
                    <span :style="{ 'font-family': font.name }"
                      >Sample text</span
                    >
                    <span class="font-name-info">{{ font.name }}</span>
                  </div>
                </template>
              </v-radio>
            </v-radio-group>
          </div>
        </div>
        <v-divider class="mt-2 mb-2" v-if="fontFaces.length"></v-divider>
        <p class="heading-h6" v-if="product.text_colours.length > 0">
          Select memorial engraving text colour
        </p>
        <div class="row mb-5" v-if="product.text_colours.length > 0">
          <div
            class="col-4"
            v-for="colour in product.text_colours"
            :key="colour.id"
          >
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <div
                  v-bind="attrs"
                  v-on="on"
                  :style="{ backgroundColor: colour.colour }"
                  :class="
                    colour !== null &&
                    fontColour !== null &&
                    colour.name === fontColour.name
                      ? 'material selected-item'
                      : 'material'
                  "
                  @click="
                    updateColour(colour);
                    addToCart();
                  "
                ></div>
              </template>
              <span>{{ colour.name }}</span>
            </v-tooltip>
          </div>
        </div>
        <v-divider
          class="mt-5 mb-2"
          v-if="product.text_colours.length > 0"
        ></v-divider>
      </div>
    </div>
    <div class="row mt-0 mb-5" v-if="visibleProductMaterials.length > 0">
      <div class="col-12 pt-0">
        <p class="heading-h6 mt-0 mb-2">Material</p>
        <div class="row">
          <div
            class="col-3"
            v-for="material in visibleProductMaterials"
            :key="material.id"
            @click="addToCart(), setMaterial(material)"
          >
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <img
                  :src="material.imageurl"
                  v-bind="attrs"
                  v-on="on"
                  :class="
                    material !== null &&
                    iSelectedMaterial !== null &&
                    material.id === iSelectedMaterial.id
                      ? 'material selected-item'
                      : 'material'
                  "
                  alt=""
                />
              </template>
              <span>{{ material.type }}</span>
            </v-tooltip>
          </div>
        </div>
      </div>
    </div>
    <v-divider v-if="visibleProductMaterials.length > 0"></v-divider>
    <div
      v-for="attribute_type in product.attribute_types"
      :key="attribute_type.id"
      class=""
    >
      <div
        class="row mb-5"
        v-if="attribute_type.visible == 1 && attribute_type.name"
      >
        <div class="col-12">
          <p class="heading-h6">{{ attribute_type.name }}</p>
          <v-radio-group class="mb-0" row mandatory>
            <v-radio
              v-for="(attribute, i) in attribute_type.attributes"
              :key="attribute.id"
              :label="attribute.name"
              :value="attribute"
              @click="
                setAttribute(
                  attribute,
                  i,
                  attribute_type.name,
                  attribute_type.id
                ),
                  addToCart()
              "
              v-bind:selected="i === 0"
              v-if="attribute.visible == 1"
            ></v-radio>
          </v-radio-group>
        </div>
        <v-divider></v-divider>
      </div>
    </div>
    <div class="row" style="margin-bottom: 20px; margin-top: 35px">
      <div
        class="col-12"
        v-if="allowsCustom || (allowsPredefined && allImages.length > 0)"
      >
        <p class="heading-h6">Additional image (optional)</p>
        <p>Add a photo or an image to personalise your memorial further.</p>
        <v-btn
          rounded
          class="btn btn-upload mb-2"
          @click="launchAdditionalImageDialogue('custom')"
          block
          v-if="allowsCustom"
        >
          Upload Own Image
        </v-btn>
        <p
          class="heading-h6 text-center pt-3"
          v-if="allowsPredefined && allowsCustom && allImages.length > 0"
        >
          OR
        </p>
        <v-btn
          rounded
          class="btn btn-choose"
          @click="launchAdditionalImageDialogue('predefined')"
          block
          v-if="allowsPredefined && allImages.length > 0"
        >
          Choose From Our Selection
        </v-btn>
      </div>
    </div>
    <v-divider
      v-if="allowsCustom || (allowsPredefined && allImages.length > 0)"
      class="mt-0 mb-5"
    ></v-divider>
    <div class="mt-5">
      <div v-if="!product.POA" >
        <p class="heading-h6">Buy now</p>
        <p>
          {{ buy_now_copy ? buy_now_copy : "When you're happy with your personalised memorial design, simply place your order. Once received, we will be in touch as soon as possible. Alternatively, if you would like to confirm the exact placement of your memorial personalisation or if you have any special requests, you can call us on after placing your order." }}
        </p>
        <v-tooltip bottom :disabled="valid">
          <template v-slot:activator="{ on, attrs }">
            <span v-bind="attrs" v-on="on" class="btn-tootlip-container">
              <v-btn
                name="btnBuyNow"
                class="btn"
                rounded
                :disabled="!valid"
                block
                :color="styles.primary_colour"
                :style="{ color: styles.secondary_colour }"
                @click="addToCart(), showPaymentOptions(), scrollToTop()"
              >
                Buy now
              </v-btn></span
            >
          </template>
          <span>To continue please make changes to Line {{ invalid }}</span>
        </v-tooltip>
        <v-divider style="margin-top: 50px"></v-divider>
      </div>
      <br />
      <p class="heading-h6">Share with friends and family</p>
      <p>
        {{ share_friends_family_copy ? share_friends_family_copy : "You can email or download a copy of your bespoke memorial to share with friends and family." }}
      </p>
      <v-btn
        rounded
        block
        :color="styles.primary_colour"
        :style="{ color: styles.primary_colour }"
        @click="sendEmailDialogue('other')"
        outlined
        class="btn-outline btn-share-family"
      >
        Email</v-btn
      >
      <br />
      <v-divider></v-divider>
      <p class="heading-h6">Share with our team</p>
      <p>
        {{ share_team_copy ? share_team_copy : "Share a copy of your personalised memorial and one of our team will be in touch to discuss your design in more detail. Or, if you want to discuss additional personalisation options, please contact us on " }}
        {{ phonenumber }}.
      </p>
      <v-btn
        rounded
        block
        :color="styles.primary_colour"
        :style="{ color: styles.primary_colour }"
        @click="sendEmailDialogue('crematorium')"
        outlined
        class="btn-outline btn-share-crem"
      >
        Share with crematorium</v-btn
      >
      <br />
      <v-btn
        rounded
        block
        :color="styles.primary_colour"
        :style="{ color: styles.primary_colour }"
        outlined
        @click="generateReport()"
        class="btn-outline"
      >
        Download PDF</v-btn
      >
    </div>
    <AdditionalImageComponent
      v-on:closeForm="closeForm($event)"
      v-bind:additionalImageDialogue="additionalImageDialogue"
      v-bind:customShape="customShape"
      v-bind:customShapeScale="customShapeScale"
      v-bind:layoutType="layoutType"
    />
    <UploadCustomImageComponent
      v-on:closeForm="closeForm($event)"
      v-bind:uploadCustomImageDialogue="uploadCustomImageDialogue"
      v-bind:layoutType="layoutType"
    />
    <PredefinedImageComponent
      v-on:closeForm="closeForm($event)"
      v-bind:predfinedImageDialogue="predfinedImageDialogue"
      v-bind:customShape="customShape"
      v-bind:customShapeScale="customShapeScale"
      v-bind:layoutType="layoutType"
      v-bind:allImages="allImages"
    />
    <ProductPdfComponent
      :product="product"
      :productTextFields="productTextFields"
      :editedImage="editedImage"
      :totalPrice="totalPrice"
      ref="productPdfComponent"
    />
  </div>
</template>
<script>
import { bus } from "../../../../app";
import { getCodeFromParams } from "../../../../deceased";
import AdditionalImageComponent from "../modals/AdditionalImageComponent";
import PredefinedImageComponent from "../modals/PredefinedImageComponent";
import UploadCustomImageComponent from "../modals/UploadCustomImageComponent.vue";
import ProductPdfComponent from "./ProductPdfComponent.vue";
import moment from "moment";
import removeWordStyling from "../../../../removeStyling";
const filter = require("leo-profanity");
export default {
  props: [
    "product",
    "customImageDetails",
    "chosenProductSpecs",
    "attribute_types",
    "shareText",
    "buyNowText",
    "customTextDetails",
    "productTextFields",
    "editedImage",
    "productMaterials",
    "customShape",
    "customShapeScale",
    "totalPrice",
    "fontColour",
    "selectedMaterial",
  ],
  data() {
    return {
      dateOfBirthMenu: false,
      dateOfDeathMenu: false,
      textDetails: [],
      customTextIndex: 0,
      additionalImageDialogue: false,
      predfinedImageDialogue: false,
      fontFaces: [],
      selectedFontFace: "Smooch",
      productSpecs: null,
      layoutType: null,
      date_of_birth: "",
      date_of_death: "",
      dateFormat: "dddd, MMMM Do YYYY",
      phonenumber: null,
      invalid: false,
      valid: true,
      allowsPredefined: false,
      allowsCustom: false,
      allImages: [],
      uploadCustomImageDialogue: false,
      iSelectedMaterial: null,
      buy_now_copy: '',
      share_friends_family_copy: '',
      share_team_copy: ''
    };
  },
  components: {
    AdditionalImageComponent,
    PredefinedImageComponent,
    UploadCustomImageComponent,
    ProductPdfComponent,
  },
  methods: {
    getContactDetails() {
      axios
        .get(`/api/contactdetails/${getCodeFromParams(this.$route.params)}`)
        .then((res) => {
          this.validate();
          this.email = res.data.primary_contact_email;
          this.phonenumber = res.data.phone;
        })
        .catch((res) => {
          this.requestSending = false;
        });
    },
    getPredefinedImages() {
      axios
        .get(`/api/getPredefinedImages/${this.$route.params.productid}`)
        .then((res) => {
          this.allImages = res.data;
        })
        .catch((err) => {
          this.snackbar = true;
          this.snackbarText = "Error fetching products";
        });
    },
    closeForm() {
      this.additionalImageDialogue = false;
      this.predfinedImageDialogue = false;
      this.uploadCustomImageDialogue = false;
    },
    generateReport() {
      this.$forceUpdate();
      this.$refs.productPdfComponent.generatePdf(this.chosenProductSpecs);
    },
    setIndex(customTextIndex) {
      this.$emit("setCustomTextIndex", customTextIndex);
    },
    updateColour(colour) {
      bus.$emit("updateColour", colour);
    },
    updateFontFace(font) {
      bus.$emit("updateFontFace", { font: font });
    },
    setAttribute(attribute, index, type, type_id) {
      this.$emit("setAttribute", {
        attribute: attribute,
        index: index,
        type: type,
        type_id: type_id,
      });
    },
    setMaterial(material) {
      this.iSelectedMaterial = material;
      bus.$emit("setMaterial", material);
    },
    addToCart() {
      this.$emit("addToCart");
    },
    showPaymentOptions() {
      this.$emit("showPaymentOptions");
    },
    scrollToTop() {
      window.scrollTo(0, 0);
    },
    sendEmailDialogue(recipient) {
      this.$emit("sendEmailDialogue", recipient);
    },
    launchAdditionalImageDialogue(type) {
      this.layoutType = type;
      if (type === "custom") {
        this.uploadCustomImageDialogue = true;
      } else {
        this.predfinedImageDialogue = true;
      }
    },
    setFilterOnText(type) {
      const filteredText = filter.clean(
        this.productTextFields.find((element) => element.type === type).text
      );
      this.productTextFields.find((element) => element.type === type).text =
        filteredText;
    },
    validate() {
      if (this.$refs.form) {
        this.$refs.form.validate();
        for (let i = 0; i < this.productTextFields.length; i++) {
          if (!this.$refs[i][0]._data.valid) {
            this.invalid = i + 1;
            break;
          }
        }
      }
    },
    getLineLabel(index, detailsType) {
      return `Line ${index + 1}`;
    },
    getSettings() {
      axios
        .get(`/api/fetchSettings`)
        .then((res) => {
            this.buy_now_copy = res.data.buy_now_copy.value,
            this.share_friends_family_copy = res.data.share_friends_family.value,
            this.share_team_copy = res.data.share_team.value
        });
    },
  },
  computed: {
    deceased() {
      return this.$store.state.deceased;
    },
    styles() {
      return this.$store.state.styles;
    },
    computedDateOfBirthFormattedMomentjs() {
      this.date_of_birth = this.productTextFields.find(
        (element) => element.type === "date of birth"
      ).text;
      return moment(this.date_of_birth).format(this.product.date_layout);
    },
    computedDateOfDeathFormattedMomentjs() {
      this.date_of_death = this.productTextFields.find(
        (element) => element.type === "date of death"
      ).text;
      return moment(this.date_of_death).format(this.product.date_layout);
    },
    visibleProductMaterials() {
      if (this.product.materials) {
        return this.product.materials.filter((m) => m.visible === 1);
      }
      return;
    },
    cleanedDescription() {
      return removeWordStyling(this.product.description);
    },
  },
  created() {
    this.getPredefinedImages();
    this.getContactDetails();
    bus.$off("updateCustomTextIndex");
    bus.$off("allowsPredefined");
    bus.$off("allowsCustom");
    bus.$off("additionalImageDialogue");
  },
  mounted() {
    bus.$on("updateCustomTextIndex", (index) => {
      this.customTextIndex = index;
    });
    bus.$on("allowsPredefined", (data) => {
      this.allowsPredefined = data;
    });
    bus.$on("allowsCustom", (data) => {
      this.allowsCustom = data;
    });
    bus.$on("additionalImageDialogue", (data) => {
      this.additionalImageDialogue = data;
    });
    this.getSettings();
  },
  beforeMount() {
    this.$nextTick(() => {
      const font = this.fontFaces[0]; // TODO:: currenlt this will always select the first font.
      this.selectedFontFace = font;
      this.updateFontFace(font);
    });
  },
  watch: {
    customTextDetails: {
      handler: function (propVal) {
        this.$nextTick(() => {
          if (propVal.length > 0) {
            this.textDetails = propVal[0].innerTextArray;
          }
          this.addToCart();
        });
      },
      deep: true,
    },
    chosenProductSpecs: function (propVal) {
      this.productSpecs = propVal;
    },
    productMaterials: function (propVal) {
      this.iSelectedMaterial = propVal[0];
    },
    product: function () {
      if (this.product.text_colours.length) {
        bus.$emit("setInitialColour", {
          colour: this.product.text_colours[0].colour,
        });
      }
      this.fontFaces = this.product.fonts;
    },
    productTextFields: {
      handler: function () {
        bus.$emit("updateImage");
        this.$nextTick(() => {
          this.validate();
        });
      },
      deep: true,
    },
  },
};
</script>
<style scoped>
.footer {
  position: absolute;
  display: flex;
  align-items: center;
  justify-content: center;

  bottom: 0px;
  left: 0px;
  right: 0px;
  text-align: center;
  color: #004c3b;
  font-family: "Soleil";
}

.footer .powered-by {
  display: inline-block;
  margin-right: 5px;
}
.footer span.brand {
  display: inline-block;
  width: 72px;
  height: 21px;
  background-image: url(/images/obitus.png);
  background-repeat: no-repeat;
  background-size: contain;
}
.footer span.brand span {
  display: none;
}

.pdf-page {
  height: 1110px;
  position: relative;
  overflow: hidden;
}
.btn-tootlip-container {
  padding: 12px 0px !important;
}
</style>
