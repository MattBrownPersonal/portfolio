<template>
  <v-dialog
    v-model="AddNewPredefinedImageDialogue"
    persistent
    max-width="800px"
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">Add New Image</span>
      </v-card-title>
      <v-form ref="form" v-model="valid">
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="4">
                <v-text-field label="Image Title" v-model="name"></v-text-field>
              </v-col>
              <v-col cols="4">
                <v-text-field
                  prefix="£"
                  label="Image Price"
                  v-model="price"
                ></v-text-field>
              </v-col>
              <v-col cols="4">
                <v-file-input
                  accept="image/*"
                  :rules="imageRules"
                  show-size
                  v-model="image"
                  ref="file"
                  placeholder="Click to select image"
                >
                </v-file-input>
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
            @click="
              close();
              clear();
            "
          >
            Close
          </v-btn>
          <v-btn color="blue darken-1" text @click="submit()"> Save </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
    <v-snackbar v-model="snackbar" :timeout="timeout">
      {{ message }}

      <template v-slot:action="{ attrs }">
        <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </v-dialog>
</template>
<script>
export default {
  props: ["AddNewPredefinedImageDialogue"],
  data() {
    return {
      name: null,
      image: [],
      message: "",
      snackbar: false,
      timeout: 4000,
      imageRules: [
        (value) =>
          !value ||
          value.size < 2000000 ||
          "Image size should be less than 2 MB",
      ],
      valid: true,
      price: null,
    };
  },
  methods: {
    submit() {
      const image = new FormData();
      image.append("name", this.name);
      image.append("image", this.image);
      image.append("price", this.price);
      image.append("product_id", this.$route.params.product_id);
      axios
        .post("/api/predefinedImages", image)
        .then((res) => {
          (this.name = null), (this.image = []), this.close();
          this.$emit("fetchSingleProduct");
        })
        .catch((error) => {
          this.snackbar = true;
          this.message = error;
        });
    },
    clearForm() {
      this.name = "";
      this.image = [];
      this.type = null;
      this.price = null;
      this.$refs.form.resetValidation();
    },
    close() {
      this.image = [];
      this.type = null;
      this.price = null;
      this.$refs.form.resetValidation();
      this.$emit("closeForm", false);
    },
  },
};
</script>
