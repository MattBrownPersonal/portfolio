<template>
    <v-dialog v-model="additionalImageDialogue" persistent max-width="1111px">
        <v-card class="py-0 py-md-5" style="height: 830px; overflow-x: hidden;">
            <v-card-text class="pt-0 pb-0" v-if="layoutType === 'custom'">
                <div class="row mt-5 mt-md-0">
                    <div class="col-1 px-0 py-0 offset-11 text-center">
                        <v-icon @click="closeForm()">mdi-close</v-icon>
                    </div>
                </div>
                <div class="row mt-0">
                    <div class="col-12">
                        <h6 class="mb-0 mb-md-0 mx-4 primary-colour">Upload Image</h6>
                        <v-divider></v-divider>
                    </div>
                </div>
                <div class="row mt-0 pl-5">
                    <div class="col-12 col-md-8">
                        <span class="h7 primary-colour" v-if="baseImageUrl">Step 2</span>
                            <p>To overlay a specific part of your chosen image on the memorial, click and drag the red box to
                            the desired location. Or, to zoom in and out, click and drag the blue box.</p>
                            <div :style="{ 'min-height': canvaSize.height + 'px !important', width: 'auto', overflow:'hidden'}">
                              <canvas 
                                  id="imageCanvas" 
                                  class="canvasSize" 
                                  :height="canvaSize.height"
                                  :width="canvaSize.width"
                                  style="position: absolute; 
                                  z-index: 0;"
                              ></canvas>
                              <canvas 
                                  id="shapeCanvas" 
                                  class="canvasSize" 
                                  :height="canvaSize.height"
                                  :width="canvaSize.width"
                                  style="position: absolute; 
                                  z-index: 1;" 
                                  @mousedown="mouseDown"                            
                                  @mouseup="mouseUp" 
                                  @mousemove="mouseMove"
                              ></canvas>
                            </div>
                          </div>
                    <div class="col-12 col-md-4 pl-3 pt-0" style="z-index:2">
                        <span class="h7 primary-colour" v-if="baseImageUrl">Step 3</span>
                            <p>Review the final image that will be used on the memorial option and click the button below if
                        you are happy with the image.</p>
                            <div class="row">
                                <div class="col-12 outputCanvasContainer text-center">
                                    <canvas 
                                    id="outputCanvas" 
                                    class="outputCanvas" 
                                    :width=outputCanvasSize.width 
                                    :height=outputCanvasSize.height
                                    v-bind:style="{ offsetWidth: outputCanvasSize.width, offsetHeight: outputCanvasSize.height }"
                                    ></canvas>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-12">
                                <v-btn 
                                    block
                                    class="btn"
                                    @click="saveImage()"
                                    >Save Image</v-btn>
                            </div>
                            <div class="col-12">
                                <v-btn 
                                    rounded 
                                    :color="styles.primary_colour" 
                                    :style="{ color: styles.primary_colour }"
                                    outlined
                                    class="btn-outline"
                                    block                                 
                                    @click="closeForm()"
                                    >Cancel</v-btn>
                            </div>
                        </div>
                    </div>
                </div>
            </v-card-text>
            <v-card-text v-else>
                TEST
            </v-card-text>
        </v-card>
    </v-dialog>
</template>
<script>
import axios from 'axios';
import { bus } from '../../../../app'
export default {
    props: ['additionalImageDialogue', 'layoutType'],
    data () {
        return {
            canvas: null,
            context: null,
            canvaSize: {width: 659, height: 450},
            shapeCanvas: null,
            shapeContext: null,
            outputCanvas: null,
            outputContext: null,
            img: null,
            uploadedImage: [],
            baseImageUrl: null,
            shape: {
                top: null,
                left: null,
                width: null,
                height: null,
                type: null,
                rotation: null,
                oldTop: null,
                oldLeft: null,
                startX: null,
                startY: null
            },
            dragok: false,
            scaleOk: false,
            shapeScale: null,
            intervalHandle: null
        }
    },
    methods: {
        closeForm() {
            this.$emit('closeForm', false)
        },
        setBaseImage() {
            const img = new Image();
            img.crossOrigin="anonymous";
            const t = this;
            img.onload = function () {
                t.img = img;
                t.drawInitialShape()
            }
            img.src = this.baseImageUrl;
        },
        setCoordinates(e, input) {
            let x = 1;
            let y = 1;
            let baseHandleValue = 10;

            if (input === 'touch') {
                const rect = e.target.getBoundingClientRect();

                const viewportX = e.touches[0].clientX - rect.x;
                const viewportY = e.touches[0].clientY - rect.y;

                x = (this.shapeCanvas.width / this.shapeCanvas.clientWidth) * viewportX;
                y = (this.shapeCanvas.height / this.shapeCanvas.clientHeight) * viewportY;

                baseHandleValue = 50;

            } else {
                const viewportX = e.offsetX;
                const viewportY = e.offsetY;

                x = (this.shapeCanvas.width / this.shapeCanvas.clientWidth) * viewportX;
                y = (this.shapeCanvas.height / this.shapeCanvas.clientHeight) * viewportY;
            }

            return { x, y, baseHandleValue };
        },
        drawShape() {
            let height = this.shape.width * this.shapeScale;
            this.shapeContext.beginPath();
            if(this.shape.type === 'ellipse') {
                this.shapeContext.ellipse(this.shape.left, this.shape.top, this.shape.width, height, Math.PI, 0, 2 * Math.PI);
            } else {
                this.shapeContext.rect(this.shape.left, this.shape.top, this.shape.width, height);
            }
            this.shapeContext.strokeStyle = 'red';
            this.shapeContext.lineWidth = 5;
            this.shapeContext.stroke();
        },
        drawHandle() {
            let handleWidth = 10;
            let handleHeight = 10;

            if (this.$vuetify.breakpoint.name === 'xs') {
                handleWidth = 50;
                handleHeight = 50;
            } 
            this.shapeContext.fillStyle = 'blue';
            if(this.shape.type === 'ellipse') {
                this.shapeContext.fillRect(this.shape.left + this.shape.width+10, this.shape.top - 5, handleWidth, handleHeight);
            } else {
                this.shapeContext.fillRect(this.shape.left + this.shape.width+10, this.shape.top + (this.shape.height / 2) - 5, handleWidth, handleHeight);
            }
        },
        drawInitialShape() {
            const scale = this.canvas.width / this.img.width; // TODO:: does this correctly handle images that are taller than they are wide? (see PredefinedImageComponent->UpdateOutputCanvas)

            this.shapeContext.save();
            this.shapeContext.clearRect(0, 0, this.shapeCanvas.width, this.shapeCanvas.height);
            this.shapeContext.globalAlpha = 0.5;
            this.shapeContext.drawImage(this.img, 0, 0, this.canvas.width, scale * this.img.height);
            this.drawShape();
            this.shapeContext.clip();
            this.shapeContext.globalAlpha = 1;
            this.shapeContext.drawImage(this.img, 0, 0, this.canvas.width, scale * this.img.height);
            this.shapeContext.restore();
            this.updateOutputCanvas();
            this.drawHandle();
        },
        updateOutputCanvas() {
            this.outputContext.clearRect(0, 0, this.outputCanvasSize.width, this.outputCanvasSize.height);
            let height = this.shape.width * this.shapeScale;

            this.outputContext.save()
            if(this.shape.type === 'ellipse') {
                this.outputContext.beginPath();
                this.outputContext.ellipse(this.outputCanvas.width/2, this.outputCanvas.height/2, this.outputCanvas.width/2-5, this.outputCanvas.height/2-5, Math.PI, 0, 2 * Math.PI);
                this.outputContext.clip();
                this.outputContext.drawImage(this.shapeCanvas, (this.shape.left - this.shape.width), (this.shape.top -  height), (this.shape.width * 2), (height * 2), 0, 0, this.outputCanvasSize.width, this.outputCanvasSize.height);
            } else {
                this.outputContext.drawImage(this.shapeCanvas, this.shape.left, this.shape.top, this.shape.width, height, 0, 0, this.outputCanvasSize.width, this.outputCanvasSize.height);
            }
            this.outputContext.restore();
        },
        scaleEllipse(e, input) {
            const { x } = this.setCoordinates(e, input);

            let delta = Math.abs(this.shape.startX - x);
            const offset = 1.0;

            let newWidth = '';

            if (delta > this.shape.oldWidth * 3) {
                delta = this.shape.oldWidth * 3;
            }
            if( x > this.shape.startX  ) {   
                newWidth = this.shape.oldWidth + (delta * offset);
            } else if( x < this.shape.startX ){
                newWidth = this.shape.oldWidth - (delta * offset);
            }
            if(newWidth < (this.shape.oldWidth / 2)) {
                newWidth = (this.shape.oldWidth / 2);
                delta = (this.shape.oldWidth / (offset*2));
            }
            this.shape.width = newWidth;
            this.shape.height = this.shape.width * this.shapeScale;
        },
        scaleRect(e, input) {
            const { x } = this.setCoordinates(e, input);

            const offset = 2.0;
            let delta = Math.abs(this.shape.startX - x)/2; 
            
            let newWidth = '';
            
            if( x > this.shape.startX  ) {
                if (delta > this.shape.oldWidth * 3) {
                    delta = this.shape.oldWidth * 3;
                }
                newWidth = this.shape.oldWidth + (delta * offset);
                if(newWidth < (this.shape.oldWidth / 2)) {
                    newWidth = (this.shape.oldWidth / 2);
                    delta = (this.shape.oldWidth / (offset*2));
                }
                this.shape.width = newWidth;
                this.shape.height = this.shape.width * this.shapeScale;
                this.shape.left = this.shape.oldLeft - delta;
                this.shape.top = this.shape.oldTop - delta;
            } else if( x < this.shape.startX ){
                newWidth = this.shape.oldWidth - (delta * offset);

                if(newWidth < (this.shape.oldWidth / 2)) {
                    newWidth = (this.shape.oldWidth / 2);
                    delta = (this.shape.oldWidth / (offset*2));
                }
                this.shape.width = newWidth;
                this.shape.height = this.shape.width * this.shapeScale;
                this.shape.left = this.shape.oldLeft + delta;
                this.shape.top = this.shape.oldTop + delta;
            }
        },
        mouseMove(e, input) {
            e.preventDefault();
            const { x, y } = this.setCoordinates(e, input);

            if (this.dragok === true) {
                if(this.shape.type === 'ellipse') {
                    this.shape.left = x;
                    this.shape.top = y;
                } else {
                    this.shape.left = x - (this.shape.width / 2);
                    this.shape.top = y - (this.shape.height / 2);

                }
            } else if (this.scaleOk === true) {
                if (this.shape.type !== 'ellipse') {
                    this.scaleRect(e, input);
                } else {
                    this.scaleEllipse(e, input);
                }
            }
            this.drawInitialShape();
        },
        mouseDown(e, input) {
            const { x, y, baseHandleValue } = this.setCoordinates(e, input);

            let handleX1 = '';
            let handleX2 = '';
            let handleY1 = '';
            let handleY2 = '';

            if (this.shape.type === 'ellipse') {
                handleX1 = this.shape.left + this.shape.width + 10;
                handleX2 = this.shape.left + this.shape.width + (baseHandleValue * 2);
                handleY1 = this.shape.top - (baseHandleValue / 2);
                handleY2 = this.shape.top + (baseHandleValue / 2);
            } else {
                handleX1 = this.shape.left + this.shape.width + 10;
                handleX2 = this.shape.left + this.shape.width + (baseHandleValue * 2);
                handleY1 = this.shape.top + (this.shape.height / 2) - (baseHandleValue / 2);
                handleY2 = this.shape.top + (this.shape.height / 2) + (baseHandleValue / 2);
            }

            if ((x > handleX1) && (x < handleX2) && (y > handleY1) && (y < handleY2)) {
                this.shape.startX = x;
                this.shape.startY = y;

                this.shape.oldLeft = this.shape.left;
                this.shape.oldTop = this.shape.top;

                this.shape.oldWidth = this.shape.width;
                this.shape.oldHeight = this.shape.height;

                this.scaleOk = true;
            } else {
                if (this.shape.type === 'ellipse') {
                    this.shape.left = x;
                    this.shape.top = y;
                } else {
                    this.shape.left = x - (this.shape.width / 2);
                    this.shape.top = y - (this.shape.height / 2);
                }
                this.dragok = true;
            }
            this.drawInitialShape();
        },
        mouseUp(){
            this.mouseOut();
        },
        mouseOut(){
            this.dragok = false;
            this.scaleOk = false;
        },
        saveImage() {
            let img = this.outputCanvas.toDataURL("image/png");
            localStorage.setItem('croppedImage', img);
            this.closeForm();
            bus.$emit('layoutType', 'custom');
        },
        customCanvasSize() {
            switch (this.$vuetify.breakpoint.name) {
                case "xs":
                    this.canvaSize.width = (window.innerWidth - 120);
                    this.canvaSize.height = (window.innerWidth - 120) / (659/450);
                    break;
                default:
                    this.canvaSize.width =  659;
                    this.canvaSize.height = 450;
                    break;
            }
        },
    },
    watch: {
        additionalImageDialogue: {
            handler: function () {
                if (localStorage.getItem('shape')) {
                    this.shape.top = JSON.parse(localStorage.getItem('shape'))[0].top;
                    this.shape.left = JSON.parse(localStorage.getItem('shape'))[0].left;
                    this.shape.width = JSON.parse(localStorage.getItem('shape'))[0].width;
                    this.shape.height = JSON.parse(localStorage.getItem('shape'))[0].height;
                    this.shape.type = JSON.parse(localStorage.getItem('shape'))[0].type;
                    this.shape.rotation = JSON.parse(localStorage.getItem('shape'))[0].rotation;
                    this.shapeScale = localStorage.getItem('shapeScale');
                }
                this.$nextTick(() => {
                    this.canvas = document.getElementById("imageCanvas");
                    this.context = this.canvas.getContext('2d');                    
                    
                    this.shapeCanvas = document.getElementById("shapeCanvas");
                    this.shapeContext = shapeCanvas.getContext('2d');
                    
                    this.shapeCanvas.addEventListener('touchstart', (e) => { this.mouseDown(e, 'touch') });
                    this.shapeCanvas.addEventListener('touchmove', (e) => { this.mouseMove(e, 'touch') });
                    this.shapeCanvas.addEventListener('touchend', (e) => { this.mouseUp() });
                    this.shapeCanvas.addEventListener('touchcancel', (e) => { this.mouseOut() });

                    this.outputCanvas = document.getElementById('outputCanvas');
                    this.outputContext = this.outputCanvas.getContext('2d');
                });
            }
        }
    },
    created() {
        bus.$on('uploadedImage', (data) => {
            this.baseImageUrl = data;
            this.setBaseImage();
        });
        $(window).on("resize",() => {
            this.customCanvasSize();
            if(this.canvas){
                this.setBaseImage();
                this.drawInitialShape();
            }
        });
        $(window).resize();
    },
    destroyed() {
        bus.$off('uploadedImage');
        $(window).off('resize');
    },
    computed: { 
        styles() {
            return this.$store.state.styles;
        },
        outputCanvasSize() {
            switch (this.$vuetify.breakpoint.name) {
                case "xs":
                    let heightSm = 300 * this.shapeScale;
                    let widthSm = 300;
                    let scaleSm = 1;
                    const maxHeight = 280;

                    if (heightSm > maxHeight) {
                        scaleSm = maxHeight / (300 * this.shapeScale)
                        heightSm = maxHeight;
                        widthSm = heightSm * scaleSm;
                    }
                    return { "width": widthSm, "height": heightSm };
                default:
                    let heightMd = 180 * this.shapeScale;
                    let widthMd = 200;
                    let scaleMd = 1;
                    const maxHeightMd = 280;

                    if (heightMd > maxHeightMd) {
                        scaleMd = maxHeightMd / (180 * this.shapeScale)
                        heightMd = maxHeightMd;
                        widthMd = heightMd * scaleMd;
                    }
                    return { "width": widthMd, "height": heightMd };
            }
        }
    },
}
</script>
<style scoped>
.canvasStyle {
    margin-left: auto;
    margin-right: auto;
    display: block;
}

.outputCanvasContainer {
    height: 300px !important;
}

@media (max-width: 500px) {
    .outputCanvasContainer {
        height: 300px !important;
    }

}
</style>