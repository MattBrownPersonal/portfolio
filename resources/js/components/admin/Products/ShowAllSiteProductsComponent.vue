<template>
  <div class="px-5">
    <v-row>
      <v-row class="addNewButtonWrapper">
        <v-col class="col-md-4 offset-4 d-flex justify-center" no-gutters>
          <v-btn
            fab
            fixed
            right
            bottom
            color="primary"
            dark
            @click="newSiteProductDialog = true"
          >
            <v-icon>add</v-icon>
          </v-btn>
        </v-col>
      </v-row>
      <v-col class="col-2">
        <v-card elevation="2">
          <v-card-text>
            <p class="text-h6">Actions</p>
            <v-divider></v-divider>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>
                  <v-icon left>mdi-keyboard-backspace</v-icon>
                  <span @click="$router.go(-1)" class="actionLink">Back</span>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-card-text>
        </v-card>
        <UsefulLinksComponent v-bind:site="site" />
      </v-col>
      <v-col class="col-10">
        <v-card>
          <v-card-title>
            Products for {{ site.name }}
            <v-spacer></v-spacer>
            <v-text-field
              v-model="siteSearch"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            />
          </v-card-title>
          <v-data-table
            :headers="siteProductsHeaders"
            :items="siteProducts"
            :items-per-page="10"
            class="elevation-1"
            :loading="sitesTableLoading"
            loading-text="Loading... Please wait"
            :search="siteSearch"
          >
            <template v-slot:[`item.showProducts`]="{ item }">
              <span v-if="!item.memorialisation_id && site.uses_categories === 1">
                <v-btn
                  rounded
                  color="error"
                  dark
                  :to="{
                    name: 'showproduct',
                    params: { product_id: item.id, site: site.id },
                  }"
                >
                  View Product
                </v-btn>
              </span>
              <span v-else>
                <v-btn
                  rounded
                  color="primary"
                  dark
                  :to="{
                    name: 'showproduct',
                    params: { product_id: item.id, site: site.id },
                  }"
                >
                  View Product
                </v-btn>
              </span>
            </template>
            <template v-slot:[`item.product_id`]="{ item }">
              <span v-if="item.memorialisation_id" style="color:black">
              {{ item.name }}</span>
              <span v-else style="color:red">{{ item.name }}</span>
            </template>
            <template v-slot:[`item.message`]="{ item }">
              <span v-if="!item.memorialisation_id && site.uses_categories === 1">This product has no category assigned</span>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
    <v-snackbar v-model="snackbar" :timeout="timeout">
      {{ snackbarText }}

      <template v-slot:action="{ attrs }">
        <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
    <NewProductComponent
      v-bind:newSiteProductDialog="newSiteProductDialog"
      v-bind:siteId="site.id"
      v-on:closeForm="closeFormChange($event)"
      v-on:fetchProducts="fetchSiteProducts()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
  </div>
</template>
<script>
import NewProductComponent from "./modals/NewProductComponent";
import UsefulLinksComponent from "../UsefulLinksComponent.vue";
export default {
  components: {
    NewProductComponent,
    UsefulLinksComponent,
  },
  data() {
    return {
      siteProductsHeaders: [
        {
          text: "Product Name",
          align: "start",
          value: "name",
          width: "40%",
        },
        { text: "Show Products", value: "showProducts", width: "15%" },
        { text: "Message", value: "message", width: "45%" },
      ],
      siteProducts: [],
      sitesTableLoading: false,
      newSiteProductDialog: false,
      userSearch: "",
      siteSearch: "",
      selectedSite: "",
      site: "",
      snackbar: false,
      snackbarText: "",
      timeout: 6000,
    };
  },
  mounted() {
    this.sitesTableLoading = true;
    this.fetchSiteName();
    this.fetchSiteProducts();
  },
  methods: {
    fetchSiteProducts() {
      axios
        .get(`/api/allsiteproducts/${this.$route.params.id}`)
        .then((res) => {
          this.sitesTableLoading = false;
          this.siteProducts = res.data;
        })
        .catch((err) => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    },
    fetchSiteName() {
      axios.get(`/api/site/${this.$route.params.id}`).then((res) => {
        this.site = res.data;
      });
    },
    closeFormChange(state) {
      this.newSiteProductDialog = state;
    },
    triggerSnackBar($event) {
      this.snackbarText = $event;
      this.snackbar = true;
    },
  },
};
</script>
<style scoped>
.crudMenu {
  list-style-type: none;
}
.actionLink {
  cursor: pointer;
}
</style>
