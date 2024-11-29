<template>
    <v-dialog
      v-model="deleteSiteStaffDialog"
      max-width="600px"
      persistent
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Are you sure you wish to delete this user?</span>
            </v-card-title>
             <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                Cannot find user
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
    props: ['deleteSiteStaffDialog','staffMember'],
    data: function() {
        return {
            id: null,
            warningMessage: false,
        }
    },
    methods: {
        sendDelete(){
            axios.delete('/api/users/'  + this.id)
            .then(res => {
                this.closeForm();
                this.$router.go(-1)
                this.$emit('triggerSnackBar', res.data.message);               
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
            });
        },
        clearForm() {
            this.name = null;
        },
        closeForm() {
            this.$emit('closeForm', false)
        }
    },
    watch: {
        staffMember: function(propVal) {
            this.id = propVal.id;
        }
    }
})
</script>
