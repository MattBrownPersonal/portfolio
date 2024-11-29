<template>
    <v-dialog
      v-model="deleteUserDialog"
      persistent
      max-width="700px"
      height="400px"
    >
        <v-card class="text-center">
            <v-card-title>
                <span class="text-h5 red--text ">You are about to delete user {{ firstname+' '+lastname }}.</span>
            </v-card-title>     
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
    props: ['deleteUserDialog', 'user'],
    data: function() {
        return {            
            id: null,
            firstname: null,
            lastname : null,
            warningMessage: ''
        }
    },
    methods: {
        sendDelete(){
            axios.delete('/api/users/'  + this.id)
            .then(res => {
                this.closeForm();
                this.$router.go(-1) 
                this.$emit('savedMessage', res.data.message);          
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
            });
        },
        closeForm() {
            this.$emit('closeForm', false)
        }
    },
    watch: {
        user: function(propVal) {
            this.id = propVal.id;
            this.firstname = propVal.firstname;
            this.lastname = propVal.lastname;
        }
    }
})
</script>
