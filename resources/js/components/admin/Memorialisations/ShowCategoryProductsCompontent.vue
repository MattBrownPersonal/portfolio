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
            @click="
              newProductDialog = true;
              editCategoryDetails(category);
            "
          >
            <v-icon>add</v-icon>
          </v-btn>
        </v-col>
      </v-row>
      <v-col class="col-2">
        <v-card elevation="2">
          <v-card>
            <v-card-text>
              <p class="text-h6">Actions</p>
              <v-divider></v-divider>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title class="pb-4">
                    <span
                      class="actionLink"
                      @click="
                        editCategoryDialog = true;
                        editCategoryDetails(category);
                      "
                    >
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
        </v-card>
      </v-col>
      <v-col class="col-10">
        <v-card elevation="1">
          <v-card-title>
            Category: {{ category.name }}
            <v-spacer></v-spacer>
            <v-text-field
              v-model="productSearch"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            />
          </v-card-title>
          <v-data-table
            :headers="productHeaders"
            :items="products"
            :items-per-page="10"
            class="elevation-1"
            :loading="productsTableLoading"
            loading-text="Loading... Please wait"
            :search="productSearch"
          >
            <template v-slot:[`item.edit`]="{ item }">
              <v-btn
                rounded
                color="primary"
                dark
                :to="{ name: 'viewproduct', params: { id: item.id } }"
              >
                View Product Details
              </v-btn>
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
    <!-- CRUD forms for Products -->
    <NewProductComponent
      v-bind:newProductDialog="newProductDialog"
      v-on:closeForm="closeFormChange($event)"
      v-on:fetchCategory="fetchCategory()"
      v-bind:selectedCategory="selectedCategory"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
    <EditCategoryComponent
      v-bind:editCategoryDialog="editCategoryDialog"
      v-on:closeForm="closeFormChange($event)"
      v-bind:selectedCategory="selectedCategory"
      v-on:fetchCategory="fetchCategory()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
  </div>
</template>
<script>
import NewProductComponent from "./modals/NewProductComponent";
import EditCategoryComponent from "./modals/EditCategoryComponent";

export default {
  components: {
    NewProductComponent,
    EditCategoryComponent,
  },
  data() {
    return {
      productHeaders: [
        { text: "Name", align: "start", value: "name", width: "30%" },
        { text: "SKU", align: "start", value: "sku", width: "30%" },
        { text: "Action", value: "edit", width: "50%" },
      ],
      products: [],
      category: [],
      productsTableLoading: false,
      newProductDialog: false,
      editCategoryDialog: false,
      productSearch: "",
      selectedCategory: "",
      productName: "",
      productId: "",
      snackbar: false,
      snackbarText: "",
      timeout: 6000,
    };
  },
  mounted() {
    this.productsTableLoading = true;
    this.fetchCategory();
  },
  methods: {
    fetchCategory() {
      axios
        .get(`/api/categories/${this.$route.params.id}`)
        .then((res) => {
          this.productsTableLoading = false;
          this.products = res.data.products;
          this.category = res.data;
        })
        .catch((err) => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    },
    editCategoryDetails(item) {
      this.selectedCategory = item;
    },
    closeFormChange(state) {
      this.newProductDialog = state;
      this.editCategoryDialog = state;
      this.deleteProductDialog = state;
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
