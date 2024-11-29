<template>
  <div class="px-5">
    <v-row>
      <v-row class="addNewButtonWrapper">
        <v-col class="col-md-4 offset-4 d-flex justify-center" no-gutters>
          <v-btn
            fab
            fixed
            bottom
            right
            color="primary"
            dark
            @click="newSiteStaffDialog = true"
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
        <v-card elevation="2">
          <v-card-title>
            <p>Site Staff for {{ siteName }}</p>
          </v-card-title>
          <v-data-table
            :headers="headers"
            :items="allStaff"
            :items-per-page="10"
            class="elevation-1"
            :loading="staffTableLoading"
            loading-text="Loading... Please wait"
            :search="staffSearch"
          >
            <template v-slot:[`item.edit`]="{ item }">
              <v-btn
                rounded
                color="primary"
                dark
                :to="{
                  name: 'viewuser',
                  params: { id: item.id },
                }"
              >
                View
              </v-btn>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
      <v-snackbar v-model="snackbar" :timeout="timeout" :color="snackbarColour">
        {{ snackbarText }}

        <template v-slot:action="{ attrs }">
          <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
            Close
          </v-btn>
        </template>
      </v-snackbar>

      <AddNewSiteStaffComponent
        v-bind:newSiteStaffDialog="newSiteStaffDialog"
        v-bind:canCreate="canCreate"
        v-bind:siteName="siteName"
        v-on:closeForm="closeFormChange($event)"
        v-on:triggerSnackBar="triggerSnackBar($event)"
      />

      <DeleteSiteStaffComponent
        v-bind:deleteSiteStaffDialog="deleteSiteStaffDialog"
        v-on:closeForm="closeFormChange($event)"
        v-bind:staffMember="staffMember"
        v-on:triggerSnackBar="triggerSnackBar($event)"
      />
    </v-row>
  </div>
</template>
<script>
import AddNewSiteStaffComponent from "./modals/AddNewSiteStaffComponent.vue";
import DeleteSiteStaffComponent from "./modals/DeleteSiteStaffComponent.vue";
import UsefulLinksComponent from "../UsefulLinksComponent.vue";
export default {
  components: {
    AddNewSiteStaffComponent,
    DeleteSiteStaffComponent,
    UsefulLinksComponent,
  },
  data() {
    return {
      allStaff: [],
      staffMember: [],
      staffTableLoading: false,
      headers: [
        {
          text: "First Name",
          align: "start",
          value: "firstname",
          width: "25%",
        },
        { text: "Last Name", align: "start", value: "lastname", width: "25%" },
        { text: "Email", align: "start", value: "email", width: "25%" },
        { text: "Actions", value: "edit", width: "25%" },
      ],
      siteName: "",
      staffSearch: "",
      snackbar: false,
      snackbarColour: "green",
      snackbarText: "",
      timeout: 4000,
      items: [],
      value: [],
      newSiteStaffDialog: false,
      deleteSiteStaffDialog: false,
      editSiteStaffDialog: false,
      site: null,
      canCreate: false,
    };
  },
  methods: {
    fetchStaffMember() {
      axios
        .get(`/api/users/${this.$route.params.id}`)
        .then((res) => {
          this.sitesTableLoading = false;
          this.staffMember = res.data;
        })
        .catch((err) => {
          this.snackbar = true;
          this.snackbarColour = "gray";
          this.snackbarText = err.response.data.message;
        });
    },
    fetchAllStaff() {
      axios
        .get(`/api/siteStaff/${this.$route.params.id}`)
        .then((res) => {
          this.sitesTableLoading = false;
          this.allStaff = res.data.staff;
          this.siteName = res.data.sitename;
          if (!this.allStaff.length) this.canCreate = true;
        })
        .catch((err) => {
          this.snackbar = true;
          this.snackbarColour = "gray";
          this.snackbarText = err.message ? err.message : err.response.data.message;
        });
    },
    triggerSnackBar($event) {
      if($event.message) {
        this.snackbarText = $event.message;
        this.snackbarColour = $event.color;
      }
      else {
        this.snackbarColour = "green";
        this.snackbarText = $event;
      }
      this.snackbar = true;
    },
    fetchStyleName() {
      axios
        .get(`/api/sites/${this.$route.params.id}`)
        .then((res) => {
          this.site = res.data.site;
        })
        .catch((err) => {
          this.snackbar = true;
          this.snackbarColour = "gray";
          this.snackbarText = err.response.data.message;
        });
    },
    closeFormChange(state) {
      this.sitesTableLoading = true;
      this.newSiteStaffDialog = state;
      this.deleteSiteStaffDialog = state;
      this.editSiteStaffDialog = state;
      this.fetchAllStaff();
    },
  },
  mounted() {
    this.fetchAllStaff();
    this.fetchStaffMember();
    this.fetchStyleName();
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

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style >
  #listbox-null{
    padding-left: 0 !important;
  }
  .multiselect__content-wrapper{
    width: fit-content;
  }
  .multiselect__tag{
    background-color: #1c836f !important;
    border-color: #1c836f !important;
    align-items: center;
    display: inline-flex;
    color: #fff;
    border-radius: 16px;
    font-size: 14px;
    height: 32px;
  }
  .multiselect__tag-icon{
    align-items: center;
    display: inline-flex;
    padding-left: 3px;
  }
</style>
