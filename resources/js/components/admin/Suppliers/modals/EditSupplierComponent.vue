<template>
    <v-dialog
      v-model="editSupplierDialog"
      max-width="1000px"
      persistent
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit Supplier</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage !== null">
                {{ warningMessage }}
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
                                label="Supplier name*"
                                :rules="[() => !!name || 'This field is required']"
                                :error-messages="errorMessages"
                                v-model='name'
                            ></v-text-field>
                            </v-col>
                            <v-col
                            cols="12"
                            sm="6"
                            >
                            <v-text-field
                                label="Phone Number*"
                                :rules="[() => !!phonenumber || 'This field is required']"
                                :error-messages="errorMessages"
                                v-model='phonenumber'
                            ></v-text-field>
                            </v-col>
                            <v-col
                            cols="12"
                            sm="6"
                            >
                            <v-text-field
                                label="Email*"
                                :rules="[() => !!email || 'This field is required']"
                                :error-messages="errorMessages"
                                v-model='email'
                            ></v-text-field>
                            </v-col>
                            <v-col
                            cols="12"
                            sm="6"
                            >
                            <v-textarea
                                label="Address*"
                                :rules="[() => !!address || 'This field is required']"
                                :error-messages="errorMessages"
                                v-model='address'
                            ></v-textarea>
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
                    @click="sendSupplierForm(); clearForm()"
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
    props: ['editSupplierDialog', 'selectedSupplier'],
    data: function() {
        return {
            name: '',
            address: '',
            phonenumber: '',
            email: '',
            warningMessage: null,
            showSupplierDialog: '',
            errorMessages: '',
        }
    },
    methods: {
        sendSupplierForm(){
            const supplier = new FormData();
            supplier.append('name', this.name);
            supplier.append('address', this.address);
            supplier.append('phonenumber', this.phonenumber);
            supplier.append('email', this.email);
            supplier.append('id', this.id);
            supplier.append('_method', 'PUT');
            axios.post(`/api/suppliers/${this.id}`, supplier)
            .then(res => {
                this.closeForm();
                this.$emit('fetchSupplier');
                this.$emit('triggerSnackBar', res.data.message);          
            })
            .catch(err => {
                this.warningMessage = false;
            });
        },
        clearForm() {
            this.name = '';
            this.phonenumber = '';
            this.address = '';
            this.email = '';
            this.selectedSites = [];
        },
        closeForm() {
            this.$emit('closeForm', false)
        },
        fetchAllSites() {
            axios.get('/api/sites')
            .then(res => {
                this.allSites = res.data;
            })
            .catch(err => {
                this.errorMessages = err.response.data.message;
            });
        }
    },
    watch: {
        selectedSupplier: function(propVal) {
            this.name = propVal.name;
            this.phonenumber = propVal.phone_number;
            this.address = propVal.address;
            this.email = propVal.email;
            this.id = propVal.id;
        },
        editSupplierDialog: function(enableStatus) {
            this.showSupplierDialog = enableStatus;
            this.fetchAllSites();
        }
    },
    mounted() {
        this.fetchAllSites();
    }
})
</script>
