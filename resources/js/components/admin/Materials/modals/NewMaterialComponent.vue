<template>
    <v-dialog
      v-model="newMaterialDialogue"
      persistent
      max-width="1000px"
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Add New Material</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{ warningMessage }}
            </v-alert>
            <v-form id="newUser">
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field
                                    label="Type*"                                   
                                    v-model='type'
                                ></v-text-field>
                            </v-col>                           
                            <v-col cols="6">
                                <v-file-input
                                    show-size
                                    v-model="image"
                                    ref="file"
                                    placeholder="Click to select image"
                                    accept="image/*"
                                ></v-file-input>
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
                    @click="sendSiteForm(); clearForm()"
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
    props: ['newMaterialDialogue'],
    data() {
        return {
            type: null,
            image: [],
            warningMessage: '',
            errorMessages: '',       
        }
    },
    methods: {
        sendSiteForm(){
            const material = new FormData();
            material.append('type', this.type);
            material.append('image', this.image);
            axios.post('/api/materialTypes', material, {headers: {"Content-Type": "multipart/form-data"}})
            .then(res => {
                this.closeForm();
                this.$emit('fetchMaterials');
                this.$emit('triggerSnackBar', res.data.message);
                this.type =  null;
                this.image =  [];
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
            });
        },
        clearForm() {
            this.name = '';
        },
        closeForm() {
            this.$emit('closeForm', false)
        }
    }

})
</script>
