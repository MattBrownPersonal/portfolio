<template>
    <v-dialog
      v-model="newSupplierDialog"
      persistent
      max-width="600px"
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Add New Supplier</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
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
                            <v-select
                                :items="allSites"
                                label="Site*"
                                multiple
                                required
                                :rules="[() => !!sites || 'This field is required']"
                                :error-messages="errorMessages"
                                v-model='sites'
                                item-text="name"
                                item-value="id"
                            ></v-select>
                            </v-col>
                            <v-col
                            cols="12"
                            sm="6"
                            >
                            <v-text-field
                                label="Supplier name*"
                                required
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
                                label="Address*"
                                required
                                :rules="[() => !!address || 'This field is required']"
                                :error-messages="errorMessages"
                                v-model='address'
                            ></v-text-field>
                            </v-col>
                            <v-col
                            cols="12"
                            sm="6"
                            >
                            <v-text-field
                                label="Phone Number*"
                                required
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
                                required
                                :rules="[() => !!email || 'This field is required']"
                                :error-messages="errorMessages"
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
    props: ['newSupplierDialog'],
    data() {
        return {
            name: '',
            address: '',
            phonenumber: '',
            email: '',
            sites: [],
            allSites: [],
            warningMessage: '',
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
            supplier.append('sites', this.sites);
            axios.post('/api/suppliers', supplier)
            .then(res => {
                this.closeForm();
                this.$emit('fetchSuppliers');
                this.$emit('savedMessage', res.data.message);        
            })
            .catch(err => {
                this.warningMessage = err.data.message;
            });
        },
        fetchAllSites() {
            axios.get('/api/sites')
            .then(res => {
                this.allSites = res.data;
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
            });
        },
        clearForm() {
            this.name = '';
            this.address = '';
            this.phonenumber = '';
            this.email = '';
            this.sites = [];
        },
        closeForm() {
            this.$emit('closeForm', false)
        }
    },
    mounted () {
        this.fetchAllSites()
    }
})
</script>
