<template>
    <v-dialog
      v-model="newArticleDialog"
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
                             <v-col cols="12">
                                <wysiwyg v-model="article" />
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
                        @click="close()"
                    >
                    Close
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        @click="submit()"
                        text
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
    props: ['newArticleDialog'],
    data() {
        return {
            title: null,
            blurb: null,
            article: null,
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
                const article = new FormData();
                article.append('title', this.title);
                if (this.blurb) {
                    article.append('blurb', this.blurb);
                }
                article.append('article', this.article);
                article.append('image', this.image);
                article.append('url', this.url);
                axios.post('/api/articles', article, { headers: { "Content-Type": "multipart/form-data" } })
                    .then(res => {
                        this.close();
                        this.$emit('fetchArticles');
                        this.$emit('triggerSnackBar', res.data.message);
                        this.$refs.form.reset();
                    })
                    .catch(err => {
                        this.warningMessage = err.response.data.message;
                        this.$refs.form.reset();
                    });
            } 
        },
        close() {
            this.$refs.form.reset();
            this.$emit('closeForm', false)
        }
    },

})
</script>
