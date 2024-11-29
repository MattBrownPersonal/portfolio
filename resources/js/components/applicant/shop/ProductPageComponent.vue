<template>
  <div ref="innerHTML">
    <div class="container pb-5">
      <TopMenuComponent
        v-on:launchCallbackDialogue="launchCallbackDialogue($event)"
        v-bind:items="items"
      />
      <div class="row" v-if="product">
        <div class="col-12 m-3">
          <p class="h7 mb-2">{{ product.name }}</p>
          <h5 v-if="!product.POA" class="primary-colour">
            Total: £{{ totalPrice }}
          </h5>
          <h5 v-else class="primary-colour">Price on application</h5>
        </div>
      </div>
    </div>
    <div class="row pb-5 white">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="row pt-5 main-image-inner">
              <CustomImageComponent
                v-bind:customImageDetails="customImageDetails"
                v-bind:product="product"
                v-on:saveImage="saveImage($event)"
                v-bind:showPayment="showPayment"
                v-bind:chosenProductSpecs="chosenProductSpecs"
                v-bind:totalPrice="totalPrice"
                v-bind:productImages="productImages"
                v-bind:fontColour="fontColour"
                v-bind:customTextDetails="customTextDetails"
                v-bind:productTextFields="productTextFields"
                v-bind:perspectiveDetails="perspectiveDetails"
                v-bind:shape="shape"
                v-bind:selectedMaterial="selectedMaterial"
                v-bind:layoutType="layoutType"
              />
              <KeepAlive>
                <ProductPersonalisationComponent
                  v-if="showPayment == false"
                  v-bind:product="product"
                  v-bind:buyNowText="buyNowText"
                  v-bind:customShape="shape"
                  v-bind:customShapeScale="shapeScale"
                  v-bind:shareText="shareText"
                  v-bind:styles="styles"
                  v-bind:editedImage="editedImage"
                  v-bind:customTextDetails="customTextDetails"
                  v-bind:productMaterials="productMaterials"
                  v-bind:productTextFields="productTextFields"
                  v-bind:customImageDetails="customImageDetails"
                  v-bind:chosenProductSpecs="chosenProductSpecs"
                  v-bind:attribute_types="attribute_types"
                  v-bind:totalPrice="totalPrice"
                  v-bind:fontColour="fontColour"
                  v-bind:selectedMaterial="selectedMaterial"
                  v-on:setMaterial="setMaterial($event)"
                  v-on:setAttribute="setAttribute($event)"
                  v-on:addToCart="addToCart($event)"
                  v-on:showPaymentOptions="showPaymentOptions()"
                  v-on:sendEmailDialogue="sendEmailDialogue($event)"
                  v-on:updateColour="updateColour($event)"
                  v-on:saveImage="saveImage($event)"
                />
                <PaymentComponent
                  v-else
                  v-bind:chosenProductSpecs="chosenProductSpecs"
                  v-bind:customImageDetails="customImageDetails"
                  v-bind:product="product"
                  v-bind:totalPrice="totalPrice"
                  v-on:back="showPayment = false"
                />
              </KeepAlive>
            </div>
          </div>
        </div>
      </div>
    </div>

    <v-dialog
      v-model="messageSendFailed"
      max-width="474px"
      @click:outside="closeErrorForm"
      class="error-modal"
    >
      <v-card class="text-center pb-5">
        <v-card-text class="contact-us-main">
          <div class="row">
            <div class="col-1 px-0 py-0 offset-11 text-center">
              <br />
            </div>
          </div>

          <div class="text-center mt-5">
            <span class="mdi md-100"></span>
            <h4 style="color: rgb(173, 49, 49)">Email Failed To Send</h4>
            <p>
              We are sorry, but you message was not able to be sent at this
              time<br />
              Please try again later.
            </p>
            <div>
              <v-btn
                block
                :color="styles.primary_colour"
                :style="{ color: styles.secondary_colour }"
                @click="closeErrorForm()"
              >
                Close</v-btn
              >
            </div>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="messageSendSuccess"
      max-width="474px"
      @click:outside="closeSuccessForm"
      class="email-sent-modal"
    >
      <v-card class="text-center pb-5">
        <v-card-text class="contact-us-main">
          <div class="row">
            <div class="col-1 px-0 py-0 offset-11 text-center">
              <br />
            </div>
          </div>

          <div class="text-center mt-5">
            <span class="mdi md-100"></span>
            <h4 style="">Email Sent Successfully</h4>
            <p>
             Thank you <br>
              Your message has been sent
            </p>
            <div>
              <v-btn
                block
                :color="styles.primary_colour"
                :style="{ color: styles.secondary_colour }"
                @click="closeSuccessForm()"
              >
                Close</v-btn
              >
            </div>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
    <div class="container featured-products-container">
      <div class="row">
        <div class="col-12">
          <div class="row mt-5" v-if="featured != null">
            <div class="col-8">
              <p class="h7 my-0 text-begin">Featured products</p>
            </div>
            <div
              class="col-4 text-end text-end d-flex justify-content-end align-items-center"
            >
              <span class="primary-colour pr-2">See all</span>
              <v-btn
                fab
                x-small
                :to="{
                  name: 'categories',
                }"
              >
                <v-icon class="primary-colour">mdi-arrow-right</v-icon>
              </v-btn>
            </div>
          </div>
          <div class="row rowGap" v-if="featured != null">
            <div
              class="col-12 col-md-4"
              v-for="product in featured"
              :key="product.id"
            >
              <div v-if="product.id === currentProduct.id">
                <div class="row">
                  <div class="col-12">
                    <v-img
                      v-if="product.thumbnail_image"
                      :alt="'this is the product you are on'"
                      :src="product.thumbnail_image"
                      class="thumbnail-image"
                      @click="scrollToTop"
                    ></v-img>
                  </div>
                </div>
                <div class="row mt-0">
                  <div class="col-12 pt-5">
                    <h4>{{ product.name }}</h4>
                    <p
                      v-if="product.short_description"
                      class="text-size-tiny text-justify font-weight-normal featured-description"
                      v-html="removeWordStyling(product.short_description)"
                    >
                    </p>
                    <p
                      v-else-if="product.description"
                      class="text-size-tiny text-justify font-weight-normal featured-description"
                      v-html="removeWordStyling(product.description)"
                    >
                    </p>
                    <button
                      class="primary-colour product-link"
                      @click="scrollToTop"
                    >
                      View Product
                    </button>
                  </div>
                </div>
              </div>
              <div v-else>
                <router-link
                  :to="{
                    name: 'singleproductpage',
                    params: { productid: product.id },
                  }"
                >
                  <div class="row">
                    <div class="col-12">
                      <v-img
                        v-if="product.thumbnail_image"
                        :src="product.thumbnail_image"
                        class="thumbnail-image"
                      ></v-img>
                    </div>
                  </div>
                </router-link>
                <div class="row">
                  <div class="col-12 pt-5">
                    <h4>{{ product.name }}</h4>
                    <p
                      v-if="product.short_description"
                      class="text-size-tiny text-justify font-weight-normal featured-description"
                      v-html="removeWordStyling(product.short_description)"
                    >
                    </p>
                    <p
                      v-else-if="product.description"
                      class="text-size-tiny text-justify font-weight-normal featured-description"
                      v-html="removeWordStyling(product.description)"
                      >
                    </p>
                    <router-link
                      class="primary-colour product-link"
                      :to="{
                        name: 'singleproductpage',
                        params: { productid: product.id },
                      }"
                    >
                      View Product</router-link
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <SendToVenueComponent
      v-on:closeForm="closeForm($event)"
      v-on:emailVenue="emailVenue($event)"
      v-bind:sendToVenueDialogue="sendToVenueDialogue"
      v-bind:editedImage="editedImage"
      v-bind:chosenProductSpecs="chosenProductSpecs"
      v-bind:customImageDetails="customImageDetails"
      v-bind:product="product"
      v-bind:recipient="recipient"
      v-bind:productTextFields="productTextFields"
    />
    <CallbackModalComponent
      v-on:closeForm="closeForm($event)"
      v-bind:callbackDialogue="callbackDialogue"
    />
    <CompletedPaymentModal
      v-on:closeForm="closeForm($event)"
      v-bind:completedPaymentDialogue="completedPaymentDialogue"
      v-bind:orderNumber="orderNumber"
      v-bind:nextSteps="nextSteps"
    />
  </div>
