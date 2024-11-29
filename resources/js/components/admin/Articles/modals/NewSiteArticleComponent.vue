<template>
    <v-dialog
      v-model="newArticleDialogue"
      persistent
      max-width="1000px"
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Add New Article</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{ warningMessage }}
            </v-alert>
            <v-form id="newUser">
                <v-card-text>
                    <v-container>
                        <v-row v-if="articles">
                            <v-col cols="12">
                                <v-select
                                    label="Title*"                                   
                                    v-model='selectedArticle'
                                    :items="articles"
                                    item-text="title"
                                    item-value="id"
                                ></v-select>
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
                    @click="setArticle(); clearForm()"
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
    props: ['newArticleDialogue', 'siteid'],
    data() {
        return {
            articles: null,
            warningMessage: '',
            errorMessages: '',
            selectedArticle: null,
            id: null
        }
    },
    methods: {
        fetchArticles(){
            axios.get('/api/articles')
            .then ( res => {
                this.articles = res.data;
            });
        },
        setArticle() {
            let article = new FormData();
            article.append('article', this.selectedArticle);
            article.append('site', this.id);
            axios.post('/api/storearticle', article)
                .then(res => {
                this.selectedArticle = null;
                this.closeForm();
                this.$emit('fetchArticles')
            })
        },
        clearForm() {
            this.name = '';
        },
        closeForm() {
            this.$emit('closeForm', false)
        }
    },
    watch: {
        newArticleDialogue: function(){
            this.$nextTick(() => {
                this.fetchArticles() 
                this.id = this.$route.params.id;
            });
        }
    }

})
</script>
