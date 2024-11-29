<template>
  <div>
    <div class="container pb-5">
      <TopMenuComponent
        v-on:launchCallbackDialogue="launchCallbackDialogue($event)"
      />
      <div class="row my-5 text-center">
        <div class="col-12">
          <template v-if="deceased">
            <h5 class="font-weight-light">Remembering</h5>
            <h1 class="primary-colour">
              {{ deceased.firstname }} {{ deceased.middlename }}
              {{ deceased.lastname }}
            </h1>
            <p class="h7" v-if="deceased.date_of_death">
              {{ moment(deceased.date_of_death).format("Do MMMM YYYY") }}
            </p>
          </template>
          <template v-else>
            <h5 class="font-weight-light">Remembering</h5>
            <h1 class="primary-colour">Your Loved One</h1>
          </template>
        </div>
      </div>
    </div>
    <div class="home-page-row">
      <div class="container">
        <div class="row panel">
          <div class="col-12 col-lg-6">
            <div class="panelContent">
              <h6>
                Ways to remember
                <span v-if="deceased">{{ deceased.firstname }}</span>
                <span v-else>your loved one</span>
              </h6>
              <p>
                {{ remember_copy ? remember_copy : 'A customised tribute is a thoughtful and caring way to celebrate the life of your loved one and create a place of commemoration for family and friends.' }}
              </p>
              <v-btn
                :to="{
                  name: 'categories'
                }"
                class="btn-outline text-size-regular"
                outlined
                rounded
                v-if="deceased"
                elevation="0"
                :color="styles.primary_colour"
              >
                Memorial options
              </v-btn>
              <v-btn
                :to="{ name: 'categories' }"
                class="btn-outline text-size-regular"
                outlined
                rounded
                v-else
                elevation="0"
                :color="styles.primary_colour"
              >
                Memorial options
              </v-btn>
            </div>
          </div>
          <div class="col-12 col-lg-6">
            <div class="panelContent">
              <h6>Bereavement support</h6>
              <p>
                {{ bearevement_copy ? bearevement_copy : 'Coping with grief can take many forms. These articles cover a range of bereavement topics and provide details of grief and bereavement support groups and organisations.' }}
              </p>
              <v-btn
                outlined
                rounded
                :to="{ name: 'bereavementsupport' }"
                :color="styles.primary_colour"
                class="btn-outline text-size-regular"
              >
                View articles
              </v-btn>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-5" v-if="product">
        <div class="col-8 offset-2 help text-md-left text-center">
          <h4>Your Saved Memorial</h4>
          <h4>Pick up from where you left off</h4>
          <div class="row mt-4">
            <div class="col-12 col-mb-1" v-if="product.images[0]">
              <img
                :src="product.images[0].imageurl"
                alt=""
                style="width: 100px"
              />
            </div>
            <div class="col-12 col-md-8">
              <span class="font-weight-bold" v-if="product">{{
                product.name
              }}</span>
              <p v-html="product.description"></p>
            </div>
            <div class="col-12 col-md-2">
              <v-btn
                block
                :color="styles.primary_colour"
                :style="{ color: styles.secondary_colour }"
                :to="{ name: 'productpage', params: { productid: product.id } }"
              >
                Continue</v-btn
              >
            </div>
          </div>
        </div>
      </div>
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
                elevation="0"
              >
                <v-icon class="primary-colour">mdi-arrow-right</v-icon>
              </v-btn>
            </div>
          </div>
          <div class="row rowGap" v-if="featured != null">
            <div
              class="col-12 col-md-4 text-start"
              v-for="product in featured"
              :key="product.id"
            >
              <router-link
                :to="{
                  name: 'singleproductpage',
                  params: { productid: product.id },
                }"
              >
                <div class="row">
                  <div class="col-12 topRow">
                    <v-img
                      v-if="product.thumbnail_image"
                      :src="product.thumbnail_image"
                      class="thumbnail-image mb-5"
                    ></v-img>
                  </div>
                </div>
              </router-link>
              <div class="row">
                <div class="col-12 pt-5">
                  <h4>{{ product.name }}</h4>
                  <p
                    v-if="product.short_description"
                    class="text-size-tiny text-justify font-weight-light featured-description"
                    v-html="removeWordStyling(product.short_description)"
                  >
                  </p>
                  <router-link
                    text
                    class="primary-colour product-link"
                    :to="{
                      name: 'singleproductpage',
                      params: { productid: product.id },
                    }"
                    :category="product.product_category_id"
                  >
                    View Product</router-link
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-5" v-if="recentlyViewedProducts">
            <div class="col-12 col-md-3">
              <h4 class="font-weight-bold text-md-left text-center">
                Recently Viewed
              </h4>
            </div>
            <div class="col-12 col-md-3 offset-md-6" v-if="deceased">
              <span class="primary-colour pr-2">See all</span>
              <v-btn
                fab
                x-small
                :to="{
                  name: 'categories',
                }"
                elevation="0"
              >
                <v-icon class="primary-colour">mdi-arrow-right</v-icon>
              </v-btn>
            </div>
          </div>
          <div class="row mt-5" v-if="recentlyViewedProducts">
            <div
              class="col-md-4 col-12"
              v-for="recentProduct in recentlyViewedProducts"
              :key="recentProduct.id"
            >
              <v-img
                v-if="recentProduct.thumbnail_image"
                :src="recentProduct.thumbnail_image"
                class="thumbnail-image mb-5"
              ></v-img>
              <p class="font-weight-bold mt-2">{{ recentProduct.name }}</p>
              <p
                v-if="recentProduct.short_description"
                class="text-size-tiny text-justify font-weight-light featured-description"
                v-html="removeWordStyling(recentProduct.short_description)"
              >
              </p>
              <router-link
                text
                class="primary-colour product-link"
                :to="{
                  name: 'singleproductpage',
                  params: { productid: recentProduct.id },
                }"
                :category="recentProduct.product_category_id"
              >
                View Product</router-link
              >
            </div>
          </div>
        </div>
      </div>
      <CallbackModalComponent
        v-on:closeForm="closeForm($event)"
        v-bind:callbackDialogue="callbackDialogue"
      />
    </div>
  </div>
