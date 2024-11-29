<template>
    <div class="px-4">
        <v-row>
            <v-col class="col-2">
                <v-card elevation="2">
                    <v-card-text>
                        <p class="text-h6">Actions</p>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title class="pb-4">
                                    <span class="actionLink" @click="editArticleDialog = true; editArticleDetails(article)">
                                        <v-icon left>mdi-pencil</v-icon>
                                        Edit
                                    </span>
                                </v-list-item-title>                          
                                <v-list-item-title>
                                    <v-icon left>mdi-keyboard-backspace</v-icon>
                                    <span @click="$router.go(-1)" class="actionLink">Back</span>
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col class="col-10">
                <v-row>
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p v-if="article">Article Title</p>
                            </v-card-title>
                            <v-card-text>
                                {{ article.title }}
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p v-if="article">Article Blurb</p>
                            </v-card-title>
                            <v-card-text>
                                {{ article.description }}
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card elevation="2">   
                            <v-card-title>
                                <p v-if="article">Article</p>
                            </v-card-title>                    
                            <v-card-text class="article-contents">
                                <span v-html="article.article"></span>
                            </v-card-text>
                        </v-card> 
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p v-if="article">Thumbnail Image</p>
                            </v-card-title>
                            <v-card-text>
                                <v-img :src="article.image" width="300px"></v-img>
                            </v-card-text>
                        </v-card> 
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card elevation="2">   
                            <v-card-title>
                                <p v-if="article">Article URL</p>
                            </v-card-title>                    
                            <v-card-text>
                                <a :href="article.url">{{ article.url }}</a>
                            </v-card-text>
                        </v-card> 
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
        <EditArticleComponent
            v-bind:editArticleDialog="editArticleDialog" 
            v-on:closeForm="closeFormChange($event)"
            v-on:fetchArticles="fetchArticle()"
            v-on:triggerSnackBar="triggerSnackBar($event)"
            v-bind:articleDetails="article"
        />
        <v-snackbar
            v-model="snackbar"
            :timeout="timeout"
            color="green"
        >
            {{ snackbarText }}

            <template v-slot:action="{ attrs }">
            <v-btn
                color="white"
                text
                v-bind="attrs"
                @click="snackbar = false"
            >
                Close
            </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>
<script>
import EditArticleComponent from './modals/EditArticleComponent.vue'
import UsefulLinksComponent from '../UsefulLinksComponent.vue'
export default {
    components: {
        EditArticleComponent,  
        UsefulLinksComponent,    
    },
    
    data () {
        return {
            article: [],
            selectedAttribute: '',
            snackbar: false,
            snackbarText: '',
            timeout: 4000,
            editArticleDialog: false,
            id: null,
            attributeTypes: null,
            article: []
        }
    },
    methods: {
        fetchArticle() {
            axios.get(`/api/articles/${this.$route.params.article_id}`)
            .then(res => {
                this.article = res.data;
            })
            .catch(err => {
                this.snackbar = true;
                this.snackbarText = err.response.data.message;
            });
        },
        triggerSnackBar($event) {
            this.snackbarText = $event
            this.snackbar = true;
        },
        closeFormChange(state) {
            this.editArticleDialog = false;
        },
        editArticleDetails(item) {
            this.articleDetails = item;
        },
        triggerSnackBar($event) {
            this.snackbarText = $event
            this.snackbar = true;
        }
    },
    mounted () {
        this.fetchArticle();
        this.id = this.$route.params.id;
    }
}
</script>
<style scoped>
.crudMenu{
    list-style-type:none;
}
.actionLink {
    cursor: pointer;
}
</style>