<template>
  <div>
    <div class="row">
      <div class="col-12 text-left">
        <v-text-field
          label="Example Text"
          v-model.number="fieldInputs.text"
          @keyup="renderCanvases()"
        >
        </v-text-field>
      </div>
    </div>
    <div class="row">
      <div class="col-4 text-left">
        <v-text-field
          label="Starting Font Size"
          type="number"
          v-model.number="fieldInputs.fontSize"
          @change="renderCanvases()"
          @click="renderCanvases()"
        >
        </v-text-field>
      </div>
      <div class="col-4">
        <v-text-field
          label="Input Box Width"
          type="number"
          v-model.number="fieldInputs.rectangle.width"
          @change="renderCanvases()"
          @click="renderCanvases()"
        ></v-text-field>
      </div>
      <div class="col-4">
        <v-text-field
          label="Input Box Height"
          type="number"
          v-model.number="fieldInputs.rectangle.height"
          @change="renderCanvases()"
          @click="renderCanvases()"
        ></v-text-field>
      </div>
    </div>
    <div class="row">
      <div class="col-4 text-left">
        <v-text-field
          label="Maximum letter count"
          type="number"
          v-model.number="fieldInputs.letterCount"
        >
        </v-text-field>
      </div>
      <div class="col-4">
        <v-text-field
          label="X Axis"
          type="number"
          v-model.number="fieldInputs.rectangle.x"
          @change="renderCanvases()"
          @click="renderCanvases()"
        ></v-text-field>
      </div>
      <div class="col-4">
        <v-text-field
          label="Y Axis"
          type="number"
          v-model.number="fieldInputs.rectangle.y"
          @change="renderCanvases()"
          @click="renderCanvases()"
        ></v-text-field>
      </div>
      <div class="row">
        <div class="col-4 offset-4">
          <v-btn color="error" rounded @click="clearLine(selectedOption)" block>Clear line</v-btn>
        </div>        
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="col">
          <v-checkbox
            v-model="isCurvedText"
            label="Curved Text"
            @change="renderCanvases(); changeCurvedText(isCurvedText)"
          ></v-checkbox>
        </div>
      </div>
    </div>
    <div class="row" v-if="isCurvedText">
      <div class="col-4">
        <v-text-field
          label="Radius"
          type="number"
          v-model.number="fieldInputs.radius"
          @change="renderCanvases()"
          @click="renderCanvases()"
        ></v-text-field>
      </div>
      <div class="col-4">
        <v-text-field
          label="Rotation"
          type="number"
          v-model.number="fieldInputs.centerRotation"
          @change="renderCanvases()"
          @click="renderCanvases()"
        ></v-text-field>
      </div>
      <div class="col-4">
        <v-text-field
          label="Arc"
          type="number"
          v-model.number="fieldInputs.arc"
          @change="renderCanvases()"
          @click="renderCanvases()"
        ></v-text-field>
      </div>
    </div>
    <div class="row">
      <div class="col-4">
        <v-btn color="error" rounded @click="cancel(selectedOption.lineIndex)">Cancel</v-btn>
      </div>
      <div class="col-4">
        <v-btn color="primary" rounded @click="saveField(selectedOption)">
          Save and Add New Field
        </v-btn>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      isCurvedText: false,
      chosenFontFace: null
    };
  },

  props: [
    "fieldInputs",
    "selectedFontFace",
    "fontFaces",
    "showCurvedText",
    "selectedOption",
  ],
  methods: {
    renderCanvases() {
      this.$emit("renderCanvases");
    },
    changeCurvedText(isCurvedText) {
      this.$emit('changeCurvedText', isCurvedText);
    },
    changeTextFont(chosenFontFace) {
      this.$emit('changeTextFont', chosenFontFace);
    },
    cancel(lineIndex) {
      this.$emit("cancel",lineIndex);
    },
    saveField(selectedOption) {
      this.$emit('saveField', selectedOption);
    },
    clearLine(selectedOption) {
      this.$emit('clearLine', selectedOption);
    }
  },
  watch: {
    showCurvedText: function (propVal) {
      this.isCurvedText = propVal
    }
  },
};
</script>
