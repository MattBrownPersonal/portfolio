<template>
    <v-dialog
      v-model="deleteSiteLogoDialogue"
      persistent
      max-width="700px"
      height="400px"
    >
        <v-card class="text-center">
            <v-card-title>
                <span class="text-h5 red--text ">You are about to delete the logo for this site</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{ warningMessage }}
            </v-alert>         
            <v-col class="d-flex justify-center px-0">
                <v-btn
                rounded
                @click="closeForm()"
                class="mr-2"
                >Cancel</v-btn>

                <v-btn 
                color="error"
                rounded     
                @click="sendDelete()"               
                >Delete</v-btn>
            </v-col>          
        </v-card>
    </v-dialog>
</template>
<script>

export default {
    props: ['deleteSiteLogoDialogue', 'siteStyle'],
    data: function() {
        return {            
            id: null,
            name: null,
            warningMessage: '',
        }
    },
    methods: {
        sendDelete(){
            axios.delete('/api/imageuploads/' + this.siteStyle.image_id)
            .then(res => {
                this.closeForm();
                this.$emit('triggerSnackBar', res.data.message);   
                this.$emit('fetchSite');
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
            });
        },
        closeForm() {
            this.$emit('closeForm', false)
        }
    },
}
</script>
