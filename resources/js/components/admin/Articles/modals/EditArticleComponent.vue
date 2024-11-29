<template>
    <v-dialog
      v-model="editArticleDialog"
      persistent
      max-width="1000px"
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit Article</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{ warningMessage }}
            </v-alert>
            <v-form 
            ref="form" 
            v-model="valid"
            >
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="4">
                                <v-text-field
                                    label="Title*"                                   
                                    v-model='title'
                                    :rules="titleRules"
                                    required
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field
                                    label="URL (If link is external)"
                                    v-model='url'
                                ></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-file-input
                                    show-size
                                    v-model="image"
                                    ref="file"
                                    label="Click to select image*"
                                    :rules="imageRules"
                                    placeholder="Click to select image"
                                    accept="image/*"
                                ></v-file-input>
                            </v-col>                                                        
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    label="Blurb"                                   
                                    v-model='blurb'
                                ></v-text-field>
                            </v-col>                                                
                        </v-row>
                        <v-row>
                            <v-col @paste="onPaste" cols="12">
                                <wysiwyg  v-model="article" />
                            </v-col>
                        </v-row>
                        <v-expansion-panels>
                            <v-expansion-panel>
                                <v-expansion-panel-header><span class="text-h5">Article Preview</span></v-expansion-panel-header>
                                <v-expansion-panel-content><span class="article-contents" v-html="article"></span></v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>

                    </v-container>
                <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="close()"
                    >
                    Close
                    </v-btn>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="submit()"
                    >
                    Save
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>
import removeWordStyling from '../../../../removeStyling'
export default ({
    props: ['editArticleDialog', 'articleDetails'],
    data() {
        return {
            title: null,
            article: null,
            blurb: null,
            url: '',
            image: [],
            warningMessage: '',
            errorMessages: '',
            titleRules: [
                v => !!v || 'Title is required',
                v => (v && v.length <= 255) || 'Title name must be less than 255 characters',
            ],
            imageRules: [
                value => !value || value.size < 8000000 || 'Thumbnail required',
            ],
            valid: true,
        }
    },
    methods: {
        submit() {
            if (this.$refs.form.validate()) {
                let article = new FormData();
                article.append('title', this.title);
                article.append('article', this.article);

                if (this.blurb) {
                    article.append('blurb', this.blurb);
                }
                if (this.url) {
                    article.append('url', this.url);
                }
                if (this.article.image) {
                    article.append('image', this.article.image);
                } else {
                    article.append('image', this.image);
                }
                article.append('_method', 'PUT');
                axios.post(`/api/articles/${this.articleDetails.id}`, article)
                    .then(res => {
                        this.close();
                        this.$emit('fetchArticles');
                        this.$emit('triggerSnackBar', res.data.message);
                        this.$refs.form.reset();
                    })
                    .catch(err => {
                        this.warningMessage = err;
                        this.$refs.form.reset();
                    });
            }
        },
        close() {
            this.$refs.form.reset();
            this.$emit('closeForm', false)
        },
        onPaste (event) {
            event.preventDefault();
            let paste = (event.clipboardData || window.clipboardData).getData("text/html");
            paste = removeWordStyling(paste);
            const selection = window.getSelection();
            if (!selection.rangeCount) return;
            selection.deleteFromDocument();
            const div = document.createElement("div");
            div.innerHTML = paste;
            let node = null;
            const newFragment = document.createDocumentFragment();
            while ( (node = div.firstChild) ) {
                newFragment.appendChild(node);
            }
            selection.getRangeAt(0).insertNode(newFragment);
            selection.collapseToEnd();

            // get pasted content
            let content = document.querySelector('.editr--content').innerHTML;
            // apply it to the vue prop
            this.article = content;
        },
    },
    watch: {
        articleDetails: function (propVal) {
            this.title = propVal.title;
            this.article = removeWordStyling(propVal.article);
            this.blurb = propVal.description;
            this.url = propVal.url;
            this.image = this.article.image;
        }
    }
})
</script>
