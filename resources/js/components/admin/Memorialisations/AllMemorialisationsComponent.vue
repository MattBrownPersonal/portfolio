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
              @click="newArticlMemorialisation = true"
              aria-label="Add New Category"
            >
              <v-icon>add</v-icon>
            </v-btn>
          </v-col>
        </v-row>
        <v-card>
          <v-card-title>
            Categories
            <v-spacer></v-spacer>
            <v-text-field
              v-model="MemorialisationSearch"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            />
          </v-card-title>
          <v-data-table
            :headers="memorialisationHeaders"
            :items="memorialisations"
            :items-per-page="10"
            class="elevation-1"
            :loading="memorialisationsTableLoading"
            loading-text="Loading... Please wait"
            :search="MemorialisationSearch"
          >
          <template v-slot:[`item.edit`]="{ item }">
            <v-btn
              rounded
              color="primary"
              dark
              @click="selectedCategory=item;editCategoryDialog=true"
            >
              Edit
            </v-btn><v-btn
            rounded
            color="red"
            dark
            @click="deleteCategory=item;deleteMemorialisationDialog=true"
          >
            Delete
          </v-btn>

          </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
    <NewMemorialisationComponent
      v-bind:newMemorialisationDialog="newArticlMemorialisation"
      v-on:closeForm="closeFormChange($event)"
      v-on:fetchMemorialisations="fetchMemorialisations()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
    <EditMemorialisationComponent
      v-bind:editCategoryDialog="editCategoryDialog"
      v-bind:selectedCategory="selectedCategory"
      v-on:closeForm="closeFormChange($event)"
      v-on:fetchMemorialisations="fetchMemorialisations()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
    <DeleteMemorialisationComponent
      v-bind:deleteMemorialisationDialog="deleteMemorialisationDialog"
      v-bind:category="deleteCategory"
      v-on:closeForm="closeFormChange($event)"
      v-on:fetchMemorialisations="fetchMemorialisations()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
    <v-snackbar v-model="snackbar" :timeout="timeout">
      {{ snackbarText }}

      <template v-slot:actions="{ attrs }">
        <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
import NewMemorialisationComponent from "./modals/NewMemorialisationComponent.vue";
import EditMemorialisationComponent from "./modals/EditMemorialisationComponent.vue";
import DeleteMemorialisationComponent from "./modals/DeleteMemorialisationComponent.vue";
export default {
  components: {
    NewMemorialisationComponent,
    EditMemorialisationComponent,
    DeleteMemorialisationComponent,
},
  data() {
    return {
      memorialisationHeaders: [
        { text: "Title", align: "start", value: "name", width: "50%" },
        { text: "Actions", align: "end", value: "edit", width: "50%" },
      ],
      memorialisations: [],
      editCategoryDialog: false,
      selectedCategory: null,
      memorialisationsTableLoading: false,
      MemorialisationSearch: "",
      snackbar: false,
      snackbarText: "",
      timeout: 4000,
      deleteMemorialisationDialog: false,
      deleteCategory: null,
      id: null,
      permissions: [],
      newArticlMemorialisation: false,
    };
  },
  methods: {
    fetchMemorialisations() {
      axios.get("/api/memorialisations").then((res) => {
        this.memorialisations = res.data;
      });
    },
    closeFormChange(state) {
      this.newArticlMemorialisation = state;
      this.editCategoryDialog = state;
      this.deleteMemorialisationDialog = state;
    },
    triggerSnackBar($event) {
      this.snackbarText = $event;
      this.snackbar = true;
    },
    delete() {
      axios.post("/api/memorialisations/" + this.deleteCategory.id, {
          _method: "DELETE",
        })
        .then((res) => {
          this.close();
          this.triggerSnackBar(res.data.message);
        })
        .catch((err) => {
          this.triggerSnackBar(err.response.data.errors);
        });
    },
  },
  created() {
    this.fetchMemorialisations();
  },
};
</script>
