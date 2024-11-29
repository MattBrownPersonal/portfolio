<template>
    <v-dialog
      v-model="editUserDialog"
      max-width="1000px"
      persistent
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit User</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{ warningMessage }}
            </v-alert>
            <v-form ref="form" v-model="valid">
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col
                            cols="12"
                            sm="4"
                            >
                            <v-text-field
                                label="First name*"
                                required
                                :rules="textInputRules"
                                v-model='firstname'
                            ></v-text-field>
                            </v-col>
                            <v-col
                            cols="12"
                            sm="4"
                            >
                            <v-text-field
                                label="Last name*"
                                required
                                :rules="textInputRules"
                                v-model='lastname'
                            ></v-text-field>
                            </v-col>
                            <v-col 
                            cols="12"
                            sm="4">
                            <v-text-field
                                label="Email*"
                                required
                                :rules="textInputRules"
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
                    @click="closeForm();"
                    >
                    Close
                    </v-btn>
                    <v-btn
                    color="blue darken-1"
                    text
                    :disabled = !valid
                    @click="submit();"
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
    props: ['editUserDialog', 'user'],
    data: function() {
        return {
            firstname: null,
            lastname : null,
            email: null,
            id: null,
            warningMessage: '',
            showUserDialog: '',
            textInputRules: [v => !!v || 'Field is required'],
            valid: true,
        }
    },
    methods: {
        submit() {
            if (this.$refs.form.validate()) { 
                let user = new FormData();
                    user.append('firstname', this.firstname);
                    user.append('lastname', this.lastname);
                    user.append('email', this.email);
                    user.append('id', this.id);
                    user.append('_method', 'PUT');
                axios.post('/api/users/' + this.id, user)
                .then(res => {
                    this.closeForm();
                    this.clearForm();
                    this.$emit('fetchUser');
                    this.$emit('savedMessage', res.data.message);        
                })
                .catch(err => {
                    this.warningMessage = err.response.data.message;
                });
            }
        },
        clearForm() {
            this.firstname = null;
            this.lastname = null;
            this.email = null;
            this.roles = [];
        },
        closeForm() {
            this.$emit('closeForm', false)
        },
        fetchRoles() {
            axios.get('/api/roles')
            .then(res => {
                this.roleOptions = res.data;
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
            });
        }
    },
    watch: {
        user: function(propVal) {
            this.firstname = propVal.firstname;
            this.lastname = propVal.lastname;
            this.email = propVal.email;            
            this.id = propVal.id;
        },
        editUserDialog: function(enableStatus) {
            this.showUserDialog = enableStatus;
            this.fetchRoles();
        }
    },
    mounted() {
        this.fetchRoles();
    }
})
</script>