</template>
<script>
import CallbackModalComponent from './modals/CallbackModalComponent';
import CompletedPaymentModal from './modals/CompletedPaymentModalComponent';
import SendToVenueComponent from './modals/SendToVenueModalComponent';
import CustomImageComponent from './includes/CustomImageComponent';
import ProductPersonalisationComponent from './includes/ProductPersonalisationComponent';
import PaymentComponent from './includes/PaymentComponent';
import TopMenuComponent from './includes/TopMenuComponent';
import removeWordStyling from '../../../removeStyling';
import moment from 'moment';
import { bus } from '../../../app';
import axios from 'axios';
import { getCodeFromParams } from '../../../deceased';

export default {
  components: {
    CallbackModalComponent,
    CustomImageComponent,
    SendToVenueComponent,
    CompletedPaymentModal,
    ProductPersonalisationComponent,
    PaymentComponent,
    TopMenuComponent,
  },
  data() {
    return {
      productImages: null,
      buyNowText: null,
      shareText: null,
      categoryId: null,
      showPayment: false,
      product: null,
      lines: [],
      primaryColor: '#0095a6',
      secondaryColor: '#ffffff',
      requestSent: false,
      callbackDialogue: false,
      image: [],
      customImageDetails: [],
      chosenProductSpecs: new Map(),
      chosenSize: null,
      editedImage: null,
      paymentDialogue: false,
      completedPaymentDialogue: false,
      totalPrice: null,
      sendToVenueDialogue: false,
      recipient: null,
      venueRecipient: '',
      orderNumber: null,
      categoryId: null,
      nextSteps: null,
      fontColour: null,
      customTextDetails: [],
      productTextFields: [],
      shape: {
        x: 0,
        y: 0,
        width: 60,
        height: 100,
        rotation: 2,
        type: null,
      },
      shapeScale: null,
      perspectiveDetails: [],
      selectedCustomerText: [],
      selectedMaterial: null,
      selectedFontFace: null,
      productMaterials: [],
      attributes: [],
      attribute_types: [],
      layoutType: 'none',
      code: null,
      MemName: null,
      featured: null,
      messageSendFailed: false,
      messageSendSuccess: false,
      currentSlide: null,
    };
  },
  methods: {
    removeWordStyling(text) {
      return removeWordStyling(text);
    },
    closeForm(state) {
      this.callbackDialogue = state;
      this.sendToVenueDialogue = state;
      this.paymentDialogue = state;
    },
    launchCallbackDialogue(state) {
      this.callbackDialogue = state;
    },
    showPaymentOptions() {
      this.showPayment = true;
    },
    scrollToTop() {
      window.scrollTo(0, 0);
    },
    updateText(index) {
      for (let i = 0; i < this.customTextDetails.length; i++) {
        Vue.set(this.customTextDetails, i, this.customTextDetails[index]);
      }
    },
    getProductMaterialTypes() {
      if (this.product && this.product.materials && this.product.materials.length > 0) {
        this.selectedMaterial = this.product.materials[0];
      } else {
        this.selectedMaterial = null;
      }
    },
    addToCart() {
      if (!this.product) return;

      localStorage.setItem(
        'chosenProductSpecs',
        JSON.stringify(Object.fromEntries(this.chosenProductSpecs))
      );
      localStorage.setItem('lines', JSON.stringify(this.customTextDetails));
      localStorage.setItem('product', JSON.stringify(this.product));
      localStorage.setItem('totalPrice', this.totalPrice);
      localStorage.setItem('font-colour', JSON.stringify(this.fontColour));
      localStorage.setItem('orderSet', 1);
      if (this.productTextFields.length > 0) {
        localStorage.setItem(
          'customText',
          JSON.stringify(this.productTextFields)
        );
      }

      localStorage.setItem('code', this.code);
      localStorage.setItem('deceased', JSON.stringify(this.deceased));
      localStorage.setItem('site-name', this.product.site.slug);
      this.editedImage = localStorage.getItem('editedImage');
    },
    addToRecentlyViewed() {
      let recentlyViewed = [];
      recentlyViewed = JSON.parse(localStorage.getItem('recentlyViewed')) || [];
      if (recentlyViewed.find((x) => x.id === this.product.id)) {
        return;
      } else {
        recentlyViewed.push(this.product);
        localStorage.setItem('recentlyViewed', JSON.stringify(recentlyViewed));
      }
    },
    sendEmailDialogue($recipient) {
      this.editedImage = localStorage.getItem('editedImage');
      this.sendToVenueDialogue = true;
      this.recipient = $recipient;
    },
    saveImage($event) {
      localStorage.setItem('editedImage', $event);
      this.editedImage = $event;
    },
    setUpInitialAttributes() {
      this.product.attribute_types.forEach((element) => {
        element.attributes.forEach((attribute) => {
          attribute.type_name = element.name;
        });
        if (element.attributes.length > 0 && element.visible) {
          this.chosenProductSpecs.set(
            element.id,
            element.attributes.find((element) => element.visible === 1)
          );
        }
      });
      if (this.product.text_colours[0]) {
        this.chosenProductSpecs.set('textColour', {
          id: null,
          name: this.product.text_colours[0].name,
          price: null,
          type_name: 'Text colour',
          item_type: 'fontColour'
        });
      }
      this.chosenProductSpecs.set('material', {
        id: null,
        name: this.product.materials.find((element) => element.visible === 1)
          .type,
        price: this.product.materials.find((element) => element.visible === 1)
          .price,
        type_name: 'Material',
        item_type: 'material'
      });
    },
    setAttribute(event) {
      this.chosenProductSpecs.set(event.type_id, event.attribute);
      this.addToCart();
      this.calculateTotalPrice();
    },
    calculateTotalPrice() {
      if (this.chosenProductSpecs.size > 0) {
        const calculatedPrice = [];
        this.chosenProductSpecs.forEach((element) => {
          if (element?.price) {
            calculatedPrice.push(parseFloat(element.price));
          }
        });
        if (calculatedPrice.length > 0) {
          this.totalPrice = parseFloat(
            calculatedPrice.reduce((a, b) => a + b) +
              this.product.price
          ).toFixed(2);
        } else {
          this.totalPrice = parseFloat(
            this.product.price
          ).toFixed(2);
        }
      } else {
        this.totalPrice = parseFloat(
          this.product.price
        ).toFixed(2);
      }
      localStorage.setItem('totalPrice', this.totalPrice);
    },
    getDeceased() {
      axios
        .get('api/deceased', { params: { id: this.$route.params.code } })
        .then((res) => {
          this.fetchProduct();
        })
        .catch((error) => {
          this.snackbar = true;
          this.snackbarText = error;
        });
    },
    fetchAttributes() {
      axios
        .get(`/api/getAttributes/${this.product.site_id}/${this.product.id}`)
        .then((res) => {
          this.attribute_types = res.data;
          this.setUpInitialAttributes();
          this.calculateTotalPrice();
        })
        .catch((error) => {
          this.snackbar = true;
          this.snackbarText = error;
        });
    },
    fetchProduct() {
      const productId = this.$route.params.productid;
      this.purgeLocalStorageOfOldProduct(productId);
      axios.get(`/api/product/${productId}`).then((res) => {
        this.product = res.data;
        this.productImages = res.data.images;
        this.addToRecentlyViewed(); // sync
        this.getProductMaterialTypes(); // sync
        this.getCoppedImageLocation(); // sync

        this.getCustomImageDetails(); // async
        this.getCustomTextFields(); //  async
        this.fetchAttributes(); //  async
        this.getCustomSiteText(); // async
        this.fetchFeaturedProducts();
        this.fetchMemorialisationName();

        if (this.product.text_colours.length > 0) {
          this.fontColour = this.product.text_colours[0];
        } else {
          this.fontColour = { colour: '#fff', name: 'default' };
        }
        this.getSavedData('material');
        this.getSavedData('font-colour');
        this.getSavedData('font-selection');
        this.getSavedData('layoutType');
        this.getSavedData('chosenProductSpecs');
        this.calculateTotalPrice();
        this.addToCart();
      });
    },
    getCoppedImageLocation() {
      let shapeDetails = [];
      this.product.custom_images.forEach((element) => {
        if (element.additional_image) {
          shapeDetails.push(element.additional_image);
        }
      });
      if (shapeDetails.length > 0) {
        this.shape = shapeDetails;
        this.shapeScale = this.shape[0].height / this.shape[0].width;
        localStorage.setItem('shape', JSON.stringify(this.shape));
        localStorage.setItem('shapeScale', JSON.stringify(this.shapeScale));
      }
    },

    getCustomTextFields() {
      let self = this;
      axios.get(`/api/fetchSiteLineTypes/${self.product.id}`).then((res) => {
        self.productTextFields = [];
        res.data.forEach((element) => {
          let text = null;
          if (self.deceased != null) {
            switch(element.line_types) {
              case 'firstname':
                text = self.deceased.firstname 
                break;
              case 'middlename':
                text = self.deceased.middlename;
                break;
              case 'lastname':
                text = self.deceased.lastname;
                break;
              case 'date of birth':
                text = moment(self.deceased.date_of_birth).locale('en-gb').format('L') || '';
                break;
              case 'date of death':
                text = moment(self.deceased.date_of_death).locale('en-gb').format('L') || '' ;
                break;
              case 'fullname':
                text = self.formatDeceasedName(self.deceased);
                break;
            }
          }
          if (element.is_custom === 1) {
            text = element.custom_message_text;
          } 
          if (element.custom_text_field[0]) {
            self.productTextFields.push({
              letterCount: element.custom_text_field[0].letterCount,
              text: text ?? '',
              type: element.line_types,
              order_index: element.order_index,
            });
          }
        });
        self.productTextFields.sort((a, b) => a.order_index - b.order_index);
        if (!self.deceased) {
          self.productTextFields[0].text = 'Your loved one';
        }
        self.getSavedData('text');
        if (self.currentSlide) {
          self.updateProductTextFieldsFromSlide(self.currentSlide);
          self.currentSlide = null;
        }
      });
    },
    getCustomImageDetails() {
      axios.get(`/api/customImageDetails/${this.product.id}`).then((res) => {
        this.customImageDetails = res.data;
        this.customTextDetails = [];
        res.data.forEach((element) => {
          let innerTextArray = [];
          this.perspectiveDetails.push(
            element.custom_image_perspective_details
          );
          element.custom_text_fields.forEach((el) => {
            if (el.removed === 0) {
              innerTextArray.push({
                type: el.type,
                left: el.rectangleX,
                top: el.rectangleY,
                rectangleHeight: el.rectangleHeight,
                rectangleWidth: el.rectangleWidth,
                fontSize: el.fontSize,
                angle: el.angle,
                radius: el.radius,
                centerRotation: el.centerRotation,
                arc: el.arc,
                letterCount: el.letterCount,
                lineType: el.lineType,
                rectangleTextScale: el.rectangleTextScale,
              });
              localStorage.setItem(
                'customSavedLines',
                JSON.stringify(innerTextArray)
              );
            }
          });
          this.customTextDetails.push({
            innerTextArray,
          });
        });
      });
    },

    emailVenue(event) {
      let designData = new FormData();
      const shareWithCrem = event.shareWithCrem;
      if (event.message) designData.append('message', event.message);
      designData.append(
        'recipient',
        shareWithCrem ? this.venueRecipient : event.customRecipient
      );
      designData.append(
        'chosenProductSpecs',
        JSON.stringify(Object.fromEntries(this.chosenProductSpecs))
      );
      designData.append('product', localStorage.getItem('product'));
      designData.append('lines', JSON.stringify(this.productTextFields));
      designData.append('editedImage', localStorage.getItem('editedImage'));
      designData.append('totalPrice', localStorage.getItem('totalPrice'));
      designData.append(
        'customerEmail',
        event.customerEmail == ''
          ? 'noreply@alwaysremember.co.uk'
          : event.customerEmail
      );
      designData.append('contactNumber', this.contactNumber);
      if (this.deceased) {
        designData.append('id', this.deceased.id);
      }
      designData.append('url', window.location.href);
      designData.append('order_date', moment().format('YYYY-MM-DD'));
      designData.append('site_id', this.styles.site_id);
      designData.append('shareWithCrem', shareWithCrem);
      axios
        .post('/api/sendDesigns', designData)
        .then(() => {
          this.messageSendSuccess = true;
        })
        .catch((res) => {
          this.messageSendFailed = true;
          console.error(res.response.data.error);
        });
    },
    getCustomSiteText() {
      axios
        .get(`/api/getCustomText/${this.$store.state.styles.site_id}`)
        .then((res) => {
          (this.buyNowText = res.data.buy_now_text),
            (this.shareText = res.data.share_text);
          this.nextSteps = res.data.next_steps;
        });
    },
    purgeLocalStorageOfOldProduct(productId) {
      const storedProduct = JSON.parse(localStorage.getItem('product'));

      if (!storedProduct || storedProduct.id == productId) return;

      const names = [
        'chosenProductSpecs',
        'croppedImage',
        'croppedImageTimestamp',
        'predefinedImage',
        'predefinedImageTimestamp',
        'customText',
        'editedImage',
        'material',
        'product',
        'shape',
        'shapeScale',
        'textColour',
        'totalPrice',
        'layoutType',
      ];
      names.forEach((sname) => {
        localStorage.removeItem(sname);
      });
    },

    getSavedData(operation) {
      switch (operation) {
        case 'material':
          if (
            this.product.id == localStorage.getItem('material-product-id')?.id
          ) {
            const material = localStorage.getItem('material');
            if (material !== null) this.selectedMaterial = JSON.parse(material);
          }
          break;
        case 'font-colour':
          if (
            this.product.id == localStorage.getItem('font-colour-product-id')
          ) {
            const fontColour = localStorage.getItem('font-colour');
            if (fontColour !== null) this.fontColour = JSON.parse(fontColour);
          }
          break;
        case 'font-selection':
          if (
            this.product.id == localStorage.getItem('font-selection-product')
          ) {
            const fontSelection = localStorage.getItem('font-selection');
            if (fontSelection !== null) {
              this.selectedFontFace = fontSelection;
            } else {
              this.selectedFontFace = null;
            }
            bus.$emit('updateFontFace', { font: this.selectedFontFace });
          }
          break;
        case 'text':
          const customText = localStorage.getItem('customText');
          if (customText !== null)
            this.productTextFields = JSON.parse(customText);
          break;
        case 'layoutType':
          const layoutType = localStorage.getItem('layoutType');
          if (layoutType !== null) this.layoutType = layoutType;
          break;
        case 'chosenProductSpecs':
          const chosenProductSpecs = localStorage.getItem('chosenProductSpecs');
          if (chosenProductSpecs) {
            this.chosenProductSpecs = new Map(Object.entries(JSON.parse(chosenProductSpecs)));
          }
          break;
        default:
          throw `getSavedData could not process unknown operation ${operation}`;
      }
    },
    formatDeceasedName(deceased) {
      const parts = [
        deceased.firstname,
        deceased.middlename,
        deceased.lastname,
      ];
      return parts.join(' ').replace('  ', ' ');
    },
    fetchMemorialisationName() {
      axios
        .get(`/api/fetchmemorialisationname/${this.$route.params.productid}`)
        .then((res) => {
          this.MemName = res.data;
        });
    },
    fetchFeaturedProducts() {
      let code = getCodeFromParams(this.$route.params);
      if (isNaN(code)) {
          code = this.$route.params.code;
      } else {
          code = getCodeFromParams(this.$route.params);
      }
      axios
        .get(
          `/api/featuredcustomerproducts/${code}`
        )
        .then((res) => {
          if (res.data.length == 0) {
            this.featured = null;
          } else {
            this.featured = res.data;
          }
        })
        .catch((error) => {
          this.snackbar = true;
          this.snackbarText = error.response.data.message;
        });
    },
    closeErrorForm() {
      this.messageSendFailed = false;
    },
    closeSuccessForm() {
      this.messageSendSuccess = false;
    },
    updateProductTextFieldsFromSlide(slide){
      if (!slide) return;
      if (this.productTextFields.length) {
        this.productTextFields.forEach((item) => {
          const ctf = slide.custom_text_fields.find((data) => data.type === item.type);
          if (ctf) {
            item.letterCount = ctf.letterCount;
          }
        });
      } else {
        // productTextFields has not loaded yet, will be rerun once it has
        this.currentSlide = slide;
      }
    },
  },
  computed: {
    deceased() {
      return this.$store.state.deceased;
    },
    styles() {
      return this.$store.state.styles;
    },
    items() {
      if (this.$route.params.categoryid && this.MemName) {
        return [
          {
            text: 'Home',
            link: true,
            exact: true,
            disabled: false,
            to: { name: 'memorials' },
          },
          {
            text: this.MemName.name,
            link: true,
            exact: true,
            disabled: false,
            to: { name: 'categories' },
          },
          {
            text: 'Products',
            link: true,
            exact: true,
            disabled: false,
            to: {
              name: 'productlisting',
              params: {
                categoryid: this.$route.params.categoryid,
              },
            },
          },
          {
            text: this.product.name,
            link: true,
            exact: false,
            disabled: true,
          },
        ];
      } else if (this.product) {
        return [
          {
            text: 'Home',
            link: true,
            exact: true,
            disabled: false,
            to: { name: 'memorials' },
          },
          {
            text: 'Products',
            link: true,
            exact: true,
            disabled: false,
            to: { name: 'categories' },
          },
          {
            text: this.product.name,
            link: true,
            exact: false,
            disabled: true,
          },
        ];
      }
    },
    currentProduct() {
      return this.product;
    },
  },
  mounted() {
    localStorage.removeItem('customText');
    this.getDeceased();
    this.scrollToTop();

    this.code = getCodeFromParams(this.$route.params);

    bus.$on('layoutType', (data) => {
      this.layoutType = data;
      localStorage.setItem('layoutType', data);
      bus.$emit('filterCarousel');
    });
    bus.$on('updatePredefinedImage', (data) => {
      this.chosenProductSpecs.set('predefinedImage', {
        name: 'Predefined Image',
        price: data.price,
        type_name: 'Predefined Image',
      });
      bus.$emit('updateImage', {});
      this.calculateTotalPrice();
      this.addToCart();
    });
    bus.$on('imagePrice', (data) => {
      if (data == 0) {
        this.chosenProductSpecs.delete('predefinedImage');
        this.addToCart();
      }
      this.calculateTotalPrice();
    });

    bus.$on('updateColour', (data) => {
      this.fontColour = data;
      this.chosenProductSpecs.set('textColour', {
        name: data.name,
        color: data.colour,
        price: null,
        type_name: 'Text Colour',
        item_type: 'fontColour'
      });
      localStorage.setItem('font-colour-product-id', this.product.id);
      localStorage.setItem('font-colour', JSON.stringify(data));
    });

    bus.$on('setMaterial', (data) => {
      this.selectedMaterial = data;
      this.chosenProductSpecs.set('material', {
        name: data.type,
        price: data.price,
        type_name: 'Material',
        item_type: 'material'
      });
      localStorage.setItem('material-product-id', this.product.id);
      localStorage.setItem('material', JSON.stringify(data));
      this.addToCart();
    });

    bus.$on('updateFontFace', (data) => {
      if (!data.font) return;
      this.selectedFontFace = data.font;
      localStorage.setItem('font-selection-product-id', this.product.id);
      localStorage.setItem('font-selection', this.selectedFontFace);
      this.chosenProductSpecs.set('fontFace', {
        name: this.selectedFontFace,
        type_name: 'Font Face',
        item_type: 'fontFace'
      });
    });
    bus.$on('launchCallbackDialogue', (data) => {
      this.callbackDialogue = data;
    });
    bus.$on('slideChange', (slide) => {
      this.updateProductTextFieldsFromSlide(slide);
    });
  },
  destroyed() {
    bus.$off('layoutType');
    bus.$off('updatePredefinedImage');
    bus.$off('imagePrice');
    bus.$off('updateColour');
    bus.$off('setMaterial');
    bus.$off('updateFontFace');
    bus.$off('launchCallbackDialogue');
  }
};
</script>
<style scoped>
@media screen and (max-width: 576px) {
  .main-image-inner {
    margin-left: 20px;
    margin-right: 20px;
  }
}
.main-image-inner .material {
  width: 100px;
  border-radius: 200px;
  height: 100px;
}

.material:hover {
  border: 3px solid black;
}

a {
  text-decoration: none;
}

.modal-header {
  border-bottom: 0 none;
}

.modal-footer {
  border-top: 0 none;
}

.mdi.md-100 {
  font-size: 100px;
  color: green;
}

.mainContainer {
  max-width: 100%;
}

.topRow {
  height: 400px;
}

.v-input--selection-controls .v-input__slot > .v-label,
.v-input--selection-controls .v-radio > .v-label {
  align-items: center;
  display: inline-flex;
  flex: 1 1 auto;
  height: auto;
  margin-bottom: 0px !important;
}

.imageRadioButtons {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.imageRadioButtons + img {
  cursor: pointer;
}

.imageRadioButtons:checked + img {
  outline: 3px solid rgb(5, 72, 255);
}
</style>
