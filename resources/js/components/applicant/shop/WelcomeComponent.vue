<template>
    <div class="obitus-secondary-background" style="width: 100%; height: 90%; overflow: hidden;">
        <div class="row text-center">
            <div class="mx-auto welcome-container">
                <h1 class="obitus-primary">Welcome</h1>
                <h3 class="obitus-primary text-size-medium welcome-text">
                    {{ welcome_copy ? welcome_copy : 'Please enter your privacy key for bereavement support and memorial options.' }}
                </h3>
                <v-form v-model="valid" ref="form" @submit.prevent="submit()" class="pt-5">
                    <div class="row input">
                        <v-text-field
                            v-model="code"
                            label="Privacy key"
                            required
                            rounded
                            outlined
                            background-color="#fff"
                            single-line
                            class="code-textfield"
                            :rules="codeRules"
                            >
                        </v-text-field>
                        <v-btn 
                            type="submit"
                            rounded
                            class="btn-welcome btn-enter obitus-primary ml-xl-3"
                            :block="$vuetify.breakpoint.mobile"
                            
                            >
                            Enter
                        </v-btn>
                    </div>
                    <div class="row input">
                        <v-alert v-if="error" outlined type="error" text>
                            {{ error }}
                        </v-alert>
                    </div>
                </v-form>
            </div>
        </div>
        <div class="powered-by mx-auto">
            Powered by
            <span class="brand"><span>Obitus</span></span>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { bus } from '../../../app'
export default {
    data () {
        return {
            code: '',
            codeRules: [
                v => !!v || 'Code is required',
            ],
            error: null,
            welcome_copy: ''
      }  
    },
    mounted() {
        this.getSettings();
    },
    methods: {
        submit() {
            if (typeof this.code !== "string" || (typeof this.code === "string" && this.code.length < 1)) {
                this.error = null;
                this.$refs.form.reset();
                return;
            }
            axios.get(`/api/checkLandingCode/${this.code}`)
                .then((res) => {
                    const code = res.data.code + '-' + res.data.firstname + '-' + res.data.lastname
                    this.$router.push({ name: 'memorials', params: { code: code } })
                    bus.$emit('updateStyles', res.data.code);
                    this.$refs.form.reset();
                })
                .catch((error) => {
                    this.error = error.response.data.error;
                    this.$refs.form.reset();
                });
        },
        getSettings() {
            axios
                .get(`/api/fetchSettings`)
                .then(res => {
                    this.welcome_copy = res.data.welcome_copy.value
                });
            },
    } 
}
</script>

<style  scoped>
    div ::v-deep .v-input__slot fieldset legend {
        display: none !important;
    }
</style>