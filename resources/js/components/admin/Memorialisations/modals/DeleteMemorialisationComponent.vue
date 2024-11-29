<template>
  <v-dialog
    v-model="deleteMemorialisationDialog"
    persistent
    max-width="700px"
    height="400px"
  >
      <v-card class="text-center">
          <v-card-title>
              <span class="text-h5 red--text ">Are you sure you wish to delete this category?</span>
          </v-card-title>
          <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
              {{ warningMessage }}
          </v-alert>         
          <v-col class="d-flex justify-center px-0">
              <v-btn
              @click="closeForm()"
              class="mr-2"
              >Cancel</v-btn>
              <v-btn 
              color="error"
              @click="sendCategoryDelete()"               
              >Delete</v-btn>
          </v-col>          
      </v-card>
  </v-dialog>
</template>
<script>
import { bus } from '../../../../admin'
export default ({
  props: ['deleteMemorialisationDialog', 'category'],
  data: function() {
      return {            
          warningMessage: '',
      }
  },
  methods: {
      sendCategoryDelete(){
        this.warningMessage = '';
        axios.delete(`/api/memorialisations/${this.category.id}`)
          .then(res => {
              this.closeForm();
              this.$emit('triggerSnackBar', res.data.message);
              this.$emit('fetchMemorialisations');
          })
          .catch(err => {
              this.warningMessage = err.response.data.message;
          });
      },
      closeForm() {
        this.warningMessage = '';
        this.$emit('closeForm', false)
      }
  },
})
</script>
