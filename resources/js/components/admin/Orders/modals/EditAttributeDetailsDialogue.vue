<template>
    <v-dialog v-model="editAttributeDetailsDialogue" max-width="800px" persistent>
        <v-card>
            <v-card-title>
                <span class="text-h5">Attributes</span>
            </v-card-title>           
            <v-alert
                color="red"
                text
                type="warning"
                v-if="showError"
            >Please ensure all options are selected</v-alert>
            <v-form ref="form" lazy-validation>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="4" v-if="materials.length > 0">
                                <p class="font-weight-bold">Material</p>
                                <v-radio-group v-model="selectedMaterial" :rules="[v => !!v || 'Material is required']"  required>
                                    <v-radio
                                        v-for="material in materials"
                                        :key="material.id"
                                        :label="material.type"
                                        :value="material"
                                        required
                                    ></v-radio>
                                </v-radio-group>
                            </v-col>
                            <v-col cols="12" sm="4" v-if="fonts.length > 0">
                                <p class="font-weight-bold">Font Face</p>
                                <v-radio-group v-model="selectedFont">
                                    <v-radio
                                        v-for="font in fonts"
                                        :key="font.id"
                                        :label="font.name"
                                        :value="font"
                                    ></v-radio>
                                </v-radio-group>
                            </v-col>
                            <v-col cols="12" sm="4" v-if="fontColours.length > 0">
                                <p class="font-weight-bold">Font Colour</p>
                                    <v-radio-group v-model="selectedFontColour">
                                        <v-radio
                                            v-for="colour in fontColours"
                                            :key="colour.id"
                                            :label="colour.name"
                                            :value="colour"
                                        ></v-radio>
                                    </v-radio-group>
                            </v-col>                           
                        </v-row>
                        <v-row>
                            <v-col cols="12" sm="4" v-for="(attribute_type, index) in attributes">
                                <p class="font-weight-bold">{{ attribute_type.name }}</p>
                                    <v-radio-group v-model="selectedAttributes[index]">
                                        <v-radio
                                            v-for="attribute in attribute_type.attributes"                                            
                                            :key="attribute.id"
                                            :label="attribute.name"
                                            :value="attribute"
                                        ></v-radio>
                                    </v-radio-group>
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
                    <v-btn color="blue darken-1" text @click="submit();">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>

export default ({
    props: ['editAttributeDetailsDialogue', 'order', 'orderAttributes'],
    data: function () {
        return {
            attributes: [],
            materials: [],
            fontColours: [],
            fonts: [],
            selectedMaterial: null,
            selectedFontColour: null,
            selectedFont: null,
            selectedAttributes: [],
            formValid: false,
            showError: false
        }
    },
    methods: {
        submit() {
            let materialSelected = false;
            if (this.materials.length === 0) {
                materialSelected = true;
            } else if (this.materials.length > 0 && this.selectedMaterial != null) {
                materialSelected = true;
            }

            let fontColourSelected = false;

            if (this.fontColours.length === 0) {
                fontColourSelected = true;
            } else if (this.fontColours.length > 0 && this.selectedFontColour != null) {                
                fontColourSelected = true;
            }             

            let fontSelected = false;

            if (this.fonts.length === 0) {
                fontSelected = true;
            } else if (this.fonts.length > 0 && this.selectedFont != null) {
                fontSelected = true;
            }           
            
            const selectedAttributesWithOutNull = this.selectedAttributes.filter(item => item !== null);
            const attributesSelected = selectedAttributesWithOutNull.length === this.attributes.length;
            this.formValid = materialSelected && fontColourSelected && fontSelected && attributesSelected;
            
            if (this.formValid) {
                this.saveAttributes();
            } else {
                this.showError = true;
            };
        },
        saveAttributes() {
            const item = new FormData();
           if (this.selectedAttributes) {
                item.append('selectedMaterial', JSON.stringify(this.selectedMaterial));
            }
            if (this.selectedFontColour) {
                item.append('selectedFontColour', JSON.stringify(this.selectedFontColour));
            }
            if (this.selectedFont) {
                item.append('selectedFont', JSON.stringify(this.selectedFont));
            }
            if (this.selectedAttributes) {
                item.append('selectedAttributes', JSON.stringify(this.selectedAttributes));
            }
            item.append('type', 'editItemOrders');

            axios.post(`/api/updateOrder/${this.order.id}`, item)
                .then(res => {
                    this.closeForm();
                    this.$emit('fetchOrder');
                    this.showError = false
                    this.$refs.form.reset();
                })
                .catch(err => {
                    this.warningMessage = err;
                });
        },
        fetchAttributes() {
            let product = this.order.itemorders.find(x => x.item_type === 'product');
            axios.get(`/api/fetchProductAttributes/${product.product_id}/${this.order.site.id}`)
                .then(res => {
                    this.attributes = res.data.attribute_types.filter(item => item.visible === 1 && item.name != "" && item.name != 'Predefined Image ');
                    this.materials = res.data.materials;
                    this.fontColours = res.data.text_colours;
                    this.fonts = res.data.fonts;

                    this.attributes.forEach(customAttribute => {                        
                        customAttribute.attributes.forEach(attributeOption => {
                            this.$set(attributeOption, 'type', customAttribute.name);
                        });
                    });

                    this.orderAttributes.forEach(element => {
                        switch (element.item_type) {
                            case "material":
                                const attributeMaterial = this.materials.find((x) => x.type == element.attribute_name );
                                this.$set(this, 'selectedMaterial', attributeMaterial);
                                break;
                            case "fontColour":
                                const attributeFontColour = this.fontColours.find((x) => x.name == element.attribute_name);
                                this.$set(this, 'selectedFontColour', attributeFontColour);
                                break;
                            case "fontFace":
                                const attributeFontFace = this.fonts.find((x) => x.name == element.attribute_name);
                                this.$set(this, 'selectedFont', attributeFontFace);
                                break;
                            case "attribute":
                                if (element.attribute_name !== "Predefined Image") {
                                    const customAttributeIndex = this.attributes.findIndex((x) => x.name == fetchProductAttributes);
                                    const customAttributeType = this.attributes[customAttributeIndex].attributes.find((x) => x.name == element.attribute_name);
                                    this.$set(this.selectedAttributes, customAttributeIndex, customAttributeType);
                                }
                                                                  
                                break;                            
                        }
                    })
                });
        },
        closeForm() {
            this.showError = false;
            this.$emit('closeForm', false)
        },
    },
    watch: {
        editAttributeDetailsDialogue: function (propVal) {
            this.fetchAttributes();            
        },
    }
})
</script>
