<template>
    <v-app class="px-5">
        <v-row>
            <v-col class="col-12">
                <v-row class="addNewButtonWrapper">
                    <v-col class="col-md-4 offset-4 d-flex justify-center" no-gutters>
                        <v-btn
                        fab fixed right bottom
                        color="primary"
                        dark
                        @click="newProductDialog = true"
                        >
                        <v-icon>add</v-icon>
                        </v-btn>
                    </v-col>
                </v-row>
                <v-card-title>
                All Products
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
                :headers="productsHeaders"
                :items="products"
                :items-per-page="10"
                class="elevation-1"
                :loading="productsTableLoading"
                loading-text="Loading... Please wait"
                :search="productSearch"
                >
                <template v-slot:[`item.price`]="{ item }">
                    £{{ item.price }}
                </template>
                 <template v-slot:[`item.view`]="{item}">
                    <v-btn
                        rounded
                        color="primary"
                        dark
                        :to="{ name: 'products', params: { id:item.id }}"
                    >
                        View
                    </v-btn>
                </template>
            </v-data-table>
            </v-col>
        </v-row>
        <v-snackbar
            v-model="snackbar"
            :timeout="timeout"
        >
            {{ snackbarText }}

            <template v-slot:action="{ attrs }">
            <v-btn
                color="blue"
                text
                v-bind="attrs"
                @click="snackbar = false"
            >
                Close
            </v-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>
<script>
    export default {
    data () {
      return {
        productsHeaders: [
            { text: 'Name', value: 'name', width: '20%'},
            { text: 'Supplier', value: 'supplier.name', width: '20%'},
            { text: 'Description', value: 'description', width: '30%'},
            { text: 'Price', value: 'price', width: '10%'},
            { text: 'Category', value: 'category.name', width: '10%'},
            { text: 'Actions', value:'view', width: '10%'}
        ],
        products: [],
        selectedProduct: '',
        productsTableLoading: false,
        productSearch: '',
        snackbar: false,
        snackbarText: '',
        timeout: 4000,
      }
    },
    mounted () {
      this.productsTableLoading = true;
      this.fetchProducts();
    },
    methods: {
      fetchProducts() {
        axios.get('/api/products')
         yourValue
          .catch(err => {
              this.snackbar = true;
              this.snackbarText = err.response.data.message;
          });
      }, 
      closeFormChange(state) {
        this.newProductDialog = state;
      },
      triggerSnackBar($event) {
        this.snackbarText = $event
        this.snackbar = true;
      }
    }
  }
</script>
<style scoped>
a {
    text-decoration: none;
}
</style>