<template>
    <v-dialog
      v-model="editAttributeTypesDialog"
      max-width="1400px"
      persistent
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit Attributes</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage !== null">
                {{ warningMessage }}
            </v-alert>
            <v-form id="newUser">
                <v-card-text>
                    <v-container>
                        <v-row class="mb-5">
                            <v-col cols="4" offset-md="4">
                                <v-btn color="primary" block @click="addNewAttributeType">Add New Attribute Type</v-btn>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="4" v-for="(attributeType, index) in attributeTypes" v-bind:key="attributeType.id" class="mb-5">
                                <v-row>
                                    <v-col>
                                        <h4>Attribute Name</h4>
                                    </v-col>
                                </v-row>                                
                                <v-row>
                                    <v-col>
                                        <v-text-field
                                        v-model="attributeType.name"
                                        dense
                                        :disabled="attributeType.visible == 0"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col>
                                        <v-btn v-if="attributeType.visible == 1" color="error" @click="hideAttributeType(index)">Hide</v-btn> 
                                        <v-btn v-else color="primary" @click="hideAttributeType(index)">Show</v-btn>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <h4>Attribute Type(s)</h4>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <h5>Type</h5>
                                    </v-col>
                                    <v-col>
                                        <h5>Price</h5>
                                    </v-col>
                                    <v-col>
                                        <h5>Show/Hide</h5>
                                    </v-col>
                                </v-row>
                                <v-row v-for="(attribute, i) in attributeType.attributes" v-bind:key="attribute.id">
                                    <v-col>
                                        <v-text-field
                                            v-model="attribute.name"
                                            dense
                                            :disabled="attribute.visible == 0 || attributeType.visible == 0"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col>
                                        <v-text-field
                                            v-model="attribute.price"
                                            dense
                                            prefix="£"
                                            :disabled="attribute.visible == 0 || attributeType.visible == 0"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col>
                                        <v-btn v-if="attribute.visible == 1" color="error" @click="hideAttribute(attribute)">Hide</v-btn> 
                                        <v-btn v-else color="primary" @click="hideAttribute(attribute)">Show</v-btn>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <v-btn color="primary" @click="addNewAttribute(attributeType.id, index)">Add New Attribute</v-btn>
                                    </v-col>
                                </v-row>
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
                    @click="sendAttributes(); clearForm()"
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
    props: ['editAttributeTypesDialog', 'attribute_types'],
    data: function() {
        return {
            name: '',
            warningMessage: null,
            errorMessages: '',
            attributeTypes: null
        }
    },
    methods: {
        sendAttributes() {
            const attributes = new FormData();
            attributes.append('attributeTypes', JSON.stringify(this.attributeTypes));           
            axios.post('/api/attributeTypes/', attributes)
            .then(res => {
                this.closeForm();
                this.$emit('fetchAttributeTypes');
                this.$emit('triggerSnackBar', res.data.message);          
            })
            .catch(err => {
                this.warningMessage = false;
            });
        },
        addNewAttributeType() {
            const newAttributeType = {
                id: "", name: "", product_id: this.$route.params.product_id, visible: 1, attributes: []}
            this.attributeTypes.push(newAttributeType)  
        },
        addNewAttribute(attributeTypeId, index) {
            const newAttribute = {
                id: "", attribute_types_id: attributeTypeId, id: "", name: "", price: "", visible: 1}
            this.attributeTypes[index].attributes.push(newAttribute)  
        },
        hideAttributeType(index) {
            if (this.attributeTypes[index].visible === 1) {
                this.attributeTypes[index].visible = 0;
            } else {
                this.attributeTypes[index].visible = 1;
            }
        },
        hideAttribute(attribute) {
            if (attribute.visible === 1) {
                attribute.visible = 0;
            } else {
                attribute.visible = 1;
            }
        },
        clearForm() {
            this.name = '';
        },
        closeForm() {
            this.$emit("closeForm", false);
        },
    },
    computed: {
      form () {
        return {
          name: this.name,
        }
      }
    },
    watch: {
        attribute_types: function(propVal) {
            this.attributeTypes = propVal;
        },
    },
})
</script>
