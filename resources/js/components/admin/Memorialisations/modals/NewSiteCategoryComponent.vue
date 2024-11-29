<template>
  <v-dialog
    v-model="newSiteMemorialisationDialog"
    max-width="1000px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">Add New Memorialisation</span>
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
              <v-col cols="6">
                <v-autocomplete
                  :items="allMemorialisations"
                  label="Memorialisation*"
                  autofocus
                  required
                  v-model="memorialisation"
                  :rules="memorialisationRules"
                  item-text="name"
                  item-value="id"
                ></v-autocomplete>
              </v-col>
              <v-col cols="6">
                <v-file-input
                  label="Image*"
                  show-size
                  required
                  v-model="image"
                  :rules="imageRules"
                  ref="file"
                  placeholder="Click to select image"
                  accept="image/*"
                ></v-file-input>
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
  props: ["newSiteMemorialisationDialog", "selectedSite"],
  data: function() {
    return {
      allMemorialisations: [],
      memorialisation: null,
      memorialisationRules: [
        v => !!v || 'Memorialisation is required',
      ],
      image: [],
      imageRules: [
        v => !!v || 'Image is required',
        v => !v || v.size < 2000000 || 'Image size should be less than 2 MB',
      ],
      description: null,
      valid: true,
      errors: [],
    };
  },
  methods: {
    submit() {
      const newMemorialisation = new FormData();
      newMemorialisation.append("memorialisation_id", this.memorialisation);
      newMemorialisation.append("site_id", this.selectedSite);
      newMemorialisation.append("description", this.description);
      newMemorialisation.append("image", this.image);
      axios
        .post("/api/storeSiteMemorialisation/", newMemorialisation)
        .then((res) => {
          this.close();
          this.$emit("fetchMemorialisations");
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
      this.$refs.form.reset();
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
};
</script>
