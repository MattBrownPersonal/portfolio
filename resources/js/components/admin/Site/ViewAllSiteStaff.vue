<template>
    <v-app class="px-4">
         <v-row class="addNewButtonWrapper">
          <v-col class="col-12" no-gutters>
            <v-btn
            fab fixed right bottom
            color="primary"
            dark
            @click="newUserDialog = true"
            >
              <v-icon>add</v-icon>
            </v-btn>
          </v-col>
        </v-row>
        <v-row>
            <v-col class="col-2">
                <v-card elevation="2">
                    <v-card-text>
                        <p class="text-h6">Actions</p>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title class="pb-4">
                                    <span class="actionLink" @click="editSiteStyleDialog= true; editSiteStyleDetails()">
                                        <v-icon left>mdi-pencil</v-icon>
                                        Edit
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
                 <v-row>
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p v-if="siteName">{{siteName.name}} Site Details</p>
                            </v-card-title>
                        </v-card>               
                    </v-col>
                </v-row>

                <v-row v-if="!style">
                    <v-col>
                        <v-card elevation="2">
                            <v-card-text>    
                                <span class="text-h6">There are no styles listed for this site.</span>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>    
               
                <!-- <v-row> -->
                    <v-row v-if="style"> 
                        <v-col>
                            <v-card elevation="2">
                            <v-card-title>Site Logo</v-card-title>
                            <v-img v-if="style.logo_file" :src="style.logo_file"  max-height="300" max-width="300"></v-img>
                            <v-img v-else :src="style.logo_url"  max-height="300" max-width="300"></v-img>
                            </v-card>               
                        </v-col>
                    </v-row>
                    <v-row v-if="style">
                        <v-col>
                            <v-card elevation="2">
                                <v-card-text>
                                    <v-row>
                                        <v-col class="col-3">
                                            <span class="text-h6">Primary Colour</span>
                                           <v-sheet
                                            v-bind:color="style.primary_colour"
                                            elevation="3"
                                            height="100"
                                            width="100"
                                            ></v-sheet>
                                        
                                           
                                        </v-col>
                                
                                        <v-col class="col-3">
                                            <span class="text-h6">Secondary Colour</span>
                                            <v-sheet
                                            v-bind:color="style.secondary_colour"
                                            elevation="3"
                                            height="100"
                                            width="100"
                                            ></v-sheet>
                                        </v-col>

                                        <v-col class="col-3">
                                            <span class="text-h6">Font Colour</span>
                                             <v-sheet
                                            v-bind:color="style.font_colour"
                                            elevation="3"
                                            height="100"
                                            width="100"
                                            ></v-sheet>
                                          
                                        </v-col>

                                        <v-col class="col-3">
                                            <span class="text-h6">Background Colour</span>
                                            <v-sheet
                                            v-bind:color="style.background_colour"
                                            elevation="3"
                                            height="100"
                                            width="100"
                                            ></v-sheet>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>               
                        </v-col>
                    </v-row>
                <!-- </v-row> -->

                <v-row>
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Users' Details</p>
                            </v-card-title>
                        </v-card>               
                    </v-col>
                </v-row>
                <v-row v-if="!allStaff.length">
                    <v-col>
                        <v-card elevation="2">
                            <v-card-text>
                                <span class="text-h6"> There are no users listed for this site. </span>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-for="staff in allStaff" :key="staff.id">
                    <v-col>
                        <v-card elevation="2">                       
                            <v-card-text>
                                <v-row>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">First Name</p>
                                        {{ staff.firstname }}
                                    </v-col>                                  
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">Last Name</p>
                                        {{ staff.lastname }}
                                    </v-col>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">Email</p>
                                        {{ staff.email }}
                                    </v-col>
                                    <v-col class="col-3">
                                        <p></p>
                                        <v-btn class="primary" :to="{ name: 'siteuser', params: { id: staff.id }}">View User</v-btn>
                                    </v-col>                                    
                                </v-row>
                            </v-card-text>
                        </v-card> 
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
         <v-snackbar
            v-model="snackbar"
            :timeout="timeout"
            color="green"
        >
            {{ snackbarText }}

            <template v-slot:action="{ attrs }">
            <v-btn
                color="white"
                text
                v-bind="attrs"
                @click="snackbar = false"
            >
                Close
            </v-btn>
            </template>
        </v-snackbar>

        <EditSiteStyleComponent
            v-bind:editSiteStyleDialog="editSiteStyleDialog" 
            v-on:closeForm="closeFormChange($event)"
            v-bind:selectedStyle="style"
            v-bind:selectedSite="siteName"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />

        <NewUserComponent 
        v-bind:newUserDialog="newUserDialog" 
        v-bind:sites="siteId"
        v-bind:canCreate="canCreate"
        v-on:closeForm="closeFormChange($event)"
        v-on:fetchUsers="fetchUsers()"
        v-on:savedMessage="savedMessage($event)"
      />
    </v-app>
</template>
<script>
import EditSiteStyleComponent from './modals/EditSiteStyleComponent'
import NewUserComponent from '../Users/modals/AddNewUserComponent'
export default {
    components: {
            EditSiteStyleComponent,
            NewUserComponent,
        },
    data () {
        return {
            allStaff: [],
            styles: [],
            siteName: null,
            style: null,
            snackbar: false,
            snackbarText: '',
            timeout: 4000,
            editSiteStyleDialog: false, 
            newUserDialog: false,
            canCreate: false,
            siteId: [],         
        }
    },
    methods: {
        fetchAllStaff() {
            axios.get(`/api/siteStaff/${this.$route.params.id}`)
            .then(res => {
                this.sitesTableLoading = false;
                this.allStaff = res.data.staff;
                if(!this.allStaff.length)
                    this.canCreate = true;
            })
            .catch(err => {
                this.snackbar = true;
                this.snackbarText = err.response.data.message;
            });
        },
        
        editSiteStyleDetails() {
        this.fetchStyleName();
        this.fetchAllStyles();
        },
        fetchStyleName(){
             axios.get(`/api/sites/${this.$route.params.id}`)
            .then(res => {
                this.sitesTableLoading = false;
                this.siteName = res.data.site;
                
            })                      
            .catch(err => {
                this.snackbar = true;
                this.snackbarText = err.response.data.message;
            });
        }, 
        fetchAllStyles() {
            axios.get(`/api/siteStyles/${this.$route.params.id}`)
            .then(res => {
                this.sitesTableLoading = false;
                this.style = res.data.style;
            })
            .catch(err => {
                this.snackbar = true;
                this.snackbarText = err.response.data.message;
            });
        },
        triggerSnackBar($event) {
            this.snackbarText = $event
            this.snackbar = true;
        },
        closeFormChange(state) {
            this.newUserDialog = state;
            this.editDeceasedDialog = state;  
            this.editSiteStyleDialog = state;
            this.fetchAllStaff();         
        },
    },
    mounted () {
        this.siteId = [parseInt(this.$route.params.id)];
        this.fetchAllStaff();
        this.fetchStyleName();
        this.fetchAllStyles();
    }
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