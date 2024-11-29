<template>
    <v-dialog
      v-model="newProductCategoryDialog"
      persistent
      max-width="600px"
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Add New Product Category</span>
            </v-card-title>
            <v-form id="newCategory">
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
                    @click="closeForm(); clearForm()"
                    >
                    Close
                    </v-btn>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="sendProductCategoryForm(); clearForm()"
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
    props: ['newProductCategoryDialog'],
    data() {
        return {
            name: '',
            errorMessages: '',
        }
    },
    methods: {
        sendProductCategoryForm(){
            let category = new FormData();
            category.append('name', this.name);
            axios.post('/api/categories', category)
            .then(res => {
                this.closeForm();
                this.$emit('fetchProductCategories');
                this.$emit('triggerSnackBar', res.data.message);              
            })
            .catch(err => {
                this.errorMessages = res.data;
            });
        },
        clearForm() {
            this.name = '';
        },
        closeForm() {
            this.$emit('closeForm', false)
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
