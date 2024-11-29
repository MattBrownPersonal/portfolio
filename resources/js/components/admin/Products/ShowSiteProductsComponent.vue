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
        <v-card>
          <v-card-text>
            <p class="text-h6">Actions</p>
            <v-divider></v-divider>
            <v-list-item>
              <v-list-item-content>               
                <v-list-item-title class="pb-4">
                  <span class="actionLink" @click="deleteMemorialisation()">
                    <v-icon left>mdi-delete</v-icon>
                    Delete
                  </span>
                </v-list-item-title>
                <v-list-item-title class="pb-4">
                  <span class="actionLink" @click="editCategoryDetails(category); ">
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
        <UsefulLinksComponent v-bind:site="site" />
      </v-col>
      <v-col class="col-10">
        <v-card class="mb-4">
          <v-card-title v-if="category">
            {{ category.memorialisation.name }}
          </v-card-title>
          <v-card-text>
            <div class="row" v-if=category>
              <div class="col-6">
                <p class="font-weight-bold">Description</p>
                <span v-html="category.description"></span>
              </div>
              <div class="col-6">
                <p class="font-weight-bold">Image</p>
                <v-img :src="category.image" width="200px"></v-img>
              </div>
            </div>
          </v-card-text>
        </v-card>
        <v-card>
            <v-card-title>
            Products
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
            :headers="siteProductHeaders"
            :items="siteProducts"
            :items-per-page="10"
            class="elevation-1"
            :loading="sitesTableLoading"
            loading-text="Loading... Please wait"
            :search="siteSearch"
          >
            <template v-slot:[`item.showProducts`]="{ item }">
              <v-btn
                rounded
                color="primary"
                dark
                :to="{
                  name: 'showproduct',
                  params: { product_id: item.id, id: $route.params.id },
                }"
              >
                View Product
              </v-btn>
            </template>
            <template v-slot:[`item.product_id`]="{ item }">
              {{ item.name }}
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
    <NewSiteMemorialisationProductComponent
      v-bind:newSiteProductDialog="newSiteProductDialog"
      v-bind:siteId="site.id"
      v-bind:memorialisationId="parseInt(this.$route.params.category)"
      v-on:closeForm="closeFormChange($event)"
      v-on:fetchSiteProducts="fetchSiteProducts()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
    <EditSiteMemorialisation
      v-bind:editCategoryDetailsDialogue="editCategoryDetailsDialogue"
      v-bind:siteId="site.id"
      v-bind:selectedMemorialisation="selectedCategory"
      v-on:closeForm="closeFormChange($event)"
      v-on:fetchCategoryData="fetchCategoryData()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
    <DeleteSiteMemorialisation
      v-bind:deleteMemorialisationDialog="deleteMemorialisationDialog"
      v-bind:siteId="site.id"
      v-bind:category="category"
      v-on:closeForm="closeFormChange($event)"
      v-on:fetchSiteProducts="fetchSiteProducts()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
  </div>
</template>
<script>
import NewSiteMemorialisationProductComponent from "./modals/NewSiteMemorialisationProductComponent";
import DeleteSiteMemorialisation from "../Memorialisations/modals/DeleteProductCategoryComponent";
import EditSiteMemorialisation from "../Products/modals/EditSiteCategoryComponent.vue";
import UsefulLinksComponent from "../UsefulLinksComponent.vue";
export default {
  components: {
    NewSiteMemorialisationProductComponent,
    UsefulLinksComponent,
    DeleteSiteMemorialisation,
    EditSiteMemorialisation
  },
  data() {
    return {
      siteProductHeaders: [
        { text: "Name", align: "start", value: "product_id", width: "25%" },
        { text: "Show Products", value: "showProducts", width: "25%" },
      ],
      category: null,
      siteProducts: [],
      sitesTableLoading: false,
      newSiteProductDialog: false,
      editCategoryDetailsDialogue: false,
      selectedCategory: null,
      userSearch: "",
      siteSearch: "",
      site: "",
      snackbar: false,
      snackbarText: "",
      timeout: 6000,
      deleteMemorialisationDialog: false
    };
  },
  mounted() {
    this.sitesTableLoading = true;
    this.fetchCategoryData();
    this.fetchSiteName();
    this.fetchSiteProducts();
  },
  methods: {
    fetchSiteProducts() {
      axios
        .get(
          `/api/siteproducts/${this.$route.params.category}/${this.$route.params.id}`
        )
        .then((res) => {
          this.sitesTableLoading = false;
          this.siteProducts = res.data;
        })
        .catch(err => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    },
    fetchCategoryData() {
      axios.get(`/api/fetchmemorialisation/${this.$route.params.id}/${this.$route.params.category}`)
        .then((res) => {
          this.category = res.data;
        })
        .catch(err => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    },
    fetchSiteName() {
      axios.get(`/api/site/${this.$route.params.id}`)
        .then((res) => {
        this.site = res.data;
        })
        .catch(err => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    },
    deleteMemorialisation() {
      this.deleteMemorialisationDialog = true;
    },
    editCategoryDetails(category) {
      this.selectedCategory = category;
      this.editCategoryDetailsDialogue = true;
    },
    closeFormChange(state) {
      this.newSiteProductDialog = state;
      this.editCategoryDetailsDialogue = state;
      this.deleteMemorialisationDialog = state;
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
