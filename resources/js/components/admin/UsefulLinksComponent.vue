<template>
    <v-card elevation="2" class="mt-5" v-if="site">
        <v-card-text class="px-0 pb-0">
            <p class="text-h6 ml-5">Site Info Links</p>
            <v-divider class="mx-5"></v-divider>
            <v-list-item :to="{ name: 'sitearticles', params: { id: $route.params.id}}" style="text-decoration: none" class="px-0"> 
                <v-list-item-content class="py-0">                                    
                    <v-list-item-title>
                        <v-list-item-icon class="mx-5">
                            <v-icon>mdi-marker</v-icon>
                        </v-list-item-icon>
                        Articles
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
            <v-list-item :to="{ name: 'siteFaqs', params: { id: $route.params.id}}" style="text-decoration: none" class="px-0"> 
                <v-list-item-content class="py-0">                                    
                    <v-list-item-title>
                        <v-list-item-icon class="mx-5">
                            <v-icon>mdi-chat-question-outline</v-icon>
                        </v-list-item-icon>
                        FAQs
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
            <v-list-item  v-if="site.uses_categories === 1" :to="{ name: 'showsitemorialisations', params: { id: $route.params.id}}" style="text-decoration: none" class="px-0"> 
                <v-list-item-content class="py-0">                                
                    <v-list-item-title>
                        <v-list-item-icon class="mx-5">
                            <v-icon>mdi-script-outline</v-icon>
                        </v-list-item-icon>
                        Categories 
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>  
            <v-list-item :to="{ name: 'showallsiteproducts', params: { site: $route.params.id}}" style="text-decoration: none" class="px-0"> 
                <v-list-item-content class="py-0">
                    <v-list-item-title>
                        <v-list-item-icon class="mx-5">
                                <v-badge
                                    :content="productCount"
                                    :value="productCount"
                                    color="red"
                                    overlap
                                >
                                <v-icon>mdi-script-outline</v-icon>
                            </v-badge>
                        </v-list-item-icon>
                        Products
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>           
            <v-list-item :to="{ name: 'allstitestaff', params: { id: $route.params.id}}" style="text-decoration: none" class="px-0"> 
                <v-list-item-content class="py-0">                                    
                    <v-list-item-title>
                        <v-list-item-icon class="mx-5">
                            <v-icon>mdi-account-group</v-icon>
                        </v-list-item-icon>
                        Staff
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
            <v-list-item :to="{ name: 'showstaff', params: { id: $route.params.id}}" style="text-decoration: none" class="px-0"> 
                <v-list-item-content class="py-0">                                    
                    <v-list-item-title>
                        <v-list-item-icon class="mx-5">
                            <v-icon>mdi-home</v-icon>
                        </v-list-item-icon>
                        View Site
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
        </v-card-text>
    </v-card>
</template>
<script>
import { bus } from '../../admin'
export default {
    props: ['site'],
    data() {
        return {
            productCount: 0
        }
    },
    methods: {
        checkCategories() {
            axios.get(`/api/checkSiteCategories/${this.site.id}`)
                .then(res => {
                    this.productCount = res.data;
                })
                .catch((err) => {
                    alert(err)
                });
        }
    },
    watch: {
        site: function () {
            this.checkCategories();
        }
    },
    mounted() {        
        bus.$on('updatePage', () => {
            this.checkCategories();
        });
    }
}
</script>
<style scoped>
.actionLink {
    cursor: pointer;
}
</style>