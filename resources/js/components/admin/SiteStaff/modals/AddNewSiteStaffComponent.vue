<template>
  <v-dialog
    v-model="newSiteStaffDialog"
    persistent
    content-class="overflow-visible"
    max-width="600px"
  >
      <v-card>
          <v-card-title>
              <span class="text-h5">Add New Site Staff for {{ siteName }}</span>
          </v-card-title>
          <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
              {{ warningMessage }}
          </v-alert>
          <v-form id="newUser" ref="newUser" v-model="valid">
              <v-card-text>
                  <v-container>
                      <v-row>
                          <v-col
                          cols="12"
                          sm="6"
                          >
                          <v-text-field
                              label="First name*"
                              required
                              v-model='firstname'
                          ></v-text-field>
                          </v-col>
                          <v-col
                          cols="12"
                          sm="6"
                          >
                          <v-text-field
                              label="Last name*"
                              required
                              v-model='lastname'
                          ></v-text-field>
                          </v-col>
                          <v-col cols="6">
                          <v-text-field
                              label="Email*"
                              required
                              v-model='email'
                          ></v-text-field>
                          </v-col>
                          <v-col cols="6">
                            <v-select :items="roleOptions" label="Role*"
                            multiple required :rules="[() => !!roles || 'This field is required']" :error-messages="errorMessages"
                            v-model='roles' item-text="name" item-value="id"></v-select>
                        </v-col>
                          <v-col cols="12" sm="6">
                            <div>
                              <label class="typo__label">Site(s)*</label>
                              <vue-multiselect 
                                v-model="selectedSites" 
                                tag-placeholder="Add this as new tag"
                                :close-on-select="false" 
                                :clear-on-select="false" 
                                placeholder="Search or add a tag" 
                                label="name" 
                                track-by="id" 
                                :options="userSites" 
                                :multiple="true"
                            ></vue-multiselect>
                            </div>
                          </v-col>
                      </v-row>
                  </v-container>
              <small>*indicates required field</small>
              </v-card-text>
              <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                  color="blue darken-1"
                  text
                  @click="closeForm(); clearForm()"
                  >
                  Close
                  </v-btn>
                  <v-btn
                  color="blue darken-1"
                  text
                  :disabled="!canSave"
                  @click="sendSiteStaffForm();"
                  >
                  Save
                  </v-btn>
              </v-card-actions>
          </v-form>
      </v-card>
  </v-dialog>
</template>
<script>

export default ({
  props: ['newSiteStaffDialog', 'siteId', 'siteName', 'canCreate'],
  data() {
      return {
          firstname: '',
          lastname: '',
          email: '',
          warningMessage: false,
          selectedSites: [],
          userSites: [],
          saveEnabled: true,
          valid: false,
          roleOptions: [],
          errorMessages: '',
          roles: [],
      }
  },
  computed: {
    canSave() {
      return this.valid && this.saveEnabled && this.selectedSites.length > 0;
    }
  },
  methods: {
      sendSiteStaffForm(){
        if(this.$refs.newUser.validate()) {
            let site = new FormData();
            this.saveEnabled = false;
            this.warningMessage = "";
            this.$emit('triggerSnackBar', {message: "Saving...", color: 'gray'});
            site.append('firstname', this.firstname);
            site.append('lastname', this.lastname);
            site.append('email', this.email);
            site.append('roles', this.roles);
            site.append("sites", JSON.stringify(this.selectedSites));
            axios.post(`/api/siteStaff/`, site)
            .then(res => {
                this.$emit('triggerSnackBar', {message: res.data.message, color: 'success'});
                this.$emit('addNewSiteStaff', {site: site, id: this.siteId, name: this.siteName});
                this.saveEnabled = true;
                this.closeForm();
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
                this.saveEnabled = true;
            });
        }
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
      fetchAllSites() {
          axios.get('/api/sites')
          .then(res => {
              this.userSites = res.data;
          })
          .catch(err => {
              this.warningMessage = err.response.data.message;
          });
      },
      clearForm() {
          this.firstname = '';
          this.lastname = '';
          this.email = '';
          this.warningMessage = false;
          this.selectedSites = [];
          this.roles = [];
      },
      closeForm() {
        this.$emit('closeForm', false)
        this.clearForm()
      }
  },
  mounted () {
      this.fetchAllSites();
      this.fetchRoles();
  }
})
</script>
