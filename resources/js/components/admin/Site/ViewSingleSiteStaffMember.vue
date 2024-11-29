<template>
    <v-app class="px-4">
        <v-row>
            <v-col class="col-2">
                <v-card elevation="2">
                    <v-card-text>
                        <p class="text-h6">Actions</p>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>                               
                                <v-list-item-title class="pb-4">
                                    <span class="actionLink" @click="deleteSiteStaffDialog = true;">
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
                <v-row>
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Users</p>
                            </v-card-title>
                        </v-card>               
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card elevation="2">                       
                            <v-card-text>
                                <v-row>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">First Name</p>
                                        {{ staffMember.firstname }}
                                    </v-col>                                  
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">Last Name</p>
                                        {{ staffMember.lastname }}
                                    </v-col>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">Email</p>
                                        {{ staffMember.email }}
                                    </v-col>  
                                    <v-col class="col-3">
                                        <p></p>
                                        <v-btn 
                                        class="primary" 
                                        :to="{ name: 'siteuser', params: { id: staffMember.id }}"
                                         @click="editSiteStaffDialog = true;"
                                        >
                                        Edit User Details
                                        </v-btn>
                                    </v-col>                           
                                </v-row>
                            </v-card-text>
                        </v-card> 
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card elevation="2">                       
                            <v-card-text>
                                <v-row>
                                    <v-col class="col-6">
                                        <p class="font-weight-bold">Sites</p>
                                        <v-select
                                            v-model="staffMember.sites"
                                            :items="items"
                                            attach
                                            chips
                                            multiple
                                            item-text="name"
                                            item-value="id"
                                            @change="updateSites()"
                                        ></v-select>
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
        <DeleteSiteStaffComponent
            v-bind:deleteSiteStaffDialog="deleteSiteStaffDialog"
            v-on:closeForm="closeFormChange($event)"
            v-bind:staffMember="staffMember"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />
        <EditSiteStaffComponent
            v-bind:editSiteStaffDialog="editSiteStaffDialog"
            v-on:closeForm="closeFormChange($event)"
            v-on:fetchUser="fetchStaffMember"
            v-bind:staffMember="staffMember"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />
    </v-app>
</template>
<script>
import DeleteSiteStaffComponent from '../SiteStaff/modals/DeleteSiteStaffComponent.vue';
import EditSiteStaffComponent from '../SiteStaff/modals/EditSiteStaffComponent.vue';     

export default {
    components: {
        DeleteSiteStaffComponent,
        EditSiteStaffComponent,
    },
    data () {
        return {
            staffMember: [],
            snackbar: false,
            snackbarText: '',
            timeout: 4000,   
            items: [],
            value: [],     
            deleteSiteStaffDialog: false,
            editSiteStaffDialog: false,
        }
    },
    methods: {
        fetchStaffMember() {
            axios.get(`/api/users/${this.$route.params.id}`)
            .then(res => {
                this.sitesTableLoading = false;
                this.staffMember = res.data;
            })
            .catch(err => {
                this.snackbar = true;
                this.snackbarText = err.response.data.message;
            });
        },
        fetchSites() {
            axios.get('/api/sites')
            .then( res => {
                this.items = res.data
            });
        },
        updateSites() {
            axios.post(`/api/storeUserSites/${this.staffMember.id}`, { sites: this.staffMember.sites })
        },
        triggerSnackBar($event) {
            this.snackbarText = $event
            this.snackbar = true;
        },
        closeFormChange(state) {
            this.deleteSiteStaffDialog = state;           
            this.editSiteStaffDialog = state;
        },
    },
    mounted () {
        this.fetchStaffMember();
        this.fetchSites();
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