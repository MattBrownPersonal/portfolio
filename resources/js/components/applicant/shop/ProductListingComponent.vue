<template>
  <div>
    <div class="container">
      <TopMenuComponent
        v-on:launchCallbackDialogue="launchCallbackDialogue($event)"
        v-bind:items="items"
      />
      <div class="row text-center" v-if="MemName">
        <div class="col-12 mt-5 pt-5">
          <h5 class="mb-4">Category</h5>
          <h1 class="primary-colour mb-5 pb-5">{{ MemName.name }}</h1>
        </div>
      </div>
    </div>
    <div class="home-page-row">
      <div class="container">
        <div class="row rowGap">
          <div
            class="col-12 col-md-6 col-lg-4"
            v-for="product in products"
            :key="product.id"
          >
            <router-link
              :to="{ name: 'productpage', params: { productid: product.id } }"
            >
              <div class="row">
                <div class="col-12 topRow">
                  <v-img
                    class="thumbnail-image"
                    v-if="product.thumbnail_image"
                    :src="product.thumbnail_image"
                  ></v-img>
                </div>
              </div>
            </router-link>
            <div class="row" v-if="product">
              <div class="col-12 pt-5 product-cell">
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
                    name: 'productpage',
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
import TopMenuComponent from "./includes/TopMenuComponent";
import { getCodeFromParams } from "../../../deceased";
import { bus } from "../../../app";
import removeWordStyling from "../../../removeStyling";
export default {
  components: {
    CallbackModalComponent,
    TopMenuComponent,
  },
  data() {
    return {
      products: null,
      primaryColor: "#0095a6",
      secondaryColor: "#ffffff",
      requestSent: false,
      callbackDialogue: false,
      MemName: null,
    };
  },
  methods: {
     removeWordStyling(text) {
      return removeWordStyling(text);
    },
    closeForm(state) {
      this.callbackDialogue = state;
    },
    fetchProduct() {
      let code = getCodeFromParams(this.$route.params);
      if (isNaN(code)) {
        code = this.$route.params.code;
      } else {
        code = getCodeFromParams(this.$route.params);
      }
      axios
        .get(`/api/customerproducts/${this.$route.params.categoryid}/${code}`)
        .then((res) => {
          this.products = res.data;
          this.fetchMemorialisationName();
        });
    },
    launchCallbackDialogue(state) {
      this.callbackDialogue = state;
    },
    fetchMemorialisationName() {
      axios
        .get(`/api/fetchmemorialisationname/${this.$route.params.categoryid}`)
        .then((res) => {
          this.MemName = res.data;
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
    items() {
      if (this.MemName) {
        return [
          {
            text: "Home",
            link: true,
            exact: true,
            disabled: false,
            to: { name: "memorials" },
          },
          {
            text: this.MemName.name,
            link: true,
            exact: true,
            disabled: false,
            to: { name: "categories" },
          },
          {
            text: "Products",
            link: true,
            exact: true,
            disabled: false,
            to: { name: "productlisting" },
          },
        ];
      }
    },
  },
  mounted() {
    this.fetchProduct();
    bus.$on("launchCallbackDialogue", (data) => {
      this.callbackDialogue = data;
    });
  },
};
</script>
<style scoped>
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
.mainBackground {
  background-color: #e8e8e8;
}

.greyBackground {
  background-color: #e8e8e8;
}
</style>
