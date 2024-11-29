<template>
  <div>
    <div class="container pb-5">
      <TopMenuComponent
        v-on:launchCallbackDialogue="launchCallbackDialogue($event)"
        v-bind:items="items"
      />
      <div class="row text-center">
        <div class="col-12 mt-5 pt-5">
          <h5 class="mb-4">Remembering</h5>
          <h1 class="primary-colour">
            <span v-if="deceased"
              >{{ deceased.firstname }} {{ deceased.middlename }}
              {{ deceased.lastname }}</span
            >
          </h1>
          <div class="container text-container">
            <p class="text-size-small">
              {{ category_copy ? category_copy : 'There are many ways of remembering a loved one. Choose a memorial and select meaningful words to personalise your tribute.' }}
            </p>
          </div>
        </div>
      </div>
    </div>
    <div
      class="col-12 text-center mb-5 d-none d-md-block catFilters"
      v-if="categories"
    >
      <span class="mr-4" v-if="categories.length">Categories:</span>
      <router-link
        v-for="category in categories"
        :key="category.memorialisation.id"
        :to="{
          name: 'productlisting',
          params: { categoryid: category.memorialisation_id },
        }"
      >
        <v-chip outlined class="primary-colour mr-3 mb-3"
          >{{ category.memorialisation.name }}
        </v-chip>
      </router-link>
    </div>
    <div class="home-page-row">
      <div class="container">
        <div class="row rowGap mb-5" v-if="categories">
          <div class="col-12 text-center" v-if="!categories.length">
            <h3 class="primary-colour">
              There are no products assigned to this category
            </h3>
          </div>
          <div
            v-else
            class="col-12 col-md-6 col-lg-4"
            v-for="category in categories"
            :key="category.memorialisation.id"
          >
            <router-link
              :to="{
                name: 'productlisting',
                params: { categoryid: category.memorialisation_id },
              }"
            >
              <v-img
                class="thumbnail-image mb-5"
                :src="category.image"
                style="max-height: 200px"
              ></v-img>
            </router-link>
            <div class="row">
              <div class="col-12 pt-5 category-cell">
                <h4 class="mb-0">{{ category.memorialisation.name }}</h4>
                <p
                  v-if="category.description"
                  class="text-size-tiny text-justify font-weight-normal featured-description"
                  v-html="removeWordStyling(category.description)"
                >
                </p>
                <router-link
                  class="primary-colour product-link"
                  :to="{
                    name: 'productlisting',
                    params: { categoryid: category.memorialisation_id },
                  }"
                >
                  View Product</router-link
                >
              </div>
            </div>
          </div>
        </div>
        <div class="row rowGap" v-else>
          <div
            class="col-12 col-md-6 col-lg-4"
            v-for="product in products"
            :key="product.id"
          >
            <router-link
              :to="{
                name: 'singleproductpage',
                params: { productid: product.id },
              }"
            >
              <v-img
                class="thumbnail-image mb-5"
                v-if="product.thumbnail_image"
                :src="product.thumbnail_image"
              ></v-img>
            </router-link>
            <div class="row">
              <div class="col-12 pt-5 category-cell">
                <h4 class="mb-0">{{ product.name }}</h4>
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
    <div class="container">
      <div v-if="otherProducts && otherProducts.length > 0 && categories">
        <div class="row mt-5">
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
              :to="{ name: 'categories' }"
              elevation="0"
            >
              <v-icon class="primary-colour">mdi-arrow-right</v-icon>
            </v-btn>
          </div>
        </div>
        <div class="row rowGap">
          <div
            class="col-12 col-md-4 text-start"
            v-for="product in otherProducts"
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
                    class="thumbnail-image"
                  ></v-img>
                </div>
              </div>
            </router-link>
            <div class="row">
              <div class="col-12 pt-5 text-start">
                <h4 class="mb-0">{{ product.name }}</h4>
                <p
                  v-if="product.short_description"
                  class="text-size-tiny text-justify font-weight-normal featured-description"
                  v-html="removeWordStyling(product.short_description)"
                >
                </p>
                <p
                  v-else-if="product.description"
                  class="text-size-tiny text-justify font-weight-normal featured-description"
                  v-html="removeWordStyling(product.short_description)"
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
    <CallbackModalComponent
      v-on:closeForm="closeForm($event)"
      v-bind:callbackDialogue="callbackDialogue"
    />
  </div>
</template>
<script>
import CallbackModalComponent from "./modals/CallbackModalComponent";
import { getCodeFromParams } from "../../../deceased";
import TopMenuComponent from "./includes/TopMenuComponent";
import { bus } from "../../../app";
import removeWordStyling from "../../../removeStyling";

export default {
  components: {
    CallbackModalComponent,
    TopMenuComponent,
  },
  data() {
    return {
      categories: null,
      primaryColor: "#0095a6",
      secondaryColor: "#ffffff",
      requestSent: false,
      callbackDialogue: false,
      otherProducts: null,
      products: [],
      snackbar: false,
      snackbarText: "",
      timeout: 4000,
      category_copy: ''
    };
  },
  computed: {
    deceased() {
      return this.$store.state.deceased;
    },
    styles() {
      return this.$store.state.styles;
    },
    items() {
      if (!this.categories) {
        return [
          {
            text: "Home",
            link: true,
            exact: true,
            disabled: false,
            to: { name: "memorials" },
          },
          {
            text: "Products",
            link: true,
            exact: true,
            disabled: true,
          },
        ];
      } else {
        return [
          {
            text: "Home",
            link: true,
            exact: true,
            disabled: false,
            to: { name: "memorials" },
          },
          {
            text: "Categories",
            link: true,
            exact: true,
            disabled: false,
            to: { name: "categories" },
          },
        ];
      }
    },
  },
  methods: {
    removeWordStyling(text) {
      return removeWordStyling(text);
    },
    checkSite() {
      let code = getCodeFromParams(this.$route.params);
      if (isNaN(code)) {
        code = this.$route.params.code;
      } else {
        code = getCodeFromParams(this.$route.params);
      }
      axios.get(`/api/checkSite/${code}`).then((res) => {
        if (res.data.site === 1) {
          this.fetchCategories();
          this.fetchOtherProducts();
        } else {
          let code = getCodeFromParams(this.$route.params);
          if (isNaN(code)) {
            code = this.$route.params.code;
          } else {
            code = getCodeFromParams(this.$route.params);
          }
          axios
            .get(`/api/allcustomerproducts/${code}`)
            .then((res) => {
              this.products = res.data;
              this.fetchOtherProducts();
            })
            .catch((err) => {
              this.snackbar = true;
              this.snackbarText = err.response.data.message;
            });
        }
      });
    },
    fetchCategories() {
      let code = getCodeFromParams(this.$route.params);
      if (isNaN(code)) {
        code = this.$route.params.code;
      } else {
        code = getCodeFromParams(this.$route.params);
      }
      axios.get(`/api/sitecategories/${code}`).then((res) => {
        this.categories = res.data;
      });
    },
    launchCallbackDialogue(state) {
      this.callbackDialogue = state;
    },
    fetchOtherProducts() {
      let code = getCodeFromParams(this.$route.params);
      if (isNaN(code)) {
        code = this.$route.params.code;
      } else {
        code = getCodeFromParams(this.$route.params);
      }
      axios.get(`/api/otherProducts/${code}`).then((res) => {
        this.otherProducts = res.data;
      });
    },
    closeForm(state) {
      this.callbackDialogue = state;
    },
    getSettings() {
      axios
          .get(`/api/fetchSettings`)
          .then(res => {
              this.category_copy = res.data.category_copy.value
          });
      },
  },
  beforeCreated() {
    this.fetchOtherProducts();
  },
  created() {
    this.checkSite();
    bus.$on("launchCallbackDialogue", (data) => {
      this.callbackDialogue = data;
    });
  },
  mounted() {
    this.getSettings();
  },
};
</script>
<style scoped>
a {
  text-decoration: none;
}

.mainContainer {
  max-width: 100%;
}

.rowGap {
  margin-top: 30px;
}
</style>
