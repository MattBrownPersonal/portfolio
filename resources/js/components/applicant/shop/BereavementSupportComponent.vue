<template>
    <div>
      <div class="titleArea">
        <div class="container" >
            <TopMenuComponent 
                v-on:launchCallbackDialogue="launchCallbackDialogue($event)"
                v-bind:items="items"
            />
            <div class="row mb-0 p-4 mt-lg-10 mb-lg-5 text-lg-center">
                <div class="col-12 col-lg-8 offset-lg-2 pt-0">
                    <h1 class="primary-colour">Bereavement Support</h1>
                    <p class="text-size-medium">
                        {{ bereavement_landing_copy ? bereavement_landing_copy : 'Coping with grief can take many forms. These articles cover a range of bereavement topics and provide details of grief and bereavement support groups and organisations.' }}
                    </p>
                </div>
            </div>
        </div>
      </div>
      <div class="container mt-15 pt-10 ">
          <div class="row mx-1">
              <div class="col-md-12" v-for="article in articles" :key="article.article_id">
                  <div class="row g-0 bereavementArticle overflow-hidden flex-md-row mb-4 h-md-250 position-relative">
                      <div class="col-12 col-md-3">
                          <v-img :src="article.article.image"></v-img>
                      </div>
                      <div class="col p-2 p-xl-5 d-flex flex-column position-static">
                          <h3>{{ article.article.title }}</h3>
                          <p>{{ article.article.description }}</p>
                      
                          <router-link class="primary-colour" :to="{ name: 'viewarticle', params: { articleid: article.article_id } }">
                              Read more
                          </router-link>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <CallbackModalComponent v-on:closeForm="closeForm($event)" v-bind:callbackDialogue="callbackDialogue" />
    </div>
</template>
<script>
import CallbackModalComponent from './modals/CallbackModalComponent'
import TopMenuComponent from './includes/TopMenuComponent'
import { getCodeFromParams } from '../../../deceased';
import { bus } from '../../../app'
export default {
    data() {
        return {
            callbackDialogue: false,
            articles: [],
            bereavement_landing_copy: ''
        }
    },
    components: {
        CallbackModalComponent,
        TopMenuComponent,
    },
    methods: {
        getSiteArticles() {
            let code = getCodeFromParams(this.$route.params);
            if (isNaN(code)) {
                code = this.$route.params.code;
            } else {
                code = getCodeFromParams(this.$route.params);
            }

            axios.get(`/api/fetchSiteArticles/${code}`)
                .then(res => {
                    this.articles = res.data;
                })
        },
        closeForm(state) {
            this.callbackDialogue = state;
        },
        launchCallbackDialogue(state) {
            this.callbackDialogue = state;
        },
        getSettings() {
            axios
                .get(`/api/fetchSettings`)
                .then(res => {
                    this.bereavement_landing_copy = res.data.bereavement_landing_copy.value
                });
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
                    disabled: true,

                },
            ];
        }
    },
    filters: {
        truncate: function (text, suffix) {
            if (text.length > 500) {
                return text.substring(0, 500) + suffix;
            } else {
                return text;
            }
        },
    },
    mounted() {
        this.getSiteArticles();
        this.getSettings();
        bus.$on('launchCallbackDialogue', (data) => {
            this.callbackDialogue = data
        });
    }
}
</script>
<style scoped>
@media (max-width: 500px) {
    .titleImage {
        height: 200px;
    }
}

.titleImage {
    background-size: cover;
    background-position-x: center;
}

.titleArea{ 
  background-color: #FFFFFF;
}
</style>