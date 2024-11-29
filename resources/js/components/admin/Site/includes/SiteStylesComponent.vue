<template>
    <v-row>
        <v-col>
            <v-card elevation="2">
                <v-card-title>
                    <p>Styles</p>
                </v-card-title>
                <v-card-text>
                    <template v-if="siteStyle">
                        <h6>Site Logo</h6>
                        <v-img v-if="siteStyle.image" :src="siteStyle.image.imageurl"  max-height="300" max-width="300"></v-img>
                        <br />
                        <h6 class="mt-5 mb-3">Colour Palette</h6>
                        <v-row justify="center">
                            <v-col class="col-3">
                                <v-sheet
                                    class="colorBlob"
                                    v-bind:color="siteStyle.primary_colour"
                                    elevation="3"
                                    height="100"
                                    width="100"
                                    ></v-sheet>
                                <p class="mt-5">Primary Colour</p>
                            </v-col>

                            <v-col class="col-3">
                                <v-sheet
                                    class="colorBlob"
                                    v-bind:color="siteStyle.secondary_colour"
                                    elevation="3"
                                    height="100"
                                    width="100"
                                    ></v-sheet>
                                <p class="mt-5">Secondary Colour</p>
                            </v-col>

                            <v-col class="col-3">
                                <v-sheet
                                    class="colorBlob"
                                    v-bind:color="siteStyle.primary_font_colour"
                                    elevation="3"
                                    height="100"
                                    width="100"
                                    ></v-sheet>
                                <p class="mt-5">Primary Font Colour</p>
                            </v-col>

                            <v-col class="col-3">
                                <v-sheet
                                    class="colorBlob"
                                    v-bind:color="siteStyle.secondary_font_colour"
                                    elevation="3"
                                    height="100"
                                    width="100"
                                    ></v-sheet>
                                <p class="mt-5">Secondary Font Colour</p>
                            </v-col>
                        </v-row>
                    </template>
                    <template v-else>
                        <p>There are no styles listed for this site.</p>
                    </template>
                </v-card-text>
            </v-card>               
        </v-col>
    </v-row>
</template>
<script>
export default {
    props: ['siteStyle'],
    data() {
        return {
            deleteSiteLogoDialogue: false,
        }
    },
    methods: {
        contactDetails() {
            this.$emit('contactDetails', true)
        }
    }
}
</script>