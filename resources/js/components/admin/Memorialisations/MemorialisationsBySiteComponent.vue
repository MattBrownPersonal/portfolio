<template>
  <div class="px-5">
    <v-row>
      <v-col class="col-8 offset-2">
        <v-card>
          <v-card-title>
          Categories By Site
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
          :headers="siteHeaders"
          :items="sites"
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
              :to="{ name: 'showsitemorialisations', params: { id: item.id } }"
            >
              View Categories
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
  </div>
</template>
<script>
export default {
  data() {
    return {
      siteHeaders: [
        { text: "Name", align: "start", value: "name", width: "33%" },
        { text: "Show Products", value: "showProducts", width: "33%" },
      ],
      sites: [],
      sitesTableLoading: false,
      siteSearch: "",
      selectedSite: "",
      siteName: "",
      siteId: "",
      snackbar: false,
      snackbarText: "",
      timeout: 6000,
    };
  },
  mounted() {
    this.sitesTableLoading = true;
    this.fetchSites();
  },
  methods: {
    fetchSites() {
      axios
        .get("/api/sites")
        .then((res) => {
          this.sitesTableLoading = false;
          this.sites = res.data;
        })
        .catch((err) => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    },
  },
};
</script>
