<template>
    <v-dialog
      v-model="editCategoryDialog"
      max-width="600px"
      persistent
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit {{ name }}</span>
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
                            >
                            <v-text-field
                                label="Category name*"
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
                    @click="closeForm()"
                    >
                    Close
                    </v-btn>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="sendCategoryDetails()"
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
    props: ['editCategoryDialog', 'selectedCategory'],
    data: function() {
        return {
            warningMessage: null,
            name: null,
            errorMessages: '',
        }
    },
    methods: {
        sendCategoryDetails(){
            const editedCategory = new FormData();
            editedCategory.append('name', this.name);
            editedCategory.append('id', this.selectedCategory.id);
            editedCategory.append('_method', 'PUT');
            axios.post(`/api/categories/${this.selectedCategory.id}`, editedCategory)
            .then(res => {
                this.closeForm();
                this.$emit('fetchCategory');
                this.$emit('triggerSnackBar', res.data.message); 
            })
            .catch(err => {
                this.warningMessage = false;
            });
        },
        closeForm() {
            this.$emit('closeForm', false)
        },
    },
     watch: {
        selectedCategory: function(propVal) {
            this.name = propVal.name;
            this.id = propVal.id;
        }
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
