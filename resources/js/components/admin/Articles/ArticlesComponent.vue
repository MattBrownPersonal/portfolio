<template>
    <div class="px-5">
        <v-row>
            <v-col class="col-12" no-gutters>
                <v-row class="addNewButtonWrapper">
                    <v-col class="col-md-4 offset-4 d-flex justify-center" no-gutters>
                        <v-btn fab fixed right bottom color="primary" dark @click="newArticleDialog = true">
                            <v-icon>add</v-icon>
                        </v-btn>
                    </v-col>
                </v-row>
                <v-card>
                    <v-card-title>
                        Articles
                        <v-spacer></v-spacer>
                        <v-text-field v-model="ArticlesSearch" append-icon="mdi-magnify" label="Search" single-line
                            hide-details />
                    </v-card-title>
                    <v-data-table :headers="articleHeaders" :items="articles" :items-per-page="10" class="elevation-1"
                        :loading="articlesTableLoading" loading-text="Loading... Please wait" :search="ArticlesSearch">
                        <template v-slot:[`item.edit`]="{item}">
                            <v-btn rounded color="primary" dark
                                :to="{ name: 'viewarticle', params: { article_id:item.id }}">
                                View
                            </v-btn>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
        <NewArticleComponent v-bind:newArticleDialog="newArticleDialog" v-on:closeForm="closeFormChange($event)"
            v-on:fetchArticles="fetchArticles()" v-on:triggerSnackBar="triggerSnackBar($event)" />
        <v-snackbar v-model="snackbar" :timeout="timeout">
            {{ snackbarText }}

            <template v-slot:action="{ attrs }">
                <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>

<script>
import NewArticleComponent from './modals/NewArticleComponent.vue'
export default {
    components: {
        NewArticleComponent,   
    },
    data () {
        return {
            articleHeaders: [
                { text: 'Title',align: 'start', value: 'title', width: '50%'},
                { text: 'Actions', value:'edit', width: '50%'}
            ],
            articles: [],
            selectedArticle: '',
            articlesTableLoading: false,
            ArticlesSearch: '',
            snackbar: false,
            snackbarText: '',
            timeout: 4000,
            id: null,
            permissions: [],
            newArticleDialog: false,
            editArticleDialog: false
        }
    },
    methods: {
        fetchArticles() {
            axios.get('/api/articles')
            .then ( res => {
                this.articles = res.data;
            });
        },
        closeFormChange(state) {
            this.newArticleDialog = state;
        },
        triggerSnackBar($event) {
            this.snackbarText = $event;
            this.snackbar = true;
        },
    },
    created () {
        this.fetchArticles();
    },

}
</script>

