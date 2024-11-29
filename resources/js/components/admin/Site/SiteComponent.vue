<template>
  <div class="px-5">
    <v-row>
      <v-col class="col-12" no-gutters>
        <v-row class="addNewButtonWrapper">
          <v-col class="col-md-4 offset-4 d-flex justify-center" no-gutters>
            <v-btn
            fab fixed right bottom
            color="primary"
            dark
            @click="newSiteDialog = true"
            >
            <v-icon>add</v-icon>
            </v-btn>
          </v-col>
        </v-row>
        <v-card>
          <v-card-title>
          Sites
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
            <template v-slot:[`item.edit`]="{item}">
              <v-btn
                v-if="item.uses_categories === 1 && item.products.find(x => x.memorialisation_id === null)"
                rounded
                color="error"
                dark
                :to="{ name: 'showstaff', params: { id:item.id }}"
              >
                View Site Info
              </v-btn>
              <v-btn
                v-else
                rounded
                color="primary"
                dark
                :to="{ name: 'showstaff', params: { id:item.id }}"
              >
                View Site Info
              </v-btn>
            </template>
            <template v-slot:[`item.message`]="{ item }">
              <span v-if="item.uses_categories === 1 && item.products.find(x => x.memorialisation_id === null)">Site has products without categories </span>
            </template>
          </v-data-table>
        </v-card>
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
      <!-- CRUD forms for Sites -->
      <NewSiteComponent 
        v-bind:newSiteDialog="newSiteDialog" 
        v-on:closeForm="closeFormChange($event)"
        v-on:fetchSites="fetchSites()"
        v-on:triggerSnackBar="triggerSnackBar($event)"
      />
      
      <DeleteSiteComponent
        v-bind:deleteSiteDialog="deleteSiteDialog"
        v-on:closeForm="closeFormChange($event)"
        v-bind:selectedSite="selectedSite"
        v-on:fetchSites="fetchSites()"
        v-on:fetchUsers="viewAllSiteStaff()"
        v-on:triggerSnackBar="triggerSnackBar($event)"
      />
      
  </div>
</template>
<script>
    import NewSiteComponent from './modals/AddNewSiteComponent'    
    import DeleteSiteComponent from './modals/DeleteSiteComponent'
   
    export default {
        components: {
            NewSiteComponent,            
            DeleteSiteComponent,
        },
    data () {
      return {
        siteHeaders: [
          { text: 'Name',align: 'start',value: 'name', width: '33%' },
          { text: 'Site',value: 'edit', width: '33%' },
          { text: 'Message', value: 'message', width: '33%' },
        ],
        sites: [],
       
        sitesTableLoading: false,
        newSiteDialog: false,
        editSiteDialog: false,
        deleteSiteDialog: false,
        userSearch: '',
        siteSearch: '',
        selectedSite: '',
        siteName: '',
        siteId: '',
        snackbar: false,
        snackbarText: '',
        timeout: 6000,
      }
    },
    mounted () {
      this.sitesTableLoading = true;
      this.fetchSites();
    },
    methods: {
      fetchSites() {
        axios.get('/api/sites')
          .then(res => {
            this.sitesTableLoading = false;
            this.sites = res.data;
          })
          .catch(err => {
            this.snackbar = true;
            this.snackbarText = err.response.data.message;
          });
      },
      editSiteStaffDetails(item) {
        this.selectedStaff = item;
      },
      closeFormChange(state) {
        this.newSiteDialog = state;
        this.editSiteDialog = state;
        this.deleteSiteDialog = state;
      },
      
      triggerSnackBar($event) {
        this.snackbarText = $event
        this.snackbar = true;
      },
    }
  }
  
</script>