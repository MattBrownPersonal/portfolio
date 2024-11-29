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
                  <span class="actionLink" @click="editUserDialog = true">
                    <v-icon left>mdi-pencil</v-icon>
                    Edit
                  </span>
                </v-list-item-title>
                <v-list-item-title class="pb-4">
                  <span class="actionLink" @click="deleteUserDialog = true">
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
        <UsefulLinksComponent v-bind:site="site" />
      </v-col>
      <v-col class="col-10">
        <v-row>
          <v-col>
            <v-card>
              <v-card-title>
                <p>User Details</p>
              </v-card-title>
              <v-card-text>
                <v-row>
                  <v-col class="col-3">
                    <p class="font-weight-bold">First Name</p>
                    {{ user.firstname }}
                  </v-col>
                  <v-col class="col-3">
                    <p class="font-weight-bold">Last Name</p>
                    {{ user.lastname }}
                  </v-col>
                  <v-col class="col-3">
                    <p class="font-weight-bold">Email</p>
                    {{ user.email }}
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
        <v-row>
          <v-col>
            <v-card>
              <v-card-text>
                <v-row>
                  <v-col class="col-6">
                    <p class="font-weight-bold">Roles</p>
                    <v-select
                      v-model="user.roles"
                      :items="items"
                      attach
                      chips
                      multiple
                      item-text="name"
                      item-value="id"
                      @change="updateUser('roles')"
                    >
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
        <v-row>
          <v-col>
            <v-card>
              <v-card-text>
                <v-row>
                  <v-col class="col-6">
                    <p class="font-weight-bold">Sites</p>
                      <div>
                        <label class="typo__label">Site(s)*</label>
                        <vue-multiselect 
                          v-model="user.sites" 
                          tag-placeholder="Add this as new tag" 
                          :close-on-select="false" 
                          :clear-on-select="false" 
                          placeholder="Search or add a tag" 
                          label="name" 
                          track-by="id" 
                          :options="sites" 
                          :multiple="true"
                          @input="updateUser('sites')"
                        ></vue-multiselect> 
                      </div>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
    <v-snackbar v-model="snackbar" :timeout="timeout" color="green">
      {{ snackbarText }}
      <template v-slot:action="{ attrs }">
        <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
    <DeleteUserComponent
      v-bind:deleteUserDialog="deleteUserDialog"
      v-on:closeForm="closeFormChange($event)"
      v-bind:user="user"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
    <EditUserComponent
      v-bind:editUserDialog="editUserDialog"
      v-on:closeForm="closeFormChange($event)"
      v-on:fetchUser="fetchUser"
      v-bind:user="user"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
  </div>
</template>
<script>
import DeleteUserComponent from "./modals/DeleteUserComponent.vue";
import EditUserComponent from "./modals/EditUserComponent.vue";
import UsefulLinksComponent from "../UsefulLinksComponent.vue";

export default {
  components: {
    DeleteUserComponent,
    EditUserComponent,
    UsefulLinksComponent,
  },
  data() {
    return {
      user: [],
      snackbar: false,
      snackbarText: "",
      timeout: 4000,
      items: [],
      sites: [],
      site: null,
      value: [],
      deleteUserDialog: false,
      editUserDialog: false,
    };
  },
  methods: {
    fetchUser() {
      axios
        .get(`/api/users/${this.$route.params.id}`)
        .then((res) => {
          this.usersTableLoading = false;
          this.user = res.data;
        })
        .catch((err) => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    },
    updateUser(requestType) {
      let user = new FormData();
      for (let i = 0; i < this.user.roles.length; i++) {
        user.append("roles[]", this.user.roles[i]);
      }
      for (let i = 0; i < this.user.sites.length; i++) {
        user.append("sites[]", this.user.sites[i].id);
      }
      user.append("requestType", requestType);
      user.append("id", this.user.id);
      user.append("_method", "PUT");
      axios.post(`/api/users/${this.user.id}`, user);
    },
    triggerSnackBar($event) {
      this.snackbarText = $event;
      this.snackbar = true;
    },
    fetchRoles() {
      axios.get("/api/roles").then((res) => {
        this.items = res.data;
      });
    },
    fetchSites() {
      axios.get("/api/sites").then((res) => {
        this.sites = res.data;
      });
    },
    updateSites() {
      axios.post(`/api/storeUserSites/${this.user.id}`, {
        sites: this.user.sites,
      });
    },
    fetchStyleName() {
      axios.get(`/api/sites/${this.$route.params.id}`)
        .then(res => {
          this.site = res.data.site;
        })
        .catch(err => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    }, 
    closeFormChange(state) {
      this.deleteUserDialog = state;
      this.editUserDialog = state;
    },
  },
  mounted() {
    this.fetchUser();
    this.fetchRoles();
    this.fetchSites();
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
