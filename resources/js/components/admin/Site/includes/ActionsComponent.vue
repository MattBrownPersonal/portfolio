<template>
    <v-card elevation="2">
        <v-card-text>
            <p class="text-h6">Actions</p>
            <v-divider></v-divider>
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title class="pb-4">
                        <span class="actionLink" @click="editSiteStyleDetails()">
                            <v-icon left>mdi-pencil</v-icon>
                            Edit
                        </span>
                    </v-list-item-title>
                    <v-list-item-title>
                        <v-icon left>mdi-keyboard-backspace</v-icon>
                        <span @click="$router.go(-1)" class="actionLink">Back</span>
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-card-text>
    </v-card>
</template>
<script>
export default {
    data() {
        return {

        }
    },
    methods: {
        editSiteStyleDetails() {
            this.$emit('editSiteStyleDetails', true)
        }
    }
}
</script>
<style scoped>
.crudMenu{
    list-style-type:none;
}
.actionLink {
    cursor: pointer;
}
.siteLogo {
    
}
.colorBlob {
    border-radius: 50%;
}
</style>