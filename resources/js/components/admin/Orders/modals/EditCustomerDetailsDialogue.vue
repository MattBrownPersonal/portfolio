<template>
    <v-dialog v-model="editCustomerDetailsDialogue" max-width="1000px" persistent>
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit Customer Details</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{ warningMessage }}
            </v-alert>
            <v-form ref="form" v-model="valid">
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="4">
                                <v-text-field label="Full name*" v-model='customerName' required
                                    :rules="textInputRules"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-text-field label="Email Address*" v-model='customerEmail' required
                                    :rules="textInputRules"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-text-field label="Phone Number*" v-model='customerPhoneNumber' required
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
    props: ['editCustomerDetailsDialogue', 'order'],
    data: function () {
        return {
            customerName: null,
            customerEmail: null,
            customerPhoneNumber: null,
            warningMessage: '',
            textInputRules: [v => !!v || 'Field is required'],
            valid: true
        }
    },
    methods: {
        submit() {
            if (this.$refs.form.validate()) {
                const customer = new FormData();
                customer.append('name', this.customerName);
                customer.append('email', this.customerEmail);
                customer.append('phone_number', this.customerPhoneNumber);
                customer.append('type', 'editCustomer');

                axios.post(`/api/updateOrder/${this.order.id}`, customer)
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
        editCustomerDetailsDialogue: function (propVal) {
            if (propVal) {
                this.customerName = this.order.name;
                this.customerEmail = this.order.email;
                this.customerPhoneNumber = this.order.phone_number;
            } else {
                this.customerName = null;
                this.customerEmail = null;
                this.customerPhoneNumber = null;
            }
            
        }
    }
})
</script>