</template>
<script>
import CallbackModalComponent from "./modals/CallbackModalComponent";
import TopMenuComponent from "./includes/TopMenuComponent";
import removeWordStyling from "../../../removeStyling";
import { validateLocalStorageForDeceased, validateLocalStorageForSite, getCodeFromParams } from "../../../deceased";
import { bus } from "../../../app";
export default {
  components: {
    CallbackModalComponent,
    TopMenuComponent,
  },
  data() {
    return {
      data: "",
      name: null,
      primaryColor: null,
      secondaryColor: null,
      callbackDialogue: false,
      product: null,
      featured: null,
      recentlyViewedProducts: null,
      snackbar: false,
      snackbarText: "",
      timeout: 4000,
      article: null,
      remember_copy: '',
      bearevement_copy: ''
    };
  },
  methods: {
    removeWordStyling(text) {
      return removeWordStyling(text);
    },
    closeForm(state) {
      this.callbackDialogue = state;
    },
    launchCallbackDialogue(state) {
      this.callbackDialogue = state;
    },
    getSavedItem() {
      this.product = JSON.parse(localStorage.getItem("product"));
      this.primaryColor = this.styles.primary_colour;
      this.secondaryColor = this.styles.secondary_colour;
    },
    fetchProduct() {
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
          this.getRecentlyViewed();
          if (res.data.length == 0) {
            this.featured = null;
          } else {
            this.featured = res.data;
          }
        })
        .catch((error) => {
          this.snackbar = true;
          this.snackbarText = 'Error fetching products';
        });
    },
    getRecentlyViewed() {
      if (localStorage.getItem("recentlyViewed") != null) {
        this.recentlyViewedProducts = JSON.parse(
          localStorage.getItem("recentlyViewed")
        ).slice(0, 4);
      }
    },
    checkDeceased() {
      let deceased = localStorage.getItem("deceased");
      this.checkSite();
      if (deceased && deceased !== '""') {
        deceased = JSON.parse(deceased);
      } else {
        return;
      }
      if (
        !validateLocalStorageForDeceased(
          this.$route.params,
          deceased.landing_code,
          deceased.firstname.toLowerCase(),
          deceased.lastname.toLowerCase()
        )
      ) {
        localStorage.clear();
      }
    },
    checkSite() {
      let siteName = localStorage.getItem("site-name");
      if (siteName) {
          if (
          !validateLocalStorageForSite(
            this.$route.params,
            siteName
          )
          ) {
          localStorage.clear();
        }
      }
    },
    getSettings() {
      axios
        .get(`/api/fetchSettings`)
        .then((res) => {
          this.remember_copy = res.data.homepage_remember_copy.value,
          this.bearevement_copy = res.data.homepage_bereavement_copy.value
        });
    },
  },
  computed: {
    memorialisations() {
      return this.$store.state.memorialisations;
    },
    deceased() {
      return this.$store.state.deceased;
    },
    styles() {
      return this.$store.state.styles;
    },
  },
  created() {
    this.checkDeceased();
    this.$store.dispatch("fetchMemorialisationInfo");
    this.getSavedItem();
  },
  mounted() {
    this.fetchProduct();
    this.getSettings();
    bus.$on("launchCallbackDialogue", (data) => {
      this.callbackDialogue = data;
    });
  },
};
</script>
<style scoped>
@media (min-width: 400px) {
  .rememberText {
    padding-top: 15px !important;
    color: white;
  }
}
@media (min-width: 992px) {
  .rememberText {
    padding-top: 80px !important;
    color: white;
  }
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
.customButton {
  background-color: #0095a6;
  color: white;
}
.imageFont {
  color: white;
}
.descriptionBox {
  background-color: rgba(0, 102, 0, 0.3);
  /* background-image: linear-gradient(to right, rgba(0,102,0,0), rgba(0,102,0,0.3), rgba(0,102,0,1)); */
  position: absolute;
  right: 0;
  height: 100%;
  width: 500px;
  padding: 0 100px;
}
.viewAllButton {
  background-color: white;
  width: 130px;
  color: black;
}
.primary {
  background-color: #00bfff;
}
.remembering {
  display: flex;
  justify-content: center;
  align-content: center;
  flex-direction: column;
}
.waysToRememeber {
  background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
    url("/memorialisation/garden.jpg");
  height: 500px;
  background-size: cover;
  background-position-y: center;
  color: white;
}
.help {
  background-color: #e8e8e8;
  padding-top: 30px;
}
.womanImage {
  background: url("/memorialisation/womancup.jpg");
  height: 245px;
  background-size: cover;
  background-position-y: center;
}
.bold {
  font-weight: bold;
}
.councilLogo {
  background: url("/memorialisation/westerleigh-200.png");
  background-size: cover;
  background-position-y: center;
}
</style>
