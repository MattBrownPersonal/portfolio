<template>
  <v-dialog v-model="newUserDialog" persistent max-width="1000px">
    <v-card :height=cardHeight>
      <v-card-title>
        <span class="text-h5">Add New User</span>
      </v-card-title>
      <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
        User's email address already exists.
      </v-alert>
      <v-form id="newUser" ref="newUser" v-model="valid">
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6">
                <v-text-field label="First name*" required :rules="[() => !!firstname || 'This field is required']"
                  :error-messages="errorMessages" v-model='firstname'></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field label="Last name*" required :rules="[() => !!lastname || 'This field is required']"
                  :error-messages="errorMessages" v-model='lastname'></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field label="Email*" required :rules="[() => !!email || 'This field is required']"
                  :error-messages="errorMessages" v-model='email'></v-text-field>
              </v-col>
              <v-col cols="4" sm="6">
                <v-select :items="canCreate ? roleOptions : roleOptions.filter(role => role.id != 1)" label="Role*"
                  multiple required :rules="[() => !!roles || 'This field is required']" :error-messages="errorMessages"
                  v-model='roles' item-text="name" item-value="id" @change="checkForSiteAdmin()"></v-select>
              </v-col>
              <v-col cols="12" sm="12" v-if="showSitesDropDown">
                <vue-multiselect 
                  v-model="chosenSites" 
                  tag-placeholder="Add this as new tag" 
                  :close-on-select="false"
                  :clear-on-select="false" 
                  placeholder="Search or add a site for this user" 
                  label="name" 
                  track-by="id" 
                  :options="siteOptions" 
                  :multiple="true"
                  ></vue-multiselect>
              </v-col>
            </v-row>
            <v-row v-if="warningMessage">
              <v-col class="cols-12">
                <h5>This user already exists in another site. Do you want to add them to your sites?</h5>
                <v-btn @disabled="!valid" color="success" @click="sendUserForm();">
                  Yes
                </v-btn>
                <v-btn color="error" @click="closeForm(); clearForm()">
                  No
                </v-btn>
              </v-col>
            </v-row>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions v-if="!warningMessage">
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="closeForm(); clearForm()">
            Close
          </v-btn>
          <v-btn color="blue darken-1" text :disabled="!canSave" @click.prevent="sendUserForm();">
            Save
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
    <v-snackbar v-model="snackbar">
      {{ snackbarText }}
      <template v-slot:action="{ attrs }">
        <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </v-dialog>
</template>
<script>

export default ({
  props: ['newUserDialog', 'canCreate', 'sites'],
  data() {
    return {
      firstname: '',
      lastname: '',
      email: '',
      id: '',
      roles: [],
      roleOptions: [],
      showSitesDropDown: false,
      chosenSites: [],
      siteOptions: [],
      warningMessage: false,
      errorMessages: '',
      snackbar: false,
      snackbarText: '',
      saveEnabled: true,
      valid: false,
      cardHeight: '500px'
    }
  },
  computed: {
    canSave() {
      return this.valid && this.saveEnabled && this.roles.length > 0;
    }
  },
  methods: {
    checkForSiteAdmin() {
      if (this.roles.includes(3)) {
        this.showSitesDropDown = true;
        this.cardHeight = '800px';
      } else {
        this.showSitesDropDown = false;
      }
    },
    sendUserForm() {
      if (this.$refs.newUser.validate()) {
        this.saveEnabled = false;
        this.snackbarText = "Saving...";
        this.snackbar = true;
        let user = new FormData();
        user.append('firstname', this.firstname);
        user.append('lastname', this.lastname);
        user.append('email', this.email);
        user.append('roles', this.roles);
        user.append('sites', JSON.stringify(this.chosenSites));
        axios.post('/api/users', user)
          .then(res => {
            this.closeForm();
            this.clearForm();
            this.$emit('fetchUsers');
            this.$emit('savedMessage', res.data.message);
            this.saveEnabled = true;
          })
          .catch(err => {
            if (err.response.data.message) {
              this.snackbarText = err.response.data.message;
              this.snackbar = true;

            }
            else {
              this.snackbarText = err.message;
              this.snackbar = true;
            }
            this.saveEnabled = true;
          });
      }
    },
    fetchSites() {
      axios.get('/api/sites')
        .then(res => {
          this.siteOptions = res.data;
        })
        .catch(err => {
          this.warningMessage = err.response.data.message;
        });
    },
    fetchRoles() {
      axios.get('/api/roles')
        .then(res => {
          this.roleOptions = res.data;
        })
        .catch(err => {
          this.warningMessage = err.response.data.message;
        });
    },
    clearForm() {
      this.firstname = '';
      this.lastname = '';
      this.email = '';
      this.roles = '';
    },
    closeForm() {
      this.$emit('closeForm', false);
      this.warningMessage = false;
    }
  },
  mounted() {
    this.fetchRoles();
    this.fetchSites();
  },
})
</script>
