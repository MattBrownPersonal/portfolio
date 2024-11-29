<template>
    <v-dialog
      v-model="completedPaymentDialogue"
      persistent
      max-width="900px"
    >
        <v-card class="py-5 payment">
            <v-card-text class="pt-5 pb-0">
                <div class="row" v-if="orderNumber">
                    <div class="col-12">
                         <div class="text-center mt-5">
                            <span class="mdi md-100 mdi-checkbox-marked-circle-outline"></span>
                            <h4 style="color: green">Your order has been sent to the crematorium</h4>
                            <p>Your order number is <span class="font-weight-bold">{{orderNumber}}</span></p>
                            <p>{{ nextSteps }}</p>
                            <div class="modal-footer">       
                                <v-btn      
                                block
                                :color="styles.primary_colour"
                                :style="{color: styles.secondary_colour}"
                                @click="returnHome()"
                                >
                                Return to home screen</v-btn>
                            </div>
                        </div>
                    </div>
                </div>
            </v-card-text>                
        </v-card>
    </v-dialog>
</template>
<script>

export default {
    props: ['completedPaymentDialogue', 'orderNumber', 'nextSteps'],
    data () {
    return {
            primaryColor: '#0095a6',
            secondaryColor: '#ffffff', 
        }
    },
    methods: {
        returnHome() {
            this.$router.push({name: 'memorials', params: { deceased: this.deceased.name }})
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

