<template>
  <div class="px-5">
    <v-row>
      <v-col class="col-12" no-gutters>
        <v-row class="addNewButtonWrapper">
          <v-col class="col-md-4 offset-4 d-flex justify-center" no-gutters>
            <v-btn
              fab
              fixed
              right
              bottom
              color="primary"
              dark
              @click="newSupplierDialog = true"
            >
              <v-icon>add</v-icon>
            </v-btn>
          </v-col>
        </v-row>
        <v-card>
          <v-card-title>
            Suppliers
            <v-spacer></v-spacer>
            <v-text-field
              v-model="supplierSearch"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            />
          </v-card-title>
          <v-data-table
            :headers="suppliersHeaders"
            :items="suppliers"
            :items-per-page="10"
            class="elevation-1"
            :loading="suppliersTableLoading"
            loading-text="Loading... Please wait"
            :search="supplierSearch"
          >
            <template v-slot:[`item.sites`]="{ item }">
              <div v-for="site in item.sites" :key="site.id">
                {{ site.name }}
              </div>
            </template>
            <template v-slot:[`item.edit`]="{ item }">
              <v-btn
                rounded
                color="primary"
                dark
                :to="{ name: 'viewsupplier', params: { id: item.id } }"
              >
                View
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
    <!-- CRUD forms for Suppliers -->
    <NewSupplierComponent
      v-bind:newSupplierDialog="newSupplierDialog"
      v-on:closeForm="closeFormChange($event)"
      v-on:fetchSuppliers="fetchSuppliers()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
    <EditSupplierComponent
      v-bind:editSupplierDialog="editSupplierDialog"
      v-on:closeForm="closeFormChange($event)"
      v-bind:selectedSupplier="selectedSupplier"
      v-on:fetchSuppliers="fetchSuppliers()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
    <DeleteSupplierComponent
      v-bind:deleteSupplierDialog="deleteSupplierDialog"
      v-on:closeForm="closeFormChange($event)"
      v-bind:selectedSupplier="selectedSupplier"
      v-on:fetchSuppliers="fetchSuppliers()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
  </div>
</template>
<script>
import NewSupplierComponent from "./modals/AddNewSupplierComponent";
import EditSupplierComponent from "./modals/EditSupplierComponent";
import DeleteSupplierComponent from "./modals/DeleteSupplierComponent";
export default {
  components: {
    NewSupplierComponent,
    EditSupplierComponent,
    DeleteSupplierComponent,
  },
  data() {
    return {
      suppliersHeaders: [
        { text: "Name", value: "name", width: "50%" },
        { text: "Actions", value: "edit", width: "50%" },
      ],
      suppliers: [],
      selectedSupplier: "",
      suppliersTableLoading: false,
      newSupplierDialog: false,
      editSupplierDialog: false,
      deleteSupplierDialog: false,
      supplierSearch: "",
      snackbar: false,
      snackbarText: "",
      timeout: 4000,
    };
  },
  mounted() {
    this.suppliersTableLoading = true;
    this.fetchSuppliers();
  },
  methods: {
    fetchSuppliers() {
      axios
        .get("/api/suppliers")
        .then((res) => {
          this.suppliersTableLoading = false;
          this.suppliers = res.data;
        })
        .catch((err) => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    },
    editSupplierDetails(item) {
      this.selectedSupplier = item;
    },
    closeFormChange(state) {
      this.newSupplierDialog = state;
      this.editSupplierDialog = state;
      this.deleteSupplierDialog = state;
    },
    triggerSnackBar($event) {
      this.snackbarText = $event;
      this.snackbar = true;
    },
  },
};
</script>
