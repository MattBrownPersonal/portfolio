<template>
    <v-row>
        <v-col>
            <v-card>
                <v-card-title>
                    <p v-if="site">Contact Details</p>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col class="col">
                            <p class="font-weight-bold">Name</p>
                            {{ site.primary_contact_name !== "null" ? site.primary_contact_name : '-' }}
                        </v-col>
                        <v-col class="col">
                            <p class="font-weight-bold">Email Address</p>
                            {{ site.primary_contact_email !== "null" ? site.primary_contact_email : '-' }}
                        </v-col>
                        <v-col class="col">
                            <p class="font-weight-bold">Phone Number</p>
                            {{ site.phone !== "null" ? site.phone : '-' }}
                        </v-col>
                        <v-col class="col">
                            <p class="font-weight-bold">Address</p>
                            <div v-if="site.street !== 'null'">
                                {{site.street}} <br>
                                {{site.city}} <br>
                                {{site.county}} <br>
                                {{site.country}} <br>
                                {{site.postcode}}
                            </div>
                            <div v-else>
                                -
                            </div>
                        </v-col>   
                        <v-col class="col">
                            <p></p>
                            <v-btn class="primary" @click="contactDetails()">Edit Contact Details</v-btn>
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

        }
    },
    methods: {
        contactDetails() {
            this.$emit('contactDetails', true)
        }
    }
}
</script>