<template>
    <div>
        <div class="container">
            <TopMenuComponent 
                v-on:launchCallbackDialogue="launchCallbackDialogue($event)"
                v-bind:items="items"
            />
        </div>

        <div class="container-fluid">
            <div class="articleHeader" v-if="article">
                <div class="row mx-1 mx-md-5 mb-5">
                    <div class="col-12 col-md-12 col-lg-8 col-xl-4 align-self-center order-1 order-md-2">
                        <h1 class="primary-colour">{{ article.title }}</h1>
                        <p>{{ article.description }}</p>
                    </div>
                    <div class="col-12 d-sm-none d-md-block col-md-6 col-lg-4 offset-md-0 offset-lg-2 p-3 p-md-5 order-2 order-md-1">
                        <img :src="article.image" class="pb-5 w-100">
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid articleBody" v-if="article">
            <div class="row mx-1 mx-md-5 px-4">
                <div class="col-12 col-xl-6 offset-xl-3">
                    <span v-html="article.article" class="text-size-regular"></span>
                </div>
            </div>
        </div>

        <div class="container relatedArticles" v-if="allArticles">
            <div class="row mx-1">
                <h3 class="primary-colour mx-auto relatedArticles-title">Related Articles</h3>
                <div class="col-md-12" v-for="singleArticle in allArticles.slice(0,5)" :key="singleArticle.article_id">
                    <div v-if="singleArticle.article_id !== article.id" class="row g-0 bereavementArticle overflow-hidden flex-md-row mb-4 h-md-250 position-relative">
                        <div class="col-12 col-md-3">
                            <v-img :src="singleArticle.article.image"></v-img>
                        </div>
                        <div class="col p-2 p-xl-5 d-flex flex-column position-static">
                            <h3>{{ singleArticle.article.title }}</h3>
                            <p>{{ singleArticle.article.description }}</p>
                        
                            <router-link class="primary-colour" :to="{ name: 'viewarticle', params: { articleid: singleArticle.article_id } }">
                                Read more
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <CallbackModalComponent
            v-on:closeForm="closeForm($event)"
            v-bind:callbackDialogue="callbackDialogue" 
        />
    </div>
</template>
<script>
import CallbackModalComponent from './modals/CallbackModalComponent'
import TopMenuComponent from './includes/TopMenuComponent'
import ArticleComponent from './includes/ArticleComponent'
import { getCodeFromParams } from '../../../deceased';
import { bus } from '../../../app'
export default {
    data() {
        return {
            callbackDialogue: false, 
            article: null,
            allArticles: null,
            relatedArticles: []
        }
    },
    components: {
        CallbackModalComponent,
        TopMenuComponent,
        ArticleComponent
    },
    methods: {
        fetchArticles() {
            axios.get(`/api/fetchSiteArticle/${this.$route.params.articleid}`)
            .then( res =>{
                this.article = res.data;                
            })
        },
        fetchNewArticle(id) {
            axios.get(`/api/fetchSiteArticle/${id}`)
                .then(res => {
                    this.article = res.data;
                    window.scrollTo(0, 0);
                })
        },
        changeArticle(id) {
            this.article = this.allArticles.find(x => x.id === id);
        },
        fetchAllArticles() {
            let code = getCodeFromParams(this.$route.params);
            if (isNaN(code)) {
                code = this.$route.params.code;
            } else {
                code = getCodeFromParams(this.$route.params);
            }

            axios.get(`/api/fetchSiteArticles/${code}`)
            .then( res =>{
                this.allArticles = res.data;
            })
        },
        launchCallbackDialogue(state) {
            this.callbackDialogue = state;
        },
        closeForm(state) {
            this.callbackDialogue = state;
        },
    },
    created() {
        this.fetchArticles();
        this.fetchAllArticles();
        bus.$on('launchCallbackDialogue', (data) => {
            this.callbackDialogue = data
        });
    },
    filters: {
        truncate: function (text, length, suffix) {
            if (text.length > length) {
                return text.substring(0, length) + suffix;
            } else {
                return text;
            }
        },
    },
    computed: {
        memorialisations() {
            return this.$store.state.memorialisations;
        },
        deceased() {
            return this.$store.state.deceased;
        },
        styles() {
            return this.$store.state.styles;
        },
        items() {
            return [
                {
                    text: 'Home',
                    link: true,
                    exact: true,
                    disabled: false,
                    to: { name: 'memorials' },
                },
                {
                    text: 'Bereavement Articles',
                    link: true,
                    exact: true,
                    disabled: false,
                    to: { name: 'bereavementsupport' }
                },
                {
                    text: 'Article',
                    link: true,
                    exact: true,
                    disabled: true,
                }
            ];
        }
    },

}
</script>
