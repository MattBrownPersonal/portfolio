<template>
    <v-dialog v-model="deleteFaqDialog" persistent max-width="700px" height="400px">
        <v-card class="text-center">
            <v-card-title>
                <span class="text-h5 red--text ">You are about to delete this FAQ. Are you sure?</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{ warningMessage }}
            </v-alert>
            <v-col class="d-flex justify-center px-0">
                <v-btn color="error" rounded @click="sendDelete()">Delete</v-btn>

                <v-btn color="primary" rounded @click="closeForm()">Cancel</v-btn>
            </v-col>
        </v-card>
    </v-dialog>
</template>
<script>

export default ({
    props: ['deleteFaqDialog', 'selectedFaq'],
    data: function () {
        return {
            warningMessage: '',
        }
    },
    methods: {
        sendDelete() {
            axios.delete(`/api/faqs/${this.selectedFaq.id}`)
                .then(res => {
                    this.closeForm();
                    this.$emit('fetchFaqs');
                    this.$emit('triggerSnackBar', res.data.message);
                })
                .catch(err => {
                    this.warningMessage = 'FAQ Not Found'
                });
        },
        closeForm() {
            this.$emit('closeForm', false)
        }
    }
})
</script>
