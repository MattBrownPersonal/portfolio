<template>
    <div class="article-content">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <img :src="article.image" width="100%">
                </div>
                <div class="col-8">
                    <h2 class="primary-colour">{{ article.title }}</h2>
                </div>
            </div>
        </div>
        <div class="home-page-row">
            <div class="container">
                <div class="row">
                    <div class="col-8 offset-2">
                        <span v-html="article.article"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:['article'],
    data () {
        return {
            selectedArticle: null,
        }
    },
    watch: {
        article: function (propVal) {
            this.selectedArticle = propVal;
        }
    },
    
}
</script>