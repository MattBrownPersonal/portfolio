<template>
  <div class="px-4">
    <v-row>
      <v-col class="col-2">
        <v-card>
          <v-card-text>
            <p class="text-h6">Actions</p>
            <v-divider></v-divider>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title class="pb-4">
                  <v-list-item-title class="pb-4">
                    <span class="actionLink" @click="
                      editSupplierDialog = true;
                    editSupplierDetails(supplier);
                                            ">
                      <v-icon left>mdi-pencil</v-icon>
                      Edit
                    </span>
                  </v-list-item-title>
                  <span class="actionLink" @click="
                    deleteSupplierDialog = true;
                  editSupplierDetails(supplier);
                                        ">
                    <v-icon left>mdi-delete</v-icon>
                    Delete
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
      </v-col>
      <v-col class="col-10">
        <LoadingComponent v-if="!supplier" />
        <v-row v-if="supplier">
          <v-col>
            <v-card>
              <v-card-title>
                <p>{{ supplier.name }}</p>
              </v-card-title>
              <v-card-text>
                <v-row>
                  <v-col class="col-3">
                    <p class="font-weight-bold">Address</p>
                    <p>{{ supplier.name }}</p>
                  </v-col>
                  <v-col class="col-3">
                    <p class="font-weight-bold">Phone Number</p>
                    <p>{{ supplier.phone_number }}</p>
                  </v-col>
                  <v-col class="col-3">
                    <p class="font-weight-bold">Email</p>
                    <p>{{ supplier.email }}</p>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
        <v-row v-if="supplier">
          <v-col>
            <v-card>
              <v-card-title>
                <p>Sites</p>
              </v-card-title>
              <v-card-text>
                <v-row>
                  <v-col class="col-12">
                    <v-select v-model="supplier.sites" :items="items" attach chips multiple item-text="name"
                      item-value="id" @change="updateSites()">
                      <template #selection="{ item }">
                        <v-chip color="primary">{{ item.name }}</v-chip>
                      </template>
                    </v-select>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <EditSupplierComponent v-bind:editSupplierDialog="editSupplierDialog" v-on:closeForm="closeFormChange($event)"
      v-bind:selectedSupplier="selectedSupplier" v-on:fetchSupplier="fetchSupplier()"
      v-on:triggerSnackBar="triggerSnackBar($event)" />
    <DeleteSupplierComponent v-bind:deleteSupplierDialog="deleteSupplierDialog" v-on:closeForm="closeFormChange($event)"
      v-bind:selectedSupplier="selectedSupplier" v-on:triggerSnackBar="triggerSnackBar($event)" />
    <v-snackbar v-model="snackbar" :timeout="timeout" color="green">
      {{ snackbarText }}

      <template v-slot:action="{ attrs }">
        <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>
<script>
import EditSupplierComponent from "./modals/EditSupplierComponent";
import DeleteSupplierComponent from "./modals/DeleteSupplierComponent";
import LoadingComponent from '../LoadingComponent';
export default {
  watch: {},
  components: {
    EditSupplierComponent,
    DeleteSupplierComponent,
    LoadingComponent,
  },

  data() {
    return {
      supplier: null,
      selectedSupplier: null,
      snackbar: false,
      snackbarText: "",
      timeout: 4000,
      editSupplierDialog: false,
      deleteSupplierDialog: false,
      id: null,
      items: [],
    };
  },
  methods: {
    fetchSupplier() {
      axios
        .get(`/api/suppliers/${this.$route.params.id}`)
        .then((res) => {
          this.supplier = res.data;
        })
        .catch((err) => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    },
    fetchSites() {
      axios.get("/api/sites").then((res) => {
        this.items = res.data;
      });
    },
    updateSites() {
      const supplier = new FormData();
      supplier.append("sites", this.supplier.sites);
      supplier.append("requestType", "sites");
      supplier.append("id", this.id);
      supplier.append("_method", "PUT");
      axios.post(`/api/suppliers/${this.$route.params.id}`, supplier);
    },
    triggerSnackBar($event) {
      this.snackbarText = $event;
      this.snackbar = true;
    },
    closeFormChange(state) {
      this.deleteSupplierDialog = state;
      this.editSupplierDialog = state;
    },
    editSupplierDetails(item) {
      this.selectedSupplier = item;
    },
    triggerSnackBar($event) {
      this.snackbarText = $event;
      this.snackbar = true;
    },
  },
  mounted() {
    this.fetchSupplier();
    this.fetchSites();
    this.id = this.$route.params.id;
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
