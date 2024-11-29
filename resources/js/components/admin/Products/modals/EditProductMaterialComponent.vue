<template>
    <v-dialog v-model="EditProductMaterialDialogue" persistent max-width="800px">
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit Material</span>
            </v-card-title>
            <v-form ref="form" v-model="valid">
                <v-card-text>
                    <v-container>
                        <v-row v-if="editedMaterial">
                            <v-col cols="6">
                                <v-text-field label="Material Name*" v-model="editedMaterial.type" :rules="rules"></v-text-field>
                            </v-col>                           
                            <v-col cols="6">
                                <v-text-field label="Price*" v-model="editedMaterial.price" :rules="rules"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close();">
                        Close
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="submit()">
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
    props: ["EditProductMaterialDialogue", "material"],
    data() {
        return {
            editedMaterial: null,
            message: "",
            snackbar: false,
            timeout: 4000,
            rules: [
                v => !!v || 'Field cannot be left blank',
            ],            
            valid: true,
        };
    },
    methods: {
        submit() {
            if (this.$refs.form.validate()) {
                const material = new FormData();
                material.append("editedMaterial", JSON.stringify(this.editedMaterial));
                material.append('_method', 'PUT');
                axios
                    .post(`/api/materialTypes/${this.editedMaterial}`, material)
                    .then((res) => {
                        this.close();
                        this.$emit("fetchSingleProduct");
                    })
                    .catch((error) => {
                        this.snackbar = true;
                        this.message = error;
                    });
            }
        },
        close() {
            this.$emit("closeForm", false);
        },
    },
    watch: {
        material: function (propVal) {
            this.$nextTick(() => {
                this.editedMaterial = propVal
            });
        }
    }
};
</script>
