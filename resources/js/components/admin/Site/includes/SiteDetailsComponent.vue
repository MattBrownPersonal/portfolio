<template>
    <v-row>
        <v-col>
            <v-card>
                <v-card-title>
                    <p>{{site.name}} Site Details</p>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col class="col">
                            <p class="font-weight-bold">Site ID</p>
                            {{site.obitus_site_id}}
                        </v-col>
                        <v-col class="col">
                            <p class="font-weight-bold">Site URL (Click to View)</p>
                            <v-progress-circular
                            v-if="loading"
                            indeterminate
                            color="primary"
                            ></v-progress-circular>
                            <a v-else :href=appUrl+site.slug target="_blank">{{site.slug}}</a>
                        </v-col>
                        <v-col class="col">
                            <p class="font-weight-bold">Site Type</p>
                            {{site.site_type}}
                        </v-col>
                        <v-col class="col">
                            <p class="font-weight-bold">Parent Account</p>
                            {{ site.parent_account !== 'null' ? site.parent_account : 'None' }}
                        </v-col>
                        <v-col class="col">
                            <p></p>
                            <v-btn class="primary" @click="editSiteDetails()">Edit Site Details</v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>
<script>
export default {
    props: ['site'],
    data() {
        return {
            appUrl: null,
            loading: true
        }
    },
    methods: {
        getSiteUrl() {
            axios.get(`/api/siteUrl/`).then(res => {
                if(res.data) {
                    this.appUrl = res.data + 'm/';
                    this.loading = false;
                }
            })
            .catch(err => {
                this.snackbar = true;
                this.snackbarText = err.response.data.message;
            });
        },
        editSiteDetails() {
            this.$emit('editSiteDetails', true)
        }
    },
    created() {
        this.getSiteUrl()
    }
}
</script>
