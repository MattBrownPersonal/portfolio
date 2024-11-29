<template>
    <div class="px-5">
        <v-row>
            <v-row class="addNewButtonWrapper">
                <v-col class="col-md-4 offset-4 d-flex justify-center" no-gutters>
                    <v-btn fab fixed bottom right color="primary" dark @click="newArticleDialogue = true">
                        <v-icon>add</v-icon>
                    </v-btn>
                </v-col>
            </v-row>
            <v-col class="col-2">
                <v-card elevation="2">
                    <v-card-text>
                        <p class="text-h6">Actions</p>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>
                                    <v-icon left>mdi-keyboard-backspace</v-icon>
                                    <span @click="$router.go(-1)" class="actionLink">Back</span>
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-card-text>
                </v-card>
                <UsefulLinksComponent v-bind:site="site" />
            </v-col>
            <v-col class="col-10">
                <v-card elevation="2">
                    <v-card-title>
                        <p>Site Articles (Click and drag article to change order)</p>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="articles" :items-per-page="10" class="elevation-1"
                        :loading="articlesTableLoading" loading-text="Loading... Please wait" :search="search"
                        v-sortable-data-table @sorted="saveOrder">
                        <template v-slot:[`item.new_order_number`]="{item}">
                            <v-select :items="items" label="Order Number"></v-select>
                        </template>
                        <template v-slot:[`item.edit`]="{item}">
                            <v-btn rounded color="primary" dark
                                :to="{ name: 'viewsitearticle', params: { id:$route.params.id, article_id:item.article_id }}">
                                View
                            </v-btn>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
            <NewSiteArticle v-bind:newArticleDialogue="newArticleDialogue" v-on:fetchArticles="fetchSiteAritcles()"
                v-on:closeForm="newArticleDialogue = false" />
        </v-row>
    </div>
</template>
<script>
import NewSiteArticle from './modals/NewSiteArticleComponent.vue'
import UsefulLinksComponent from '../UsefulLinksComponent.vue'
export default {
    components: {
        NewSiteArticle,
        UsefulLinksComponent,
    },
    data() {
        return {
            headers: [
                { text: 'Title', align: 'start', value: 'article.title', width: '50%', sortable: false,},
                { text: 'Actions', value:'edit', width: '20%'}
            ],
            articles: [],
            articlesTableLoading: false,
            search: '',
            selectedUser: '',
            message: '',
            snackbar: false,
            timeout: 4000,
            newArticleDialogue: false,
            items: [],
            newOrderNumber: null,
            site: null,
        }
    },
    methods: {
        fetchSiteAritcles() {
            axios.get(`/api/adminFetchSiteArticles/${this.$route.params.id}`)
            .then( res => {
                this.articles = res.data;
                res.data.forEach(element => {
                    this.items.push(element.order_number);
                })
            })
        },
        setArticleOrder() {
            const orderNumber = new FormData();
            orderNumber.append('articles', JSON.stringify(this.articles));      
            axios.post(`/api/newOrder/${this.$route.params.id}`, orderNumber, {headers: {"Content-Type": "multipart/form-data"}})
            .then(res => {
                this.fetchSiteAritcles()
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
            });
        },
        saveOrder(event) {
            let newOrderNumber = this.articles[event.oldIndex].order_number;
            const movedItem = this.articles.splice(event.oldIndex, 1)[0];
            this.articles.splice(event.newIndex, 0, movedItem);

            if (event.oldIndex < event.newIndex) {
                // Move Element Down
                for (let i = event.oldIndex; i <= event.newIndex; i++) {
                    let oldOrderNumber = this.articles[i].order_number;
                    this.articles[i].order_number = newOrderNumber;
                    newOrderNumber = oldOrderNumber;
                }                
            } else {
                // Move Element Up
                for (let i = event.oldIndex; i >= event.newIndex; i--) {
                    let oldOrderNumber = this.articles[i].order_number;
                    this.articles[i].order_number = newOrderNumber;
                    newOrderNumber = oldOrderNumber;
                }
            }
            this.setArticleOrder();
        },
        fetchStyleName() {
            axios.get(`/api/sites/${this.$route.params.id}`)
                .then(res => {
                    this.site = res.data.site;

                })
                .catch(err => {
                    this.warningMessage = err.response.data.message;
                });
        }, 
    },
    directives: {
        sortableDataTable: {
            bind (el, binding, vnode) {
                const options = {
                    animation: 150,
                    onUpdate: function (event) {
                        vnode.child.$emit('sorted', event)
                    }
                }
                Sortable.create(el.getElementsByTagName('tbody')[0], options)
            }
        }
    },
    mounted() {
        this.fetchSiteAritcles();
        this.fetchStyleName();
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