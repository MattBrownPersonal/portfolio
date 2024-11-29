<template>
    <v-dialog v-model="EditPredefinedImageDialogue" persistent max-width="800px">
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit Image</span>
            </v-card-title>
            <v-form ref="form" v-model="valid">
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field label="Image Title" v-model="name"></v-text-field>
                            </v-col>    
                            <v-col cols="6">
                                <v-text-field prefix="£" label="Image Price" v-model="price"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="
                        close();
                        clear();
                    ">
                        Close
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="
                        submit();
                    ">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
        <v-snackbar v-model="snackbar" :timeout="timeout">
            {{ message }}

            <template v-slot:action="{ attrs }">
                <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </v-dialog>
</template>
<script>
export default {
    props: ["EditPredefinedImageDialogue", 'predefindedImage'],
    data() {
        return {
            name: null,
            price: null,
            id: null,
            message: "",
            snackbar: false,
            timeout: 4000,            
            valid: true,
        };
    },
    methods: {
        submit() {
            const image = new FormData();
            image.append("name", this.name);
            image.append("price", this.price);
            image.append('_method', 'PUT');
            axios
                .post(`/api/predefinedImages/${this.id}`, image)
                .then((res) => {
                    this.name = null,
                    this.close();
                    this.$emit("fetchSingleProduct");
                    this.$refs.form.reset();
                })
                .catch((error) => {
                    this.snackbar = true;
                    this.message = error;
                });
        },
        clearForm() {
            this.name = "";
        },
        close() {
            this.$emit("closeForm", false);
        },
    },
    watch: {
        predefindedImage: function (propVal) {
            this.name = propVal.name
            this.price = propVal.price
            this.id = propVal.id        
        }
    }
};
</script>
