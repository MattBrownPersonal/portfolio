<template>
    <v-dialog
      v-model="editAttributeTypeDialog"
      max-width="600px"
      persistent
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit Attribute</span>
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
                            sm="12"
                            >
                            <v-text-field
                                label="Name*"
                                required
                                :rules="[() => !!name || 'This field is required']"
                                :error-messages="errorMessages"
                                v-model='name'
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
    props: ['editAttributeTypeDialog', 'selectedAttributeType'],
    data: function() {
        return {
            name: '',
            warningMessage: null,
            errorMessages: '',
        }
    },
    methods: {
        sendSupplierForm(){
            const attributeType = new FormData();
            attributeType.append('name', this.name);
            attributeType.append('id', this.id);
            attributeType.append('_method', 'PUT');
            axios.post(`/api/attributeTypes/${this.id}`, attributeType)
            .then(res => {
                this.closeForm();
                this.$emit('fetchAttribute');
                this.$emit('triggerSnackBar', res.data.message);          
            })
            .catch(err => {
                this.warningMessage = false;
            });
        },
        clearForm() {
            this.name = '';
            this.price = '';
        },
        closeForm() {
            this.$emit('closeForm', false)
        },
    },
    watch: {
        selectedAttributeType: function(propVal) {
            this.name = propVal.name;
            this.id = propVal.id;
        },
    },
    computed: {
      form () {
        return {
          name: this.name,
        }
      }
    },
})
</script>
