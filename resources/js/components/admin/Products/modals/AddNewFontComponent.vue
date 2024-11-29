<template>
    <v-dialog
      v-model="AddFontDialog"
      max-width="600px"
      persistent
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Add New Font</span>
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
            <v-form>
                <v-card-text>
                    <v-container>
                        <v-row align="center">
                            <v-col cols="12">
                                <v-select
                                v-model="selectedFonts"
                                :items="fonts"
                                label="Select"
                                multiple
                                hint="Select required fonts"
                                persistent-hint
                                item-text="name"
                                return-object
                                key="id"
                                ></v-select>
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
                        @click="submit()"
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
    props: ['AddFontDialog', 'product'],
    data: function() {
        return {
            errors: [],
            fonts: [],
            selectedFonts: []
        }
    },
    methods: {
        fetchFonts() {
            axios.get(`/api/fonts/`)
                .then(res => {
                    this.fonts = res.data.filter ( (font) => {
                        return (this.product.fonts.filter( (selectedFont) => {
                           return selectedFont.id == font.id }
                        ).length == 0); }
                      );
                    this.$emit('fontsChanged',this.fonts);
                    
                })
                .catch((error) => {
                    this.message = error;
                });
        },
        submit() {
            const fonts = new FormData();
            fonts.append("fonts", JSON.stringify(this.selectedFonts));
            fonts.append("_method", "PUT");
            axios.post(`/api/storeFonts/${this.$route.params.product_id}`, fonts)
                .then(res => {
                    this.message = res.data;
                    this.$emit('fetchSingleProduct');
                    this.close();
                })
                .catch((error) => {
                    this.message = error;
                });
        },
        close() {
            this.errors = [];
            this.$emit('closeForm', false)
        },
    },
    watch: {
        product: function (propVal) {
            this.selectedFonts = [];
            this.fetchFonts();
        }

    },
    mounted() {
        this.fetchFonts();
    }
})
</script>
