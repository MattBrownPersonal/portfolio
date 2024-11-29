<template>
    <div class="px-5">
        <v-row>
            <v-col class="col-12">
                <v-row class="addNewButtonWrapper">
                    <v-col class="col-md-4 offset-4 d-flex justify-center" no-gutters>
                        <v-btn
                        fab fixed right bottom
                        color="primary"
                        dark
                        @click="newDeceasedDialog = true"
                        >
                        <v-icon>add</v-icon>
                        </v-btn>
                    </v-col>
                </v-row>
                <v-card>
                    <v-card-title>
                    Deceased
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="DeceasedSearch"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    />
                    </v-card-title>
                    <v-data-table
                        :headers="deceasedHeaders"
                        :items="allDeceased"
                        :items-per-page="10"
                        class="elevation-1"
                        :loading="deceasedTableLoading"
                        loading-text="Loading... Please wait"
                        :search="DeceasedSearch"
                        >
                        <template v-slot:[`item.view`]="{item}">
                            <v-btn
                                rounded
                                color="primary"
                                dark
                                :to="{ name: 'showdeceased', params: { id:item.id }}"
                            >
                                View
                            </v-btn>
                        </template>
                        <template v-slot:[`item.date_of_birth`]="{item}">
                            {{ item.date_of_birth == null ? 'No Date Entered' : moment(item.date_of_birth).locale('en-gb').format('L') }}
                        </template>
                        <template v-slot:[`item.date_of_death`]="{item}">
                            {{ item.date_of_death == null ? 'No Date Entered' : moment(item.date_of_death).locale('en-gb').format('L') }}
                        </template>
                        <template v-slot:[`item.date_of_service`]="{item}">
                            {{ item.date_of_service == null ? 'No Date Entered' : moment(item.date_of_service).locale('en-gb').format('L') }}
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
        <NewDeceasedComponent
            v-bind:newDeceasedDialog="newDeceasedDialog" 
            v-on:closeForm="closeFormChange($event)"
            v-bind:selectedDeceased="deceased"
            v-on:fetchDeceased="fetchDeceased()"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />
        <v-snackbar
            v-model="snackbar"
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
    </div>
</template>
<script>
import NewDeceasedComponent from './modals/NewDeceasedComponent'
    export default {
    components: {
        NewDeceasedComponent
    },
    data () {
      return {
        deceasedHeaders: [
            { text: 'First Name',align: 'start', value: 'firstname'},  
            { text: 'Middle Name',align: 'start', value: 'middlename'},  
            { text: 'Last Name',align: 'start', value: 'lastname'},        
            { text: 'Date Of Birth',align: 'start', value: 'date_of_birth'},
            { text: 'Date Of Death',align: 'start', value: 'date_of_death'},
            { text: 'Date Of Service',align: 'start', value: 'date_of_service'},
            { text: 'Site',align: 'start', value: 'site.name'},
            { text: 'Actions', value:'view'}  
        ],
        allDeceased: [],
        selectedproductcategory: '',
        deceasedTableLoading: false,
        DeceasedSearch: '',
        deceased: '',
        snackbar: false,
        snackbarText: 'Error generating QR code. Please try again',
        timeout: 4000,
        newDeceasedDialog: false,
        canCreate: null
      }
    },
    mounted () {
      this.deceasedTableLoading = true;
      this.fetchDeceased();
    },
    methods: {
        fetchDeceased() {
        axios.get('/api/deceased')
            .then(res => {
                this.deceasedTableLoading = false;
                this.allDeceased = res.data.deceased;
                this.canCreate = res.data.canCreate;
            })
        }, 
        createNewCode(deceased) {
            axios.post('/api/deceased', deceased)
            .then(res => {
                this.qrcode = res.data;
                this.deceased = deceased;
            })
            .catch(err => {
                this.snackbar = true;
            });
        },
        closeFormChange(state) {
            this.newDeceasedDialog = state;         
        },
        triggerSnackBar($event) {
            this.snackbarText = $event
            this.snackbar = true;
        }
    }
  }
</script>