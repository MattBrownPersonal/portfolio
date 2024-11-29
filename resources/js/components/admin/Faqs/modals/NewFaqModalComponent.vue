<template>
    <v-dialog
      v-model="newFaqDialogue"
      persistent
      max-width="1000px"
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Add New FAQ</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{ warningMessage }}
            </v-alert>
            <v-form id="newUser">
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    label="Question"
                                    name="question"
                                    v-model="question"
                                ></v-text-field>
                            </v-col>                            
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                <v-textarea
                                    label="Answer"
                                    v-model="answer"
                                    name="answer"
                                ></v-textarea>
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
                    @click="sendFaq(); clearForm()"
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
    props: ['newFaqDialogue', 'site'],
    data() {
        return {
            question: null,
            answer: null,
            warningMessage: '',
            errorMessages: '',
            selectedArticle: null,
            id: null
        }
    },
    methods: {
        sendFaq() {
            let faq = new FormData();
            faq.append('question', this.question);
            faq.append('answer', this.answer);
            if (this.id) {
                faq.append('site', this.id);
            }
            axios.post('/api/faqs', faq)
                .then(res => {
                this.question = null;
                this.answer = null;
                this.closeForm();
                this.$emit('fetchSiteFaqs');
            })
        },
        clearForm() {
            this.name = '';
        },
        closeForm() {
            this.$emit('closeForm', false);
        }
    },
    watch: {
        newFaqDialogue: function(){
            this.$nextTick(() => {
                this.id = this.site.id;
            });
        }
    }
})
</script>
