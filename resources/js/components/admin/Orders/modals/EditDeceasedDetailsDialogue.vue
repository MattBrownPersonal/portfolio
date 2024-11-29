<template>
    <v-dialog v-model="editDeceasedDetailsDialogue" max-width="1000px" persistent>
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit Deceased Name</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{ warningMessage }}
            </v-alert>
            <v-form ref="form" v-model="valid">
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="4">
                                <v-text-field label="First Name*" v-model='firstName' required
                                    :rules="textInputRules"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-text-field label="Middle Name" v-model='middleName'></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-text-field label="Last Name*" v-model='lastName' required
                                    :rules="textInputRules"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeForm();">
                        Close
                    </v-btn>
                    <v-btn color="blue darken-1" text :disabled=!valid @click="submit();">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>

export default ({
    props: ['editDeceasedDetailsDialogue', 'order'],
    data: function () {
        return {
            firstName: null,
            middleName: null,
            lastName: null,
            warningMessage: '',
            textInputRules: [v => !!v || 'Field is required'],
            valid: true
        }
    },
    methods: {
        submit() {
            if (this.$refs.form.validate()) {
                const deceased = new FormData();
                deceased.append('firstName', this.firstName);
                deceased.append('middleName', this.middleName);
                deceased.append('lastName', this.lastName);
                deceased.append('id', this.order.deceased.id);
                deceased.append('type', 'editDeceased');

                axios.post(`/api/updateOrder/${this.order.id}`, deceased)
                    .then(res => {
                        this.closeForm();
                        this.$emit('fetchOrder');
                        this.$refs.form.reset();
                    })
                    .catch(err => {
                        this.warningMessage = err;
                    });
            }
        },
        closeForm() {
            this.$emit('closeForm', false)
        },
    },
    watch: {
        editDeceasedDetailsDialogue: function (propVal) {
            if (propVal) {
                this.firstName = this.order.deceased.firstname;
                this.middleName = this.order.deceased.middlename;
                this.lastName = this.order.deceased.lastname;
            } else {
                this.firstName = null;
                this.middleName = null;
                this.lastName = null;
            }
            
        }
    }
})
</script>
