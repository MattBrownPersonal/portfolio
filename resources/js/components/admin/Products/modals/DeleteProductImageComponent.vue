<template>
    <v-dialog
      v-model="deleteProductImageDialog"
      persistent
      max-width="700px"
      height="400px"
    >
        <v-card class="text-center">
            <v-card-title>
                <span class="text-h5 red--text ">Do you want to delete this image?</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-if="errors.length">
                <ul>
                    <li v-for="error in errors">
                        {{ error }}
                    </li>
                </ul>
            </v-alert>
            <v-col class="d-flex justify-center px-0">
                <v-btn 
                color="error"
                rounded     
                @click="sendDelete()"               
                >Delete</v-btn>
                
                <v-btn
                color="primary"
                rounded
                @click="closeForm()"
                >Cancel</v-btn>
            </v-col>          
        </v-card>
    </v-dialog>
</template>
<script>

export default ({
    props: ['deleteProductImageDialog', 'deleteImageId'],
    data: function() {
        return {            
            id: null,
            name: null,
            errors: [],
        }
    },
    methods: {
        sendDelete(){
            axios.delete(`/api/deleteProductImage/${this.deleteImageId}`)
            .then(res => {
                this.closeForm();
                this.$emit('triggerSnackBar', res.data.message);
                this.$emit('refreshPage');
            })
                .catch(err => {
                this.errors = err.response.data.errors;
            });
        },
        closeForm() {
            this.$emit('closeForm', false)
        }
    },
    watch: {
        deleteImageId: function(propVal) {
            this.id = propVal.id;
        }
    }
})
</script>
