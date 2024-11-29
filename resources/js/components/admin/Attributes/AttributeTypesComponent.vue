<template>
    <div class="px-5">
        <v-row>
            <v-col class="col-6 offset-3">
                <v-row class="addNewButtonWrapper">
                    <v-col class="col-md-4 offset-4 d-flex justify-center" no-gutters>
                        <v-btn
                        fab fixed right bottom
                        color="primary"
                        dark
                        @click="newAttributeTypeDialog = true"
                        >
                        <v-icon>add</v-icon>
                        </v-btn>
                    </v-col>
                </v-row>
                <v-card>
                    <v-card-title>
                    Attribute Types
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="AttributeTypesSearch"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    />
                    </v-card-title>
                        <v-data-table
                        :headers="attributeTypesHeaders"
                        :items="attributeTypes"
                        :items-per-page="10"
                        class="elevation-1"
                        :loading="attributeTypesTableLoading"
                        loading-text="Loading... Please wait"
                        :search="AttributeTypesSearch"
                        >
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
        <NewAttributeTypeComponent
            v-bind:newAttributeTypeDialog="newAttributeTypeDialog" 
            v-on:closeForm="closeFormChange($event)"
            v-on:fetchAttributeTypes="fetchAttributeTypes()"
            v-on:triggerSnackBar="triggerSnackBar($event)"
        />
        <v-snackbar
            v-model="snackbar"
            :timeout="timeout"
        >
            {{ snackbarText }}

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
import NewAttributeTypeComponent from './modals/NewAttributeTypeComponent'
export default {
    components: {
        NewAttributeTypeComponent,     
    },
    data () {
        return {
            attributeTypesHeaders: [
                { text: 'Name',align: 'start', value: 'name', width: '50%'},
            ],
            attributeTypes: [],
            selectedAttributeTypes: '',
            attributeTypesTableLoading: false,
            AttributeTypesSearch: '',
            snackbar: false,
            snackbarText: '',
            timeout: 4000,
            id: null,
            permissions: [],
            newAttributeTypeDialog: false
        }
    },
    methods: {
        fetchAttributeTypes() {
            axios.get('/api/attributeTypes')
            .then ( res => {
                this.attributeTypes = res.data;
            });
        },
        closeFormChange(state) {
        this.newAttributeTypeDialog = state;
        },
    },
    created () {
        this.fetchAttributeTypes();
    },

}
</script>
