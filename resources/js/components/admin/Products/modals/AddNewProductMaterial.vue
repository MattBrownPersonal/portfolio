<template>
  <v-dialog
    v-model="AddNewProductMaterialDialogue"
    persistent
    max-width="1000px"
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">Add New Material</span>
      </v-card-title>
      <v-form ref="form" v-model="valid">
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="4">
                <v-text-field
                  label="Material Name*"
                  v-model="type"
                  :rules="rules"
                ></v-text-field>
              </v-col>
              <v-col cols="4">
                <v-file-input
                  show-size
                  v-model="image"
                  ref="file"
                  placeholder="Click to select image"
                  accept="image/*"
                  :rules="imageRules"
                ></v-file-input>
              </v-col>
              <v-col cols="4">
                <v-text-field
                  label="Price*"
                  v-model="price"
                  :rules="rules"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
          <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close()"> Close </v-btn>
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
  props: ["AddNewProductMaterialDialogue"],
  data() {
    return {
      type: null,
      image: [],
      price: null,
      message: "",
      snackbar: false,
      timeout: 4000,
      rules: [
        (v) => !!v || "Field cannot be left blank",
        (v) =>
          (v && v.length <= 255) || "Input must be less than 255 characters",
      ],
      imageRules: [
        (value) =>
          !value ||
          value.size < 2000000 ||
          "Image size should be less than 2 MB",
      ],
      valid: true,
    };
  },
  methods: {
    submit() {
      if (this.$refs.form.validate()) {
        const material = new FormData();
        material.append("type", this.type);
        material.append("image", this.image);
        material.append("product_id", this.$route.params.product_id);
        material.append("price", this.price);
        axios
          .post("/api/materialTypes", material)
          .then((res) => {
            this.close();
            this.$emit("fetchSingleProduct");
          })
          .catch((error) => {
            this.snackbar = true;
            this.message = error;
          });
      }
    },
    close() {
      this.$emit("closeForm", false);
      this.image = [];
      this.type = null;
      this.price = null;
      this.$refs.form.resetValidation();
    },
  },
};
</script>
