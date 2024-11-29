<template>
    <div>
        <div v-if="$vuetify.breakpoint.smAndDown" class="d-flex justify-content-between align-items-start">
            <div class="col-4 col-sm-3 p-0">
                <router-link :to="{ name: 'memorials' }">
                    <img v-if="styles.image" :src="styles.image.imageurl" class="sitelogo">
                </router-link>
            </div>

            <div class="col-8 col-sm-9 p-0">
                <span class="show-menu" @click="overlay = !overlay">
                    <v-icon v-if="!overlay" large class="primary-colour">mdi-menu</v-icon>
                    <v-icon v-else large class="primary-colour">mdi-close</v-icon>
                </span>
                <v-overlay :opacity="opacity" :value="overlay" :color="styles.secondary_colour"
                    class="overlay">
                    <div class="col-12 text-center">
                        <h2 class="mb-5"><router-link :to="{ name: 'categories' }"
                                class="h7 primary-colour">Memorials</router-link></h2>
                        <h2 class="mb-5"><router-link
                                :to="{ name: 'bereavementsupport' }"
                                class="h7  primary-colour">Bereavement Support</router-link> </h2>
                        <h2 class="mb-5"><router-link :to="{ name: 'faqs' }"
                                class="h7  primary-colour">FAQs</router-link> </h2>
                        <div class="callback-menu">
                            <v-btn rounded class="btn" @click="launchCallbackDialogue()">
                                Request a callback</v-btn>
                        </div>
                    </div>

                </v-overlay>
            </div>
        </div>
        <div v-else>
            <div class="d-flex justify-content-between align-items-center">
                <div class="col-3 p-0">                        
                    <router-link :to="{ name: 'memorials' }">
                        <img v-if="styles.image" :src="styles.image.imageurl" class="sitelogo">
                    </router-link>
                </div>
                <div class="col-6 p-0">
                    <div class="text-center">                      
                        <router-link :to="{ name: 'categories' }"
                            class="primary-colour text-size-regular">
                            Memorials
                        </router-link>
                        <router-link :to="{ name: 'bereavementsupport'}"
                            class="middle-nav-link primary-colour text-size-regular">
                            Bereavement Support
                        </router-link>
                        <router-link :to="{ name: 'faqs' }"
                            class="primary-colour text-size-regular">
                            FAQs
                        </router-link>
                    </div>
                </div>
                <div class="col-3 p-0">
                    <v-btn rounded class="btn" @click="launchCallbackDialogue()">
                        Request a callback
                    </v-btn>
                </div>
            </div>
        </div>
        <div class="row mb-0 py-0">
            <div class="col-12">
                <v-breadcrumbs class="px-0 pb-0 primary-colour text-size-tiny" :items="items"></v-breadcrumbs>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['items'],
    data: () => ({
        absolute: true,
        opacity: 1,
        overlay: false,
    }),
    methods: {
        launchCallbackDialogue() {
            this.$emit('launchCallbackDialogue', true)
        }
    },
    computed: {
        deceased() {
            return this.$store.state.deceased;
        },
        styles() {
            return this.$store.state.styles;
        }
    },
}
</script>