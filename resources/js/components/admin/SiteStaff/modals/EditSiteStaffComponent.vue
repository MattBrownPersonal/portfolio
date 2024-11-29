<template>
    <v-dialog
      v-model="editSiteStaffDialog"
      persistent
      max-width="600px"
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit {{ firstname + ' ' + lastname }}</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                User's email address already exists.
            </v-alert>
            <v-form id="newUser">
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
                            <v-col cols="12">
                            <v-text-field
                                label="Email*"
                                required
                                v-model='email'
                            ></v-text-field>
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
                    @click="sendEditSiteStaffForm(); clearForm()"
                    >
                    Save
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
  props: ["editSiteStaffDialog", "staffMember"],
  data() {
    return {
      id: "",
      firstname: "",
      lastname: "",
      email: "",
      warningMessage: "",
    };
  },
  methods: {
    sendEditSiteStaffForm() {
      let user = new FormData();
      user.append("firstname", this.firstname);
      user.append("lastname", this.lastname);
      user.append("email", this.email);
      user.append("_method", "PUT");
      user.append("id", this.id);
      axios.post("/api/users/" + this.id, user).then((res) => {
        this.closeForm();
        this.$emit("fetchUser");
        this.$emit("triggerSnackBar", res.data.message);
      });
    },
    methods: {
        sendEditSiteStaffForm(){
            let user = new FormData();
            user.append('firstname', this.firstname);
            user.append('lastname', this.lastname);
            user.append('email', this.email);
            user.append('_method', 'PUT');
            user.append('id', this.id);
            axios.post('/api/users/'  + this.id, user)
            .then(res => {
                this.closeForm();
                this.$emit('fetchUser');
                this.$emit('triggerSnackBar', res.data.message);              
            })
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
            this.name = '';
        },
        closeForm() {
            this.$emit('closeForm', false)
        }
    },
    clearForm() {
      this.name = "";
    },
    closeForm() {
      this.$emit("closeForm", false);
    },
  },
  mounted() {
    this.fetchAllSites();
  },
  watch: {
    staffMember: function (propVal) {
      this.firstname = propVal.firstname;
      this.lastname = propVal.lastname;
      this.email = propVal.email;
      this.id = propVal.id;
    },
  },
};
</script>
