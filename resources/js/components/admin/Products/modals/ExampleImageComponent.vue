<template>
    <v-dialog v-model="addExampleImageDialog" persistent fullscreen transition="dialog-bottom-transition">
        <v-card>
            <v-card-title>
                <span class="text-h5 red--text">{{ modalType }} Image Text</span>
            </v-card-title>
            <v-card-text>
                <v-form ref="form" v-model="valid">
                    <v-row>
                        <div class="col-12">
                            <v-file-input accept="image/*" show-size v-model="image" ref="file" placeholder="Click to select image"
                                @change="previewImage()"></v-file-input>
                        </div>
                    </v-row>                
                    <v-row>
                        <div class="col-6 offset-1">
                            <div v-if="showSelectionButtons == true">
                                <div v-for="button in allLineTypes" :key="button.id" v-if="button.removed === 0">
                                    <v-btn v-if="button.lineIndex === null" color="primary" block
                                        @click="addField(button)" class="mb-3">
                                        {{ 'Add ' + button.line_types + " Field" }}
                                    </v-btn>
                                    <v-btn v-else color="primary" block @click="editField(button)" class="mb-3">
                                        {{ 'Edit ' + button.line_types + " Field" }}
                                    </v-btn>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        <v-btn v-if="customImageType === 'none' || customImageType === 'predefined'" color="primary" block @click="addSecondImage('custom')" class="mb-3">
                                            Add custom image
                                        </v-btn>
                                        <v-btn v-if="customImageType === 'custom'" color="primary" block @click="addSecondImage('custom')" class="mb-3">
                                            Edit custom image
                                        </v-btn>
                                        <v-btn v-if="customImageType === 'custom'" color="primary" block @click="removeImage()" class="mb-3">
                                            Remove custom image
                                        </v-btn>
                                    </div>
                                    <div class="col-2 text-center">
                                        <h4 class="pt-2">- OR -</h4>
                                    </div>
                                    <div class="col-5">
                                        <v-btn v-if="customImageType === 'none' || customImageType === 'custom'"  color="primary" block @click="addSecondImage('predefined')" class="mb-3">
                                            Add predefined image 
                                        </v-btn>
                                        <v-btn v-if="customImageType === 'predefined'" color="primary" block @click="addSecondImage('predefined')" class="mb-3">
                                            Edit predefined image
                                        </v-btn>
                                        <v-btn v-if="customImageType === 'predefined'" color="primary" block @click="removeImage()" class="mb-3">
                                            Remove predefined image
                                        </v-btn>
                                    </div>
                                </div>
                            </div>

                            <div class="row" v-if="showSelectionButtons == true">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="mt-5 text-h5 red--text" v-if="!materialSet">Please Select Material Type
                                                (Optional):</span>
                                            <span class="mt-5 text-h5 red--text" v-else>Please Select Material Type: (Required)</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <v-select :items="materialTypes" v-model="selectedMaterialType"
                                                label="Select Material" item-text="type" item-value="id" v-if="!materialSet"></v-select>
                                            <v-select :items="materialTypes" 
                                                v-model="selectedMaterialType"
                                                label="Select Material (Required)" 
                                                item-text="type" 
                                                item-value="id" 
                                                required 
                                                :rules="[v => !!v || 'Item is required']"
                                                v-else></v-select>
                                        </div>
                                    </div>
                                    <v-btn color="error" rounded @click="closeForm()">Cancel</v-btn>
                                    <v-btn color="warning" rounded @click="sendImage()">Save Image</v-btn>
                                </div>
                            </div>
                            <AdditionalImageComponent 
                                v-else-if="showAddImage == true" 
                                v-on:cancel="cancel()"
                                v-on:close="close()" 
                                v-on:shapeDetails="setShapeDetails($event)"
                                v-bind:shape="shape"/>
                            <TextFieldComponent 
                                v-else v-bind:fieldInputs="fieldInputs"
                                v-bind:fontFaces="fontFaces"
                                v-bind:showCurvedText="showCurvedText" 
                                v-bind:selectedOption="selectedOption"
                                v-on:clearLine = "clearLine($event)"
                                v-on:changeTextFont="changeTextFont($event)" 
                                v-on:renderCanvases="renderCanvases()" 
                                v-on:cancel="cancel($event)"
                                v-on:saveField="saveField($event)" 
                                v-on:changeCurvedText="changeCurvedText($event)" />
                        </div>
                        <div class="col-5 px-0" style="height: 450px">
                            <canvas id="canvas" width="650" height="450"
                                style="position: absolute; left: 0; top: 0; z-index: 0;"></canvas>
                            <canvas id="textCanvas" width="650" height="450"
                                style="position: absolute; z-index: 1; left: 0; top: 0" @mousedown="mouseDown"
                                @mouseup="mouseUp" @mousemove="mouseMove"></canvas>                        
                        </div>                    
                    </v-row>
                </v-form>
                <div class="row">
                    <div class="col-5 offset-7 pl-0">
                        <div class="row">
                            <div class="col-3 text-center">
                                Colour of Handles
                                <v-color-picker
                                dot-size="25"
                                swatches-max-height="200"
                                label="Primary Font Colour"
                                v-model='handleColour'
                                mode="hexa"
                                @input="drawPoints()"
                                ></v-color-picker>
                            </div>
                            <div class="col-3 text-center">
                                Text Box Colour
                                <v-color-picker
                                dot-size="25"
                                swatches-max-height="200"
                                label="Primary Font Colour"
                                v-model='textboxColour'
                                mode="hexa"
                                @input="renderCanvases()"
                                ></v-color-picker>
                            </div>
                            <div class="col-3 text-center">
                                Text Colour
                                <v-color-picker
                                dot-size="25"
                                swatches-max-height="200"
                                label="Primary Font Colour"
                                v-model='exampleTextColour'
                                mode="hexa"
                                @input="renderCanvases()"
                                ></v-color-picker>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
import TextFieldComponent from "../includes/TextFieldsComponent.vue";
import AdditionalImageComponent from "../includes/AdditionalImageComponent.vue";
export default {
    props: ["addExampleImageDialog", "selectedProduct", "customImage", "materialTypes", "modalType", "editableCustomImage"],
    components: {
        TextFieldComponent,
        AdditionalImageComponent,
    },
    data: function() {
        return {
            id: null,
            name: null,
            oldCustomImage: null,
            warningMessage: "",
            image: null,
            imgUrl: null,
            baseImgUrl: null,
            selectedMaterialType: null,
            allLineTypes: [],
            fieldInputs: {
                type: "",
                text: "",
                customProductTextId: null,
                textCanvasTop: 230,
                textCanvasLeft: 350,
                fontSize: 80,
                radius: 200,
                centerRotation: -90,
                arc: 90,
                lineType: "straight",
                render: true,
                angle: 0,
                letterCount: 25,
                rectangle: { x: 150, y: 190, width: 400, height: 50 },
                visible: true,
                textScale: 1
            },
            selectedOption: null,
            savedFieldInputs: [],
            selectedFieldInputs: [],
            imageHeight: 0,
            fontFaces: ["Arial", "Brush Script MT", "Courier New", "Nunito", "Smooch", "Titan One"],
            selectedFontFace: "Arial",
            selectedType: "",
            dateFilled: false,
            nameFilled: false,
            lineType: "straight",
            showSelectionButtons: true,
            fieldNumber: 0,
            activeRenderIndex: 0,
            editMode: false,
            blur: 2,
            highLight: "rgba(255,255,0,1)",
            shadow: "rgba(255,255,0,1)",
            fill: "rgba(255, 234, 0,0.8)",
            showCurvedText: false,
            showAddImage: false,
            textCanvas: null,
            textContext: null,
            defaultHandles: [
                {
                    x: 100,
                    y: 100,
                    dragHandle: false,
                },
                {
                    x: 550,
                    y: 100,
                    dragHandle: false,
                },
                {
                    x: 100,
                    y: 350,
                    dragHandle: false,
                },
                {
                    x: 550,
                    y: 350,
                    dragHandle: false,
                },
            ],
            handles: null,
            handleColour: "#FF0000",
            showShape: false,
            defaultShape: {
                x: 300,
                y: 200,
                width: 60,
                height: 100,
                rotation: 2,
                type: "rectangle",
            },
            shape: null,
            customImageType: 'none',
            newLineIndex: 0,
            editingIndex: null,
            textboxColour: '#0C00FF',
            exampleTextColour: '#FF0000',
            currentLineIndex: null,
            materialSet: null,
            valid: true,
        };
    },
    mounted() {
        if (!this.handles) this.handles = this.defaultHandles.map(a => {return {...a}});
    },
    methods: {
        fetchCustomLines() {
            axios.get(`/api/linetypes/${this.selectedProduct.id}`).then((res) => {
                this.allLineTypes = [];
                res.data.forEach(element => {
                    this.allLineTypes.push({
                        id: element.id,
                        line_count: element.line_count,
                        line_text: element.line_types,
                        line_types: element.line_types,
                        product_id: element.product_id,
                        removed: element.removed,
                        lineIndex: null
                    })
                })
                if (this.modalType === 'edit') {
                    this.setCustomLines();
                    this.createShape();
                }
            });
        },
        setCustomLines() {
            let i = 0;
            this.editableCustomImage.custom_text_fields.forEach(el => {
                if (el.removed === 0) {
                    this.$set(this.savedFieldInputs, i, {
                        type: el.type,
                        text: el.type,
                        customProductTextId: el.custom_product_text_id,
                        textCanvasLeft: el.left,
                        textCanvasTop: el.top,
                        fontSize: (el.fontSize * el.rectangleTextScale),
                        angle: el.angle,
                        radius: el.radius,
                        centerRotation: el.centerRotation,
                        arc: el.arc,
                        letterCount: el.letterCount,
                        lineType: el.lineType,
                        rectangle: { x: el.rectangleX, y: el.rectangleY, width: el.rectangleWidth, height: el.rectangleHeight },
                        textScale: el.rectangleTextScale,
                        visible: true,
                        lineIndex: el.line_index,
                    });
                    i++;
                };
                this.allLineTypes.forEach(line => {
                    if (line.line_types === el.type) {
                        line.lineIndex = el.line_index;
                    }
                })
            })
        },

        sendImage() {
            if (this.$refs.form.validate()) {                
                const canvas = document.getElementById("canvas");
                canvas.getContext("2d").drawImage(this.textCanvas, 0, 0);
                const dataURL = canvas.toDataURL("image/png");
                const baseImage = localStorage.getItem("baseImage");
    
                const customImage = new FormData();
                customImage.append("inputs", JSON.stringify(this.savedFieldInputs));
                customImage.append("productId", this.selectedProduct.id);
                customImage.append("file", dataURL);
                if (this.image) {
                    customImage.append("baseImage", baseImage);
                } else {
                    customImage.append("customPath", this.editableCustomImage.custom_image_id);
                    customImage.append("basePath", this.editableCustomImage.image_id);
                }
                if (this.editableCustomImage) {
                    customImage.append("custom_image_id", this.editableCustomImage.id);
                }
                if (this.showShape === true) {
                    customImage.append("shapeDetails", JSON.stringify(this.shape));
                }
                customImage.append("handles", JSON.stringify(this.handles));
                customImage.append("customImageType", this.customImageType);
                if (this.selectedMaterialType) {
                    customImage.append("material_id", this.selectedMaterialType);
                }
                axios
                    .post("/api/sendImage", customImage, {
                        headers: { "Content-Type": "multipart/form-data" },
                    })
                    .then((res) => {
                        this.closeForm();
                        this.$emit("fetchSingleProduct");
                    });
            }
        },
        close() {
            this.showAddImage = false;
            this.showSelectionButtons = true;
            this.renderCanvases();
        },
        storeBlankImage() {
            axios
                .post("/api/sendImage", customImage, {
                    headers: { "Content-Type": "multipart/form-data" },
                })
                .then((res) => {
                    this.closeForm();
                    this.$emit("fetchSingleProduct");
                });
        },
        previewImage() {
          if(this.image)
              this.imgUrl = URL.createObjectURL(this.image);
            this.renderCanvases("straight");
        },
        closeForm() {
            this.clearData();
            this.$emit("closeForm", false);
        },
        addField(button) {
            this.renderCanvases();
            this.selectedOption = button;
            this.showSelectionButtons = false;
            this.selectedType = button.line_types;
            this.editMode = false;
            this.fieldInputs.text = button.line_text;
            this.fieldInputs.type = button.line_types;
            this.fieldInputs.customProductTextId = button.id;
            this.fieldInputs.visible = true;
        },
        findSelectedField(type) {
            return this.savedFieldInputs.find(function (elem) {
                if (elem.type == type) return elem;
            });
        },
        editField(button) {
            this.showSelectionButtons = false;
            this.editMode = true;
            this.editingIndex = button.lineIndex;
            this.selectedType = button.line_types;
            this.selectedOption = button;

            const valObj = this.savedFieldInputs.find(e => e.type === button.line_types);
            valObj.visible = false

            if (valObj) {
                this.fieldInputs.type = valObj.type;
                this.fieldInputs.text = valObj.text;
                this.fieldInputs.customProductTextId = valObj.customProductTextId;
                this.fieldInputs.textCanvasTop = valObj.textCanvasTop;
                this.fieldInputs.textCanvasLeft = valObj.textCanvasLeft;
                this.fieldInputs.fontSize = valObj.fontSize;
                this.fieldInputs.radius = valObj.radius;
                this.fieldInputs.centerRotation = valObj.centerRotation;
                this.fieldInputs.arc = valObj.arc;
                this.fieldInputs.lineType = valObj.lineType;
                this.fieldInputs.render = true;
                this.fieldInputs.angle = valObj.angle;
                this.fieldInputs.letterCount = valObj.letterCount;
                this.fieldInputs.rectangle.x = valObj.rectangle.x;
                this.fieldInputs.rectangle.y = valObj.rectangle.y;
                this.fieldInputs.rectangle.width = valObj.rectangle.width;
                this.fieldInputs.rectangle.height = valObj.rectangle.height;
                this.fieldInputs.visible = true;
                this.fieldInputs.textScale = valObj.textScale
            }

            this.renderCanvases();
        },
        addSecondImage(type) {
            this.showShape = true;
            if (!this.handles) this.handles = this.defaultHandles.map(a => {return {...a}});
            this.customImageType = type;
            this.showSelectionButtons = false;
            this.showAddImage = true;
            this.renderCanvases();
        },
        resetFieldInputs(source) {
            this.showSelectionButtons = true;
            this.showAddImage = false;
            this.editMode = false;
            this.fieldInputs.type = "";
            this.fieldInputs.text = "";
            this.fieldInputs.customProductTextId = null;
            this.fieldInputs.textCanvasTop = 230;
            this.fieldInputs.textCanvasLeft = 350;
            this.fieldInputs.fontSize = 80;
            this.fieldInputs.radius = 200;
            this.fieldInputs.centerRotation = -90;
            this.fieldInputs.arc = 90;
            this.fieldInputs.lineType = "straight";
            this.fieldInputs.render = true;
            this.fieldInputs.angle = 0;
            this.fieldInputs.letterCount = 25;
            this.fieldInputs.rectangle.x = 150;
            this.fieldInputs.rectangle.y = 190;
            this.fieldInputs.rectangle.width = 400;
            this.fieldInputs.rectangle.height = 50;
            this.fieldInputs.visible = true;
            this.fieldInputs.textScale = 1

            this.renderCanvases();
        },
        cancel(currentLineIndex) {
            this.showSelectionButtons = true;
            if (currentLineIndex) {
                const currentInput = this.savedFieldInputs.find(e => e.lineIndex === currentLineIndex);
                currentInput.visible = true;
            }
            this.resetFieldInputs();
            this.renderCanvases();
        },
        clearLine($event) {
            const currentLine = this.savedFieldInputs.findIndex(e => e.lineIndex === $event.lineIndex)
            this.allLineTypes.find(e => e.lineIndex === $event.lineIndex).lineIndex = null;
            
            this.$delete(this.savedFieldInputs, currentLine)
            this.resetFieldInputs();
        },
        clearData() {
            this.savedFieldInputs = [];
            this.allLineTypes = [];
            this.customImageType = 'none';
            this.showShape = false;
            this.oldCustomImage = null;
            this.imgUrl = null;
            this.shape = {...this.defaultShape};
            this.handles = this.defaultHandles.map(a => {return {...a}});

            const canvas = document.getElementById("canvas");
            const context = canvas.getContext("2d");
            context.clearRect(0, 0, canvas.width, canvas.height);

            this.textContext.clearRect(0, 0, canvas.width, canvas.height);
        },
        saveField($event) {
            if (this.editMode === false) {
                $event.lineIndex = this.newLineIndex;                
                this.savedFieldInputs.push(this.fieldInputObject($event));
                this.newLineIndex++;

            } else {
                const index = this.savedFieldInputs.findIndex(e => e.lineIndex === this.fieldInputObject($event).lineIndex)
                this.$set(this.savedFieldInputs, index, this.fieldInputObject($event));
            }
            this.showSelectionButtons = true;
            this.fieldInputs.text = "";
            this.selectedFieldInputs = null;
            this.renderCanvases();
        },
        fieldInputObject(event) {
            return {
                type: this.selectedType,
                text: this.fieldInputs.text,
                customProductTextId: this.fieldInputs.customProductTextId,
                textCanvasLeft: this.fieldInputs.textCanvasLeft,
                textCanvasTop: this.fieldInputs.textCanvasTop,
                fontSize: (this.fieldInputs.fontSize * this.fieldInputs.textScale),
                angle: this.fieldInputs.angle,
                radius: this.fieldInputs.radius,
                centerRotation: this.fieldInputs.centerRotation,
                arc: this.fieldInputs.arc,
                letterCount: this.fieldInputs.letterCount,
                lineType: this.fieldInputs.lineType,
                rectangle: { x: this.fieldInputs.rectangle.x, y: this.fieldInputs.rectangle.y, width: this.fieldInputs.rectangle.width, height: this.fieldInputs.rectangle.height },
                textScale: this.fieldInputs.textScale,
                visible: true,
                lineIndex: event.lineIndex
            }
        },
        drawPoints() {
            if (!this.handles) return;
            const canvas = document.getElementById("canvas");
            const context = canvas.getContext("2d");

            const coordinates = [
                { x: this.handles[0].x, y: this.handles[0].y },
                { x: this.handles[1].x, y: this.handles[1].y },
                { x: this.handles[2].x, y: this.handles[2].y },
                { x: this.handles[3].x, y: this.handles[3].y },
            ];
            coordinates.forEach((element) => {
                context.beginPath();
                context.ellipse(element.x, element.y, 5, 5, 0, 0, 2 * Math.PI);
                context.fillStyle = this.handleColour;
                context.fill();
            });
        },
        skewPerspective(sourceCanvas, destinationCanvas) {
            if (!this.handles) return;
            const destinationContext = destinationCanvas.getContext("2d");
            const perspectiveCanvas = fx.canvas();
            const texture = perspectiveCanvas.texture(sourceCanvas);
            perspectiveCanvas
                .draw(texture)
                .perspective(
                    [100, 100, 550, 100, 100, 350, 550, 350],
                    [
                        this.handles[0].x,
                        this.handles[0].y,
                        this.handles[1].x,
                        this.handles[1].y,
                        this.handles[2].x,
                        this.handles[2].y,
                        this.handles[3].x,
                        this.handles[3].y,
                    ]
                )
                .update();
            destinationContext.drawImage(perspectiveCanvas, 0, 0);
        },
        mouseDown(e) {
            if (!this.handles) return;
            this.handles.every((element) => {
                if (element.x - 5 < e.offsetX && element.x + 5 > e.offsetX && element.y - 5 < e.offsetY && element.y + 5 > e.offsetY) {
                    element.dragHandle = true;
                    return false;
                }
                return true;
            });
        },
        mouseMove(e) {
            if (!this.handles) return;
            this.handles.every((element) => {
                if (element.dragHandle == true) {
                    element.x = e.offsetX;
                    element.y = e.offsetY;
                    this.renderCanvases();
                    return false;
                }
                return true;
            });
        },
        mouseUp() {
            if (!this.handles) return;
            this.handles.forEach((element) => {
                element.dragHandle = false;
            });
        },
        setShapeDetails($event) {
            if (!this.shape) this.shape = {...this.defaultShape};
            this.shape.x = $event.x;
            this.shape.y = $event.y;
            this.shape.width = $event.width;
            this.shape.height = $event.height;
            this.shape.rotation = $event.rotation;
            this.shape.type = $event.type;
            this.renderCanvases();
        },
        createShape() {
            if (!this.shape) this.shape = {...this.defaultShape};
            const shapeCanvas = document.createElement("canvas");
            const shapeContext = shapeCanvas.getContext("2d");

            const canvas = document.getElementById("canvas");

            shapeCanvas.width = canvas.width;
            shapeCanvas.height = canvas.height;

            if (this.showShape == true) {
                shapeContext.fillStyle = "yellow";
                if (this.customImageType == 'custom')
                    shapeContext.fillStyle = "orange";
                shapeContext.beginPath();
                if (this.shape.type == "ellipse") {
                    shapeContext.ellipse(this.shape.x, this.shape.y, this.shape.width, this.shape.height, Math.PI * this.shape.rotation, 0, Math.PI * 2);
                } else {
                    shapeContext.fillRect(this.shape.x, this.shape.y, this.shape.width, this.shape.height);
                }
                shapeContext.fill();
            }
            return shapeCanvas;
        },
        createText(element, textureCanvas) {
            this.textContext.textAlign = "center";
            this.textContext.textBaseline = "middle";

            if (element.visible === false) {
                return;
            }
            if (!textureCanvas) return;
            textureCanvas.getContext("2d").drawImage(this.createShape(), 0, 0);

            this.createEngravedText(textureCanvas, element, this);
        },
        createCurvedEngravedText(textureCanvas, text, x, y, radius, centerRotation, arc) {
            const textLength = text ? text.length : 0;
            const numRadsPerLetter = ((arc / 180.0) * Math.PI) / textLength;

            const startRotation = (centerRotation / 180.0) * Math.PI - numRadsPerLetter * (textLength / 2.0);

            this.textContext.save();

            for (let i = 0; i < textLength; i++) {
                const dX = Math.cos(startRotation + numRadsPerLetter * i) * radius;
                const dY = Math.sin(startRotation + numRadsPerLetter * i) * radius;

                this.textContext.save();
                this.textContext.rotate(i * numRadsPerLetter);
                this.createEngravedText(textureCanvas, text[i], x + dX, y + dY, numRadsPerLetter * i);
                this.textContext.restore();
            }
            this.textContext.restore();
        },
        createEngravedText(textureCanvas, element) { 
            const engravedTextCanvas = document.createElement("canvas");
            const ctx = engravedTextCanvas.getContext("2d");
            engravedTextCanvas.width = canvas.width;
            engravedTextCanvas.height = canvas.height;

            const textScale = this.setTextScale(element);

            ctx.save();

            if(element.text !== "") {
                ctx.beginPath();
                ctx.rect(element.rectangle.x, element.rectangle.y, element.rectangle.width, element.rectangle.height);
                ctx.closePath();
                ctx.stroke();                
                ctx.lineWidth = 4;
                ctx.fillStyle = this.textboxColour;
                ctx.strokeStyle = 'red';
                var fillRect = true;
                if (fillRect) {
                    ctx.fill();
                }
            }
            ctx.font = this.textContext.font;
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            ctx.fillStyle = this.exampleTextColour;
            ctx.arc(100, 75, 50, 0, 2 * Math.PI);
            ctx.scale(textScale, textScale);
            ctx.fillText(element.text, ((element.rectangle.x + (element.rectangle.width / 2)) / textScale), ((element.rectangle.y + (element.rectangle.height / 2)) / textScale));
            ctx.restore();
            textureCanvas.getContext("2d").drawImage(engravedTextCanvas, 0, 0);
        },
        renderCanvases() {
            const imageObj = new Image();
            imageObj.crossOrigin="anonymous";
            let t = this;
            const canvas = document.getElementById("canvas");
            const context = canvas.getContext("2d");

            this.textCanvas = document.getElementById("textCanvas");
            this.textContext = this.textCanvas.getContext("2d");

            imageObj.onload = function() {
                t.initialiseImage(context, imageObj, canvas, document.getElementById("textCanvas"));
                const dataURL = canvas.toDataURL("image/png");
                localStorage.setItem("baseImage", dataURL);
                t.textContext.clearRect(0, 0, canvas.width, canvas.height);

                const textureCanvas = document.createElement("canvas");

                textureCanvas.width = canvas.width;
                textureCanvas.height = canvas.height;
                t.savedFieldInputs.forEach((element) => {
                    
                    t.textContext.font = element.fontSize  + "px " + t.selectedFontFace;
                    t.createText(element, textureCanvas);
                });

                t.textContext.font = t.fieldInputs.fontSize  + "px " + t.selectedFontFace;
                t.createText(t.fieldInputs, textureCanvas);

                t.skewPerspective(textureCanvas, t.textCanvas);

                if (t.showSelectionButtons == false) {
                    t.drawPoints();
                }
            };
            imageObj.src = this.imgUrl;
        },
        setTextScale(input) {
            const textWidth = this.textContext.measureText(input.text).width;

            let textScale = 1;

            if (textWidth > input.rectangle.width) {
                textScale = input.rectangle.width / textWidth
            } else {
                textScale = 1;
            }

            return textScale;
        },
        initialiseImage(context, imageObj, canvas, textCanvas) {
            const scale = canvas.width / imageObj.width;
            canvas.height = scale * imageObj.height ;
            textCanvas.height = scale * imageObj.height;
            context.drawImage(imageObj, 0, 0, imageObj.width, imageObj.height, 0, 0, canvas.width, scale * imageObj.height);
        },
        changeCurvedText($event) {
            this.showCurvedText = $event;
        },
        setCurrentLineIndex($event) {
            this.currentLineIndex = $event;
        },
        changeTextFont($event) {
            this.selectedFontFace = $event;
            this.renderCanvases();
        },
        removeImage() {
            this.customImageType = 'none';
            this.showShape = false;
            this.showSelectionButtons = true; // setting this true hides the draggable handles
            this.renderCanvases();
        },
    },
    watch: {
        selectedProduct: function(propVal) {
            this.id = propVal.id;
            this.name = propVal.name;
            this.materialSet = propVal.custom_images.find(e => e.material);
        },
        customImage: function(propVal) {
            this.oldCustomImage = propVal;
        },
        addExampleImageDialog: function() {
            this.$nextTick(() => {
                this.savedFieldInputs = [];
                this.fetchCustomLines();
                this.shape = {...this.defaultShape};
                this.renderCanvases();
            });
        },
        showCurvedText: function(propVal) {
            if (propVal == true) {
                this.fieldInputs.lineType = "curved";
                this.fieldInputs.top = 1100;
            } else {
                this.fieldInputs.lineType = "straight";
                this.fieldInputs.top = 160;
            }
        },
        editableCustomImage: function (propVal) {
            this.$nextTick(() => {
                if (this.modalType === 'edit') {
                    this.oldCustomImage = propVal;
                    this.customImageType = propVal.layout
                    this.imgUrl = propVal.image.imageurl;
                    if (!this.handles) this.handles = this.defaultHandles.map(a => {return {...a}});
                    this.handles[0].x = propVal.custom_image_perspective_details.ax;
                    this.handles[0].y = propVal.custom_image_perspective_details.ay;
                    this.handles[1].x = propVal.custom_image_perspective_details.bx;
                    this.handles[1].y = propVal.custom_image_perspective_details.by;
                    this.handles[2].x = propVal.custom_image_perspective_details.cx;
                    this.handles[2].y = propVal.custom_image_perspective_details.cy;
                    this.handles[3].x = propVal.custom_image_perspective_details.dx;
                    this.handles[3].y = propVal.custom_image_perspective_details.dy;
                    this.selectedMaterialType = propVal.material_id;
                    this.showShape = false;
                    this.shape = {...this.defaultShape};
                    if (this.customImageType !== 'none') {
                        this.showShape = true;
                        this.shape.x = propVal.additional_image.left;
                        this.shape.y = propVal.additional_image.top;
                        this.shape.width = propVal.additional_image.width;
                        this.shape.height = propVal.additional_image.height;
                        this.shape.rotation = propVal.additional_image.rotation;
                    }
                    this.renderCanvases();
                    this.setCustomLines();
                }
                
            });
        },
    },
};
</script>
