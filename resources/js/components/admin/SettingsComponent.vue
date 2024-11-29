<template>
  <div class="px-5">
    <v-row>
      <v-col class="col-12" no-gutters>
        <v-card>
          <v-card-title>
          Settings
          <v-spacer></v-spacer>
          <v-text-field
            v-model="SettingsSearch"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
          />
        </v-card-title>
        <v-data-table
          :headers="settingsHeaders"
          :items="settings"
          :items-per-page="10"
          class="elevation-1"
          :loading="settingsTableLoading"
          loading-text="Loading... Please wait"
          :search="SettingsSearch"
        >
          <template v-slot:[`item.value`]="props">
            <v-edit-dialog
              :return-value.sync="props.item.value"
              large
              persistent
              @save="save(props.item.value, props.item.id)"
            >
              <div>{{ props.item.value }}</div>
              <template v-slot:input>
                <div class="mt-4 text-h6">
                  Update
                </div>
                <v-textarea
                  v-model="props.item.value"
                  label="Edit"
                  single-line
                  counter
                  autofocus
                ></v-textarea>
              </template>
            </v-edit-dialog>
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
  </div>
</template>
<script>
export default {
  data() {
    return {
      settingsHeaders: [
        { text: "Setting Name", align: "start", value: "key", width: "40%" },
        { text: "Setting", value: "value", width: "60%" },
      ],
      settings: [],
      selectedsettings: "",
      settingsTableLoading: false,
      editSettingsDialog: false,
      SettingsSearch: "",
      snackbar: false,
      snackbarText: "",
      timeout: 4000,
      id: null,
    };
  },
  mounted() {
    this.settingsTableLoading = true;
    this.fetchSettings();
  },
  methods: {
    fetchSettings() {
      axios.get("/api/settings").then((res) => {
        this.settingsTableLoading = false;
        this.settings = res.data.settings;
        this.id = res.data.id;
      });
    },
    editProductCategoryDetails(item) {
      this.selectedproductcategory = item;
    },
    closeFormChange(state) {
      this.selectedsettings = state;
    },
    triggerSnackBar($event) {
      this.snackbarText = $event;
      this.snackbar = true;
    },
    save(item, id) {
      const setting = new FormData();
      setting.append("name", item);
      setting.append("id", id);
      setting.append("_method", "PUT");
      axios
        .post(`/api/settings/${id}`, setting)
        .then((res) => {
          this.snackbar = true;
          this.snackbarText = res.data.message;
        })
        .catch((err) => {
          this.snackbar = true;
          this.snackbarText = "Error saving setting";
        });
    },
  },
};
</script>
