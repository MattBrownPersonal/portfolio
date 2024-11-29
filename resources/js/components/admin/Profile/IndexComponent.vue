<template>
    <v-app class="px-4">
        <v-row>
            <v-col class="col-2">
                <v-card elevation="2">
                    <v-card-text>
                        <p class="text-h6">Actions</p>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <!-- <v-list-item-title class="pb-4">
                                    <span class="actionLink" @click="editDeceasedDialog = true;">
                                        <v-icon left>mdi-pencil</v-icon>
                                        Edit
                                    </span>
                                </v-list-item-title> -->
                                <v-list-item-title>
                                    <v-icon left>mdi-keyboard-backspace</v-icon>
                                    <span @click="$router.go(-1)" class="actionLink">Back</span>
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col class="col-10">
                <v-row>
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Your Details</p>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">First Name</p>
                                        {{ user.firstname }}
                                    </v-col>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">Last Name</p>
                                        {{ user.lastname }}
                                    </v-col>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">Email</p>
                                        {{ user.email }}
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card elevation="2">
                            <v-card-title>
                                <p>Email Preferences</p>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col-12 pb-0">
                                        <p>Do you want to receive emails whenever an order is placed?</p>
                                    </v-col>
                                </v-row>
                                <v-row>
                                  <div class="col-6 pt-0">
                                      <v-switch
                                        v-model="user.email_notify_on_new_order"
                                        inset 
                                        :label="user.email_notify_on_new_order == 0 ? 'No' : 'Yes'"
                                        @change="sendEmailResponse"
                                        ></v-switch>
                                  </div>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
         <v-snackbar
            v-model="snackbar"
            :timeout="timeout"
            color="green"
        >
            {{ snackbarText }}

            <template v-slot:action="{ attrs }">
            <v-btn
                color="white"
                text
                v-bind="attrs"
                @click="snackbar = false"
            >
                Close
            </v-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>
<script>
export default {
    inject: ["currentUser"],
    data () {
        return {
            user: [],
            user_id: this.currentUser.id,
            snackbar: false,
            snackbarText: '',
            timeout: 4000
        }
    },
    methods: {
        fetchUser() {
            axios.get(`/api/users/${this.user_id}`)
            .then(res => {
                this.user = res.data;
            })
        },
        triggerSnackBar($event) {
            this.snackbarText = $event
            this.snackbar = true;
        },
        sendEmailResponse() {
            axios.post('/api/user', {value: this.user.email_notify_on_new_order})
        }
    },
    mounted () {
        this.fetchUser();
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
</style>