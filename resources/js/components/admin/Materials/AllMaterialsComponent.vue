<template>
    <div>
        <v-row class="addNewButtonWrapper">
            <v-col class="col-md-4 offset-4 d-flex justify-center" no-gutters>
                <v-btn
                fab fixed bottom right
                color="primary"
                dark
                @click="newMaterialDialogue = true"
                >
                <v-icon>add</v-icon>
                </v-btn>
            </v-col>
        </v-row>
        <v-col class="col-6 offset-3">
            <v-card elevation="2">
                <v-card-title>
                    <p>Site Articles</p>                 
                    </v-card-title>
                    <v-data-table
                    :headers="headers"
                    :items="materials"
                    :items-per-page="10"
                    class="elevation-1"
                    :loading="materialsTableLoading"
                    loading-text="Loading... Please wait"
                    :search="search"
                    >
                    <template v-slot:[`item.imageurl`]="{item}">
                        <v-img :src="item.imageurl" class="material"></v-img>
                    </template>
                </v-data-table>                
            </v-card>               
        </v-col>
        <NewMaterial
            v-bind:newMaterialDialogue="newMaterialDialogue"
            v-on:fetchMaterials="fetchMaterials()"
            v-on:closeForm="newMaterialDialogue = false"
        />
        <v-snackbar
        v-model="snackbar"
        :timeout="timeout"
        >
        {{ message }}

        <template v-slot:action="{ attrs }">
            <v-btn
            color="blue"
            text
            v-bind="attrs"
            @click="snackbar = false"
            >
            Close
            </v-btn>
        </template>
        </v-snackbar>
    </div>
</template>
<script>
import NewMaterial from './modals/NewMaterialComponent.vue'

export default {
    components: {
        NewMaterial,
    },
    data() {
        return {
            headers: [
                { text: 'Type', align: 'start', value: 'type', width: '50%' },
                { text: 'Material', value: 'imageurl', width: '50%'},
            ],
            materials: [],
            materialsTableLoading: false,
            search: '',
            selectedUser: '',
            message: '',
            snackbar: false,
            timeout: 4000,
            newMaterialDialogue: false,
        }
    },
    methods: {
        fetchMaterials() {
            axios.get('/api/materialTypes')
            .then( res => {
                this.materials = res.data;
            })
            .catch(error => {
                this.snackbar = true;
                this.message = error;
            })
        },
    },
    mounted() {
        this.fetchMaterials()
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
.material {
    width: 100px;
    border-radius: 200px;
    height: 100px;
}
</style>