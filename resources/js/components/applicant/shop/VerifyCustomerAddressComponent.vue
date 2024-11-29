<template>
    <div>
        <div class="container pb-5">
            <TopMenuComponent
                v-on:launchCallbackDialogue="launchCallbackDialogue($event)"
                v-bind:items="items"
            />
            <div class="row">
                <div class="col-12 mt-5 pt-5">
                    <h1 class="primary-colour mb-5 text-center">Verifying Your Email Address</h1>
                </div>
                <div class="col-12 mt-5 pt-5">
                    <h2 class="primary-colour mb-5 text-center">{{ status }}</h2>
                </div>
                <div class="col-12 col-lg-8 offset-lg-2">
                    <div class="row mt-5">
                        <div class="col-12 text-center">
                            <a href="#" @click="launchCallbackDialogue(true)" class="contact-us">Contact us</a> for further
                            assistance
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
import axios from 'axios';
import { bus } from '../../../app'
export default {
    components: {
        CallbackModalComponent,
        TopMenuComponent,
    },
    data() {
        return {
            status: 'Please wait...',
            callbackDialogue: false,
        }
    },
    methods: {
        verifyEmail() {
            const token = this.$route.params['token'];
            axios.get(`/api/verifyEmailAddress/${token}`)
            .then((res) => {
                this.status = 'Verified';
            })
            .catch((error) => {
                this.status = 'Could not be verified';
            });
        },
        closeForm(state) {
            this.callbackDialogue = state;
        },
        launchCallbackDialogue(state) {
            this.callbackDialogue = state;
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
            ];
        }
    },
    mounted() {
        bus.$on('launchCallbackDialogue', (data) => {
            this.callbackDialogue = data
        });
        this.verifyEmail();
    },
}
</script>
