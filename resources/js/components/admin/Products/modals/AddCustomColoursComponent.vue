<template>
    <v-dialog
      v-model="AddCustomColourDialog"
      max-width="600px"
      persistent
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Add New Colour</span>
            </v-card-title>
            <v-alert
                type="error"
                class="mx-3"
                v-if="errors.length"
            >
                <ul>
                    <li v-for="error in errors">
                        {{ error }}
                    </li>
                </ul>
            </v-alert>
            <v-form ref="form" 
                @submit.prevent="submit()" 
                v-model="valid"
            >
                <v-card-text>
                    <v-container>
                        <v-row align="center">
                            <v-col cols="12" sm="6" offset="3">
                            <v-text-field
                                label="Colour Name*"
                                v-model="colourName"
                                required
                                :rules="colourNameRules"
                            ></v-text-field>
                            <v-color-picker
                                dot-size="25"
                                swatches-max-height="200"
                                v-model="selectedColour"
                                mode="hexa"
                            ></v-color-picker>
                            </v-col>    
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="close()"
                    >
                    Close
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        type="submit"
                        :disabled="!valid"
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
    props: ['AddCustomColourDialog', 'product'],
    data: function() {
        return {
            selectedColour: '#FF0000',
            colourName: null,
            colourNameRules: [
                v => !!v || 'Colour name is required',
            ],
            valid: true,
            errors: [],
        }
    },
    methods: {
        submit() {
            const colour = new FormData();
            colour.append('colour', JSON.stringify(this.selectedColour));
            colour.append('product_id', this.product.id);
            colour.append('name', this.colourName);

            axios.post('/api/storecolour/', colour)
            .then(res => {
                this.close();
                this.$emit('fetchSingleProduct');
                this.$emit('triggerSnackBar', res.data.message); 
                this.colourName = null;
                this.$refs.form.reset();
            })
            .catch((err) => {
                this.errors = err.response.data.errors;
                this.$refs.form.reset();
            });
        },
        close() {
            this.errors = [];
            this.$refs.form.reset();
            this.$emit('closeForm', false)
        },
    }
})
</script>
