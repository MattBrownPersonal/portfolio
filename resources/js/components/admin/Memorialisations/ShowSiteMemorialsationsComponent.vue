<template>
  <div class="px-5">
    <v-row>
      <v-row class="addNewButtonWrapper">
        <v-col class="col-md-4 offset-4 d-flex justify-center" no-gutters>
          <v-btn
          fab fixed right bottom
          color="primary"
          dark
          @click="newSiteMemorialisationDialog = true"
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
        <v-card>
          <v-card-title>
          Categories for {{ site.name }}
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
            :headers="siteMemorialisationsHeaders"
            :items="siteMemorialisations"
            :items-per-page="10"
            class="elevation-1"
            :loading="sitesTableLoading"
            loading-text="Loading... Please wait"
            :search="siteSearch"
            >
            <template v-slot:[`item.showProducts`]="{item}">
              <v-btn
                rounded
                color="primary"
                dark
                :to="{ name: 'showsiteproducts', params: { category:item.memorialisation_id, id: site.id }}"
              >
                View Products
              </v-btn>
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
      <NewSiteCategoryComponent 
        v-bind:newSiteMemorialisationDialog="newSiteMemorialisationDialog" 
        v-bind:selectedSite = site.id
        v-on:closeForm="closeFormChange($event)"
        v-on:fetchMemorialisations="fetchSiteMemorialisations()"
        v-on:triggerSnackBar="triggerSnackBar($event)"
      />
      
  </div>
</template>
<script>
import NewSiteCategoryComponent from './modals/NewSiteCategoryComponent'  
import UsefulLinksComponent from '../UsefulLinksComponent.vue'
  export default {
    components: {
      NewSiteCategoryComponent,   
      UsefulLinksComponent, 
    },
  data () {
    return {
      siteMemorialisationsHeaders: [
        { text: 'Category Name',align: 'start', value: 'memorialisation.name', width: '25%' },
        { text: 'Show Products',value: 'showProducts', width: '25%' },
        
      ],
      siteMemorialisations: [],
      sitesTableLoading: false,
      newSiteMemorialisationDialog: false,
      userSearch: '',
      siteSearch: '',
      selectedSite: '',
      site: '',
      snackbar: false,
      snackbarText: '',
      timeout: 6000,
    }
  },
  mounted () {
    this.sitesTableLoading = true;
    this.fetchSiteName();
    this.fetchSiteMemorialisations();
  },
  methods: {
    fetchSiteMemorialisations() {
      axios.get(`/api/memorialisations/${this.$route.params.id}`)
        .then(res => {
          this.sitesTableLoading = false;
          this.siteMemorialisations = res.data;
        })
        .catch(err => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    },
    fetchSiteName() {
      axios.get(`/api/site/${this.$route.params.id}`)
      .then(res => {
          this.site = res.data;
      })
        .catch(err => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    },
    closeFormChange(state) {
      this.newSiteMemorialisationDialog = state;
    },
    triggerSnackBar($event) {
      this.snackbarText = $event
      this.snackbar = true;
    },
  },
}
  
</script>
<style scoped>
.crudMenu{
    list-style-type:none;
}
.actionLink {
    cursor: pointer;
}
</style>