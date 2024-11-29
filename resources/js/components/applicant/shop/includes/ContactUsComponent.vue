<template>
    <div class="container br">
        <div class="row px-10 align-items-baseline" >
            <p class="text-size-small ">Please reach out to our team if you have any further questions?</p>
            <div class="col-lg-4 ml-auto text-left">
                <v-btn
                    rounded
                    class="btn elevation-0"
                    @click="launchCallbackDialogue()">
                    Contact our team</v-btn>
            </div>
        </div>
    </div>
</template>
<script>
import { bus } from '../../../../app'
export default {
    methods: {
        launchCallbackDialogue() {
            bus.$emit('launchCallbackDialogue', true)
        }
    },
}
</script>
<style scoped>
    .br {
        border-radius: 7px;
    }

</style>