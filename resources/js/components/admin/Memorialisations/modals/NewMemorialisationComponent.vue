<template>
  <v-dialog v-model="newMemorialisationDialog" persistent max-width="600px">
    <v-card>
      <v-card-title>
        <span class="text-h5">Add New Category</span>
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
              <v-col cols="12">
                <v-text-field
                  label="Category Name*"
                  autofocus
                  required
                  name="name"
                  v-model="name"
                  :rules="nameRules"
                ></v-text-field>
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
import { exit } from 'process';

export default {
  props: ["newMemorialisationDialog"],
  data() {
    return {
      name: "",
      nameRules: [
        v => !!v || 'Category name is required',
        v => (v && v.length <= 255) || 'Category name must be less than 255 characters',
      ],
      valid: true,
      errors: [],
    };
  },
  methods: {
    submit() {
      let memorialisation = new FormData();
      memorialisation.append("name", this.name);
      axios
        .post("/api/memorialisations", memorialisation)
        .then((res) => {
          this.close();
          this.$emit("fetchMemorialisations");
          this.$emit("triggerSnackBar", "Category added successfully");
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
      this.$emit("closeForm", false);
    },
  },
};
</script>
