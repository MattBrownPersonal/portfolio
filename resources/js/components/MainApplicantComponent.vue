<template>
    <v-app class="secondary-background-colour"> 
        <v-main>
            <router-view :key="$route.fullPath"></router-view>
        </v-main>
        <v-footer class="primary-background-colour footer-background mt-5" v-if="deceased" style="height: 100%;">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div v-if="deceased" class="text-center footer-padding">
                            <router-link :to="{ name: 'categories' }" class="footer-link text-size-regular">Memorials</router-link> 
                            <br class="d-md-none">
                            <br class="d-md-none">
                            <router-link :to="{ name: 'bereavementsupport' }" class="footer-link middle-footer-links text-size-regular">Bereavement Support</router-link>  
                            <br class="d-md-none">
                            <br class="d-md-none">
                            <router-link :to="{ name: 'faqs' }" class="footer-link text-size-regular">FAQs</router-link> 
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 footer-links text-center text-md-right">
                        <router-link :to="{ name: 'tos', hash: '#privacy' }" class="footer-link text-size-regular">Privacy</router-link>
                        <router-link :to="{ name: 'tos'}" class="footer-link middle-footer-links text-size-regular">T&Cs</router-link>
                        <router-link :to="{ name: 'tos', hash: '#cookies'}" class="footer-link text-size-regular">Cookies</router-link>
                    </div>
                </div>
            </div>
        </v-footer>
    </v-app>
</template>
<script>
import { getCodeFromParams } from '../deceased';
import { bus } from '../app'
export default {
    watch: {
        $route: {
            handler: function(newRouteValue) {
                if (this.plausible) this.plausible('pageview', { u: newRouteValue.path });
            },
        deep: true
        }
    },
    mounted() {
        if (this.plausible) this.plausible('pageview', { u: this.$route.path });
    },
    methods: {
        launchCallbackDialogue() {
            bus.$emit('launchCallbackDialogue', true)
        }
    },
    computed: {
        deceased() {
            return this.$store.state.deceased;
        },
        styles() {
            return this.$store.state.styles;
        },
        plausible() {
            return window.plausible;
        }
    },
    beforeCreate() {
        let code = getCodeFromParams(this.$route.params);
        if (isNaN(code)) {
            this.$store.dispatch('getStyles', this.$route.params.code);
        } else {
            this.$store.dispatch('fetchDeceased', code);
            this.$store.dispatch('getStyles', code);
        }

        bus.$on('updateStyles', (data) => {
            this.$store.dispatch('getStyles', data);
            this.$store.dispatch('fetchDeceased', data);
        });
    },
}
</script>
