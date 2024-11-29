<template>
  <v-dialog
    v-model="editCategoryDetailsDialogue"
    max-width="1000px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">Edit Memorialisation</span>
      </v-card-title>
      <v-alert
        type="error"
        class="mx-3"
        v-if="errors.length"
      >
        <ul>
          <li v-for="error in errors">
              {{ error }}
          </li>
        </ul>
      </v-alert>
      <v-form 
        ref="form" 
        @submit.prevent="submit()" 
        v-model="valid"
      >
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="10">
                <v-file-input
                  label="Click to select new image*"
                  show-size
                  required
                  v-model="image"
                  ref="file"
                  placeholder="Click to select image"
                  accept="image/*"
                ></v-file-input>                
              </v-col>
              <v-col cols="2">
                Current Image:
                <v-img :src="imageUrl" width=100px></v-img>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <wysiwyg
                  v-model="description"
                  placeholder="Category Description"
                />
              </v-col>
            </v-row>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn 
            color="blue darken-1"
            text 
            @click="close()"
          >
            Close
          </v-btn>
          <v-btn 
            color="blue darken-1" 
            type="submit"
            :disabled="!valid"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  props: ["editCategoryDetailsDialogue", "selectedMemorialisation"],
  data: function() {
    return {
      image: [],
      description: null,
      valid: true,
      errors: [],
      imageUrl: null
    };
  },
  methods: {
    submit() {
      const memorialisation = new FormData();
      memorialisation.append("memorialisation_id", this.selectedMemorialisation.memorialisation_id);
      memorialisation.append("site_id", this.selectedMemorialisation.site_id);
      memorialisation.append("description", this.description);
      memorialisation.append('_method', 'PUT');
      if (this.image) {
        memorialisation.append("image", this.image);
      }
      memorialisation.append("path", this.imageUrl);
      axios
        .post("/api/storeSiteMemorialisation/", memorialisation)
        .then((res) => {
          this.close();
          this.$emit("fetchCategoryData");
          this.$emit("triggerSnackBar", res.data.message);
          this.$refs.form.reset();
        })
        .catch((err) => {
          this.errors = err.response.data.errors;
          this.$refs.form.reset();
        });
    },
    close() {
      this.errors = [];
      this.description = null;
      this.$emit("closeForm", false);
    },    
    fetchallMemorialisations() {
      axios.get("/api/memorialisations").then((res) => {
        this.allMemorialisations = res.data;
      });
    },
  },
  mounted() {
    this.fetchallMemorialisations();
  },
  watch: {
    selectedMemorialisation: function (propVal) {
      this.memorialisation = propVal.memorialisation.id;
      this.description = propVal.description;
      this.imageUrl = propVal.image
    }
  }
};
</script>
