<template>
    <div>
        <div class="container pb-5">
            <TopMenuComponent 
                v-on:launchCallbackDialogue="launchCallbackDialogue($event)"
                v-bind:items="items"
            />
            <div class="row">
                <div class="col-12 mt-5 pt-5">
                    <h1 class="primary-colour mb-5 text-center">FAQs</h1>
                </div>   
                <div class="col-12 col-lg-8 offset-lg-2">             
                    <div v-for="(faq, index) in faqs" :key="faq.id" v-if="faq.hidden === 0"
                        class="accordion-container accordion-padding">
                        <div class="accordion heading-h6" @click="toggleAccordion(index)">
                            <div class="row">
                                <div class="col-1 accordion-icon text-center"></div>
                                <div class="col-10 col-lg-11"><span>{{ faq.question }}</span></div>
                            </div>
                        </div>
                        <div class="faq-panel">
                            <p class="text-size-small">{{ faq.answer }}</p>
                        </div>
                    </div>
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
import { getCodeFromParams } from '../../../deceased';
import { bus } from '../../../app'
export default {
    components: {
        CallbackModalComponent,
        TopMenuComponent,
    },
    data() {
        return {
            callbackDialogue: false,
            faqs: null
        }
    },
    methods: {
        closeForm(state) {
            this.callbackDialogue = state;
        },
        getFaqs() {
            let code = getCodeFromParams(this.$route.params);
            if (isNaN(code)) {
                code = this.$route.params.code;
            } else {
                code = getCodeFromParams(this.$route.params);
            }
            axios.get(`/api/getFaqs/${code}`)
                .then(res => {
                    this.faqs = res.data;
                });
        },
        launchCallbackDialogue(state) {
            this.callbackDialogue = state;
        },
        toggleAccordion(index) {
            const acc = document.getElementsByClassName("accordion");

            const accIcon = document.getElementsByClassName("accordion-icon");

            const accordionContainer = document.getElementsByClassName("accordion-container");

            accIcon[index].classList.toggle("active");

            const panel = acc[index].nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        }
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
                    text: 'FAQs',
                    link: true,
                    exact: true,
                    disabled: true,
                },
            ];
        }
    },
    mounted() {
        this.getFaqs();
        bus.$on('launchCallbackDialogue', (data) => {
            this.callbackDialogue = data
        });
    },
}
</script>
