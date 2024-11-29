<template>
    <div class="col-12 col-lg-7 pb-sm-0 pt-0 px-0 text-center custom-image-component">
        <div class="elevation-0 custom-image-sticky" v-if="imageDetails.length">
            <div class="px-0 py-0 px-md-3 py-md-3">
                <canvas id="customerCanvas" class="carouselImage"></canvas>
                <div class="row carousel-buttons">
                    <div class="col-2">
                        <v-btn @click="changeSlide(-1);"
                        :style="{color: styles.primary_colour}"
                        v-if="showPreviousButton"
                        outlined
                        class="btn-outline"
                        aria-label="Previous Image"
                        >Previous</v-btn>
                    </div>
                    <div class="col-4 offset-1 d-none d-md-block" style="padding-left: 35px">
                        <v-btn @click="removeAdditionalImage();"
                        :style="{color: styles.primary_colour}"
                        v-if="showClearButton && !showPayment"
                        outlined
                        class="btn-outline"
                        >Clear {{slide.layout === 'custom' ? 'uploaded' : 'selected'}} image</v-btn>
                    </div>
                    <div class="col-4 offset-6 offset-md-1 text-right">
                        <v-btn @click="changeSlide(+1);"
                        :style="{color: styles.primary_colour}"
                        v-if="showNextButton"
                        aria-label="Next Image"
                        outlined
                        class="btn-outline"
                        >Next</v-btn>
                    </div>
                </div>
                <div class="row mb-5 d-block d-md-none">
                    <v-btn @click="removeAdditionalImage();"
                    :style="{ color: styles.primary_colour }"
                    v-if="showClearButton && !showPayment"
                    outlined
                    class="btn-outline"
                    >Clear {{ slide.layout === 'custom' ? 'uploaded' : 'selected' }} image</v-btn>
                </div>
            </div>
        </div>
        <div v-else class="mt-5 text-left">
            <div v-if="product" class="row">
                <div class="col-md-6 col-12">
                    <h3>Your chosen customisations</h3>
                    <div class="row my-5">
                        <div class="col-6 col-md-3">
                            <div v-for="attribute_type in product.attribute_types" :key="attribute_type.id">
                                <h5> {{attribute_type.name}} </h5>
                            </div>
                                <h5>Price:</h5>
                        </div>
                        <div class="col-6 col-md-6">
                            <div v-for="[attribKey, productSpec] in chosenProductSpecs" :key="attribKey">
                                <h5>{{ productSpec.name }}</h5>
                            </div>
                            <h5>£{{ totalPrice }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { relativeTimeThreshold } from 'moment';
import { bus } from '../../../../app'
const filter = require('leo-profanity');

export default ({
    props: ['customImageDetails', 'product', 'showPayment', 'chosenProductSpecs', 'totalPrice', 'productImages', 'fontColour', 'customTextDetails', 'productTextFields', 'shape', 'perspectiveDetails', 'selectedMaterial', 'layoutType'],
    data: function() {
        return {
            slideIndex:0,
            slide: null,
            imageDetails:  [],
            textDetails: [],
            selectedFieldInputs: [],
            imageHeight: 0,
            selectedFontFace: 'Titan One',
            selectedType: '',
            dateFilled: false,
            nameFilled: false,
            lineType: 'straight',
            showSelectionButtons: true,
            fieldNumber: 0,
            activeRenderIndex: 0,
            editMode: false,
            blur: 1,
            highLight: "rgba(255,255,0,1)",
            shadow: "rgba(255,255,0,1)",
            customSavedLines: null,
            customText: [],
            shapeDetails: null,
            textCanvas: null,
            textContext: null,
            materialId: null,
            handles: [
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
            selectedMaterialId: null,
            showPreviousButton: false,
            showNextButton: true,
            customImages: null,
            unfilteredImageDetails: null,
            debugFeatureImageBorder: false,    // when true; draw feature image border
            debugWorkFlow: false,   // when true; log workflow messages to console
            maxFontSize: -1,
            showClearButton: false,
            materialFilter: false
        }
    },
    methods: {
        changeSlide(direction) {
            if (direction) this.slideIndex += direction;
            this.slide = this.imageDetails[this.slideIndex];
            this.showPreviousButton = (this.slideIndex > 0);
            this.showNextButton = (this.slideIndex < (this.imageDetails.length -1));
            this.debugWorkFlow && console.log(`:changeSlide:slideIndex=${this.slideIndex}, showPreviousButton=${this.showPreviousButton}, showNextButton=${this.showNextButton}`);
            this.fadeInButtons();
            this.drawSlide(this.slide);
            bus.$emit('slideChange', this.slide);
        },
        skewPerspective(sourceCanvas, destinationCanvas, index) {
            // Currently all perspective functionality is disabled
            let destinationContext = destinationCanvas.getContext("2d");
            let perspectiveCanvas = fx.canvas();
            let texture = perspectiveCanvas.texture(sourceCanvas);
            let skew = this.perspectiveDetails[index];
            // TODO:: document perspective values
            perspectiveCanvas.draw(texture).perspective([100,100,550,100,100,350,550,350], [skew.ax,skew.ay,skew.bx,skew.by,skew.cx,skew.cy,skew.dx,skew.dy]).update();
            destinationContext.drawImage(perspectiveCanvas,0,0);
        },
        setTextOnImage(slide) {
            if ((!slide) || (!slide.GFXCompositeManager)) return;
            const slideIndex = this.unfilteredImageDetails.findIndex((el) => { return el.id === slide.id});
            const cm = slide.GFXCompositeManager;
            const buffer = cm.getOffscreenBuffer('text');
            const canvas = buffer.canvas;
            const size = this.getCustomerCanvasSize(slide.id);
            canvas.width = size.width;
            canvas.height = size.height;
            const context = buffer.context;
            context.clearRect(0, 0, canvas.width, canvas.height);
            context.fillStyle = this.fontColour.colour;
            context.textAlign = "center";
            context.textBaseline = "middle";
            const textData = this.textDetails[slideIndex];
            if (!textData) return;
            buffer.dirty = true;
            context.scale(cm.scale, cm.scale);
            if (!textData) return ;
            this.debugWorkFlow && console.log(':setTextOnImage: NOTE: innerTextArray & customtext.',textData.innerTextArray,this.customText);  
            textData.innerTextArray.forEach(element  => {
                let text = this.customText.find(x => x.type === element.type);
                if (!text || text.text.length<1) return;
                this.debugWorkFlow && console.log(':setTextOnImage: render the text', text.text);
                const fontSize = element.fontSize;                
                context.font = fontSize + 'px ' + this.selectedFontFace;
                const textScale = this.setTextScale(text.text, element, context);
                const calculatedFontSize = Math.floor(fontSize * textScale);
                context.font = calculatedFontSize + 'px ' + this.selectedFontFace;
                context.fillText(text.text, (element.left + (element.rectangleWidth / 2)) , (element.top + (element.rectangleHeight / 2)) );
            });
            /*
                TODO::implement the perspective text
                t.skewPerspective(textureCanvas, canvas, index);
            */
            this.debugWorkFlow && console.log(':setTextOnImage: NOTE: Perspective text functionality is currently disabled.');
            buffer.dirty = false;
            this.drawCompositedImageToMain(slide, 'setTextOnImage');
        },
        initialiseImage(context, imageObj, canvas) {
            let scale = canvas.width / imageObj.width;
            context.drawImage(imageObj, 0, 0, imageObj.width, imageObj.height, 0, 0, canvas.width, scale * imageObj.height);
        },
        clearBufferCanvas(cm, bufferName) {
            const buffer = cm.getOffscreenBuffer(bufferName);
            const canvas = buffer.canvas;
            const context = buffer.context;
            if (cm.width !== 0 && cm.height !== 0) {
                canvas.width = cm.width;
                canvas.height = cm.height+30;   // TODO:: investigate this arbitry value: 30
            }
            context.clearRect(0, 0, canvas.width, canvas.height);
            buffer.dirty = false;
        },
        insertShape(slide, shape, featureType, force) {
            if (!shape) return; // product has an additional image defined but this slide does not.
            const cm = slide.GFXCompositeManager;
            const buffer = cm.getOffscreenBuffer('featureImage');
            const canvas = buffer.canvas;
            const context = buffer.context;
            if (!force && !buffer.dirty) {
                this.debugWorkFlow && console.log(':insertShape: featured image does not need to be reloaded');
                return;
            }
            if (cm.width !== 0 && cm.height !== 0) {
                canvas.width = cm.width;
                canvas.height = cm.height+30;
            }
            let self = this;
            let img = new Image();
            img.crossOrigin="anonymous";
            img.onload = function () {
                if (!buffer.dirty) {
                    self.debugWorkFlow && console.log(':insertShape: src loaded but buffer no longer dirty');
                    return;
                }
                self.debugWorkFlow && console.log(':insertShape: canvas and shape', canvas.width, canvas.height, cm.scale, shape,featureType,force);
                context.scale(cm.scale, cm.scale);
                if(shape.type == 'ellipse') {
                    context.ellipse(shape.left, shape.top, shape.width, shape.height, Math.PI * shape.rotation, 0, Math.PI * 2);
                    context.clip();
                    self.debugWorkFlow && console.log(':insertShape: ellipse target data', target,img);
                    context.drawImage(img, shape.left - shape.width, shape.top - shape.height, shape.width*2, shape.height*2);
                    self.debugFeatureImageBorder && context.stroke();
                } else {
                    context.rect(shape.left, shape.top, shape.width, shape.height);
                    context.clip();
                    const target = self.centerImageOnTarget(shape, img);
                    self.debugWorkFlow && console.log(':insertShape: non-ellipse target data', target,img);
                    context.drawImage(img, target.x, target.y, target.width, target.height);
                    self.debugFeatureImageBorder && context.stroke();
                }
                /* Perspective code is disabled
                self.skewPerspective(xtureCanvas, canvas, index);
                */
                self.debugWorkFlow && console.log(':insertShape: NOTE:', 'skew image not implemented yet');
                buffer.dirty = false;
                self.debugWorkFlow && console.log(`:insertShape: ${featureType} Image DateTime Stamp Loaded`, localStorage.getItem(featureType + 'ImageStamp'));
                self.drawCompositedImageToMain(slide, 'insertShape');
            }
            img.onerror = function (e) {
                console.warn('Feature image failed to load', e, e.detail);
                buffer.dirty = false;
            }
            this.debugWorkFlow && console.log(`:insertShape: ${featureType} Image DateTime Stamp Loading`, localStorage.getItem(featureType + 'ImageStamp'));
            const savedImgData = localStorage.getItem(featureType + 'Image');
            context.clearRect(0, 0, canvas.width, canvas.height);
            if (savedImgData) {
                buffer.dirty = true;
                img.src = savedImgData +  (savedImgData.startsWith('http') ? '?_=' + (new Date().getTime()) : '');
            } else {
                this.drawCompositedImageToMain(slide, 'insertShape::clear');
            }
        },
        setTextScale(text, input, context) {
            let textScale = 1;
            if (!context) return textScale;
            const textWidth = context.measureText(text).width;
            if (textWidth > input.rectangleWidth) {
                textScale = input.rectangleWidth / textWidth;
            }
            return textScale;
        },
        saveImage(canvas) {
            let dataURL = canvas.toDataURL("image/png");
            this.$emit('saveImage', dataURL);
        },
        addToCart(){
            this.$emit('addToCart');
        },
        createImageArray() {
            this.initSlides();
            this.filterCarousel();
            this.changeSlide(0);
        },
        initSlides() {
            this.unfilteredImageDetails.forEach((element) => {
                element['additionalImage'] = this.hasAdditionalImage(element);
                element['GFXCompositeManager'] = this.createGFXCompositeManager();
            });
        },
        drawSlide(slide) {
            this.$nextTick(() => {
                if (this.imageDetails.length > 0) {
                    const cm = slide.GFXCompositeManager;
                    if (cm.width === 0 || cm.height === 0) {
                        this.setLoading(true, slide.id);
                        this.setBackgroundImage(slide);
                    } else {
                        this.debugWorkFlow && console.log(':drawSlide: ready to show content');
                        this.setTextOnImage(slide);
                        this.setFeatureOnImage(slide, true);
                        this.drawCompositedImageToMain(slide, 'drawSlide');
                    }
                }
            });
        },
        removeAdditionalImage() {
            const slide = this.slide;
            const layoutType = slide.layout;
            if (layoutType === 'custom') {
                localStorage.removeItem('croppedImage');
            } else {
                localStorage.removeItem('predefinedImage');
            }
            this.unfilteredImageDetails.forEach((item) => {
                if (layoutType === item.layout) {
                    item.additionalImage = false;
                    this.clearBufferCanvas(slide.GFXCompositeManager, 'featureImage');
                    this.drawCompositedImageToMain(slide, 'removeAdditionalImage');
                }
            });
            this.filterCarousel();
            this.setClearButton();
            bus.$emit('imagePrice', 0);
            this.drawCompositedImageToMain(slide, 'removeAdditionalImage');
        },
        createGFXCompositeManager() {
            let newCM = new GFXCompositeManager();
            newCM.addOffscreenBuffer('background');
            newCM.addOffscreenBuffer('featureImage');
            newCM.addOffscreenBuffer('text');
            return newCM;
        },
        drawCompositedImageToMain(slide, from) {
            if (!slide) return;
            const cm = slide.GFXCompositeManager;
            if (cm.isDirty()) {
                this.debugWorkFlow && console.log(':drawCompositedImageToMain: A call was made to drawCompositedImageToMain, but at least one buffer is awaiting cleaning', from);
                const dirtyBuffers = cm.buffers.filter((v) => v.dirty);
                this.debugWorkFlow && console.log(':drawCompositedImageToMain: dirty buffers', dirtyBuffers);
                return;
            }
            const canvas = document.getElementById('customerCanvas');
            if (canvas) {
                const context = canvas.getContext("2d");
                context.clearRect(0, 0, canvas.width, canvas.height);
                this.drawCompositedImage(context, slide, from + ':+: drawCompositedImageToMain');
                this.saveImage(canvas);
                this.addToCart();
            } else {
                this.debugWorkFlow && console.log(':drawCompositedImageToMain: customerCanvas' + ' is not currently available');
            }
        },
        drawCompositedImage(context, slide, from) {
            const cm = slide.GFXCompositeManager;
            const layers = ['background', 'featureImage', 'text'];
            this.debugWorkFlow && console.log(':drawCompositedImage: from', from,slide);
            cm.compositeBuffersByName(layers, context);
        },
        setBackgroundImage(slide) {
            const slideIndex = this.unfilteredImageDetails.findIndex((el) => { return el.id === slide.id})
            bus.$emit('updateCustomTextIndex', slideIndex);
            const cm = slide.GFXCompositeManager;
            const buffer = cm.getOffscreenBuffer('background');
            const canvas = buffer.canvas;
            const size = this.getCustomerCanvasSize(slide.id);
            canvas.width = size.width;
            canvas.height = size.height;
            let imageObj = new Image();
            const self = this;
            imageObj.crossOrigin = "Anonymous";
            imageObj.onload = function() {
                const drawCalcs = self.scaleToFit(imageObj.width, imageObj.height, size.width, size.height);
                cm.scale = drawCalcs.scale;
                // if scale changes here we need to redraw all the other buffers
                canvas.height = drawCalcs.height;
                const context = buffer.context;
                context.clearRect(0, 0, canvas.width, canvas.height);
                context.drawImage(imageObj, drawCalcs.x, drawCalcs.y, drawCalcs.width, drawCalcs.height);
                buffer.dirty = false;
                self.forceCanvasHeight(drawCalcs.height, slide.id);
                if (cm.width === 0 && cm.height === 0) {
                    cm.width = canvas.width;
                    cm.height = canvas.height;
                    self.debugWorkFlow && console.log(':setBackgroundImage: setting canvas size to match BG image');
                    self.setFeatureOnImage(slide, true);
                    self.setTextOnImage(slide);
                }
                self.drawCompositedImageToMain(slide, 'background');
            };
            buffer.dirty = true;
            if(slide.image.imageurl)
                imageObj.src = slide.image.imageurl + (slide.image.imageurl.startsWith('http') ? '?_='+(new Date().getTime()) : '');
        },
        setLoading(show, id) {
            if (!show) return;
            this.debugWorkFlow && console.log(':setLoading:', id);
            const canvas = document.getElementById('customerCanvas');
            const size = this.getCustomerCanvasSize(id);
            if (size.width !== 0 || size.height !== 0) {
                canvas.width = size.width;
                canvas.height = size.height;
            }
            const context = canvas.getContext("2d");
            context.fillStyle="#DC9";
            context.font = "50px arial";
            context.fillRect(0, 0, canvas.width, canvas.height);
            context.textAlign = "center";
            context.textBaseline = "middle";
            context.fillStyle="#000";
            context.fillText('Loading', canvas.width/2, canvas.height/2);
        },
        getCustomerCanvasSize(id) {
            const canvas = document.getElementById('customerCanvas');
            if(!canvas) return {"width" : 0, "height" : 0};
            const size = canvas.getBoundingClientRect();
            if (size.width === 0 || size.height === 0) {
                let i = 0;
                let sizeAlt = null;
                let canvasAlt = null;
                while (i<(this.imageDetails.length -1)) {
                    this.debugWorkFlow && console.log(':getCustomerCanvasSize: canvas', i);
                    const cm = imageDetails[i].GFXCompositeManager;
                    const buffer = cm.getOffscreenBuffer('background');
                    canvasAlt = buffer.canvas;
                    sizeAlt = (canvasAlt) ? canvasAlt.getBoundingClientRect() : { width:400, height:500 };
                    if (sizeAlt.width !== 0 || sizeAlt.height !== 0) {
                        break;
                    }
                    i++;
                }
                this.debugWorkFlow && console.log(':getCustomerCanvasSize: returning alternative values, selected index:', i, ' requested id:' + id, 'width and height', sizeAlt.width, sizeAlt.height);
                return {"width" :  Math.floor(sizeAlt.width), "height" : Math.floor(sizeAlt.height)};
            }
            this.debugWorkFlow && console.log(':getCustomerCanvasSize: returning ', id, size.width, size.height);
            return {"width" :  Math.floor(size.width), "height" : Math.floor(size.height)};
        },
        scaleToFit(sw, sh, dw, dh) {
            // scales based on width only
            const scale = dw / sw;
            const x = 0;
            const y = 0;
            return {"x" : x, "y" : y, "width" : Math.floor(sw * scale), "height" : Math.floor(sh * scale), "scale" : scale};
        },
        forceCanvasHeight(height, id) {
            const canvas = document.getElementById('customerCanvas');
            if (height>0) canvas.height = height;
        },
        setFeatureOnImage(slide, force = false) {
            if (
                (slide.layout === 'none') ||
                (this.layoutType === 'none')
                ) {
                const cm = slide.GFXCompositeManager;
                cm.getOffscreenBuffer('featureImage').dirty = false;
                return;
            }
            const shape = (slide && slide.layout !== 'none') ? slide.additional_image : null;
            this.debugWorkFlow && console.log(':setFeatureOnImage: shape', shape);
            this.insertShape(slide, shape, (slide.layout === 'custom' ? 'cropped' : 'predefined'), force);
        },
        centerImageOnTarget(target, source) {
            const center = {"x" : (target.width / 2), "y" : (target.height / 2)};
            let scale = 1;
            if (target.width <= target.height) {
                scale = target.width / source.width;
            } else {
                scale = target.height / source.height;
            }
            const scaledSource = {"width" : source.width * scale, "height" : source.height * scale};
            return {
                "x" : target.left + (target.width - (center.x + (scaledSource.width / 2))),
                "y" : target.top + (target.height - (center.y + (scaledSource.height / 2))),
                "width" : scaledSource.width,
                "height" : scaledSource.height
            };
        },
        hasAdditionalImage(element){
            if (element.layout === 'none') return false;
            const featureType = (element.layout === 'custom' ? 'cropped' : 'predefined');
            return (!!localStorage.getItem(featureType + 'Image'));
        },
        filterCarousel() {
            if (!this.unfilteredImageDetails || this.unfilteredImageDetails.length === 0) return;
            const usingCustomImage = !!localStorage.getItem('cropped' + 'Image');
            const usingPredefinedImage = !!localStorage.getItem('predefined' + 'Image');
            this.debugWorkFlow && console.log(`:filterCarousel:custom:${usingCustomImage}, predefined:${usingPredefinedImage}`);
            this.imageDetails.splice(0, this.imageDetails.length);
            if (!usingCustomImage && !usingPredefinedImage) {
                this.imageDetails = this.unfilteredImageDetails.filter((el) => { return el.layout==='none'});
            } else if (usingCustomImage && usingPredefinedImage) {
                this.imageDetails = this.unfilteredImageDetails.filter((el) => { return (el.layout==='custom' || el.layout==='predefined')});
            } else if (usingCustomImage && !usingPredefinedImage) {
                this.imageDetails = this.unfilteredImageDetails.filter((el) => { return el.layout==='custom'});
            } else {
                this.imageDetails = this.unfilteredImageDetails.filter((el) => { return el.layout==='predefined'});
            }

            this.debugWorkFlow && console.log(`:filterCarousel:filterBeforeMaterialCheck:${this.imageDetails}`);

            
            this.debugWorkFlow && console.log(`:filterCarousel:material:${this.selectedMaterial.id}`);

            //filter for materials
            if (this.materialFilter === true) {
                this.imageDetails = this.imageDetails.filter((el) => { return el.material_id === this.selectedMaterial.id });
            }

            this.debugWorkFlow && console.log(`:filerCarousel:unfilteredImagesLength:${this.imageDetails.length}:filterAfterMaterialCheck:${this.imageDetails}`);

            // if after filtering there are no items
            if (this.imageDetails.length === 0) {
                this.debugWorkFlow && console.log(':filterCarousel:no matches');
                this.imageDetails = [this.unfilteredImageDetails[0]];
            }

            this.debugWorkFlow && console.log(`:filterCarousel:this.layoutType:${this.layoutType}, this.slide.layout:${this.slide? this.slide.layout: 'not set'}`);
            // if current slide is still valid then change to it
            const currentSlideIndex= this.imageDetails.findIndex((el) => {
                return (
                    this.slide &&
                    (this.slide.layout === this.layoutType) &&
                    el.id === this.slide.id)
            });
            if (currentSlideIndex >= 0) {
                this.debugWorkFlow && console.log(`:filterCarousel:setting currentSlideIndex`);
                this.slideIndex = currentSlideIndex;
            } else {
                // try and find a slide of the correct new type to set as current
                const bestSlideIndex= this.imageDetails.findIndex((el) => {
                    return (el.layout === this.layoutType)
                });
                this.debugWorkFlow && console.log(`:filterCarousel:this.slideIndex:${this.slideIndex}, bestSlideIndex:${bestSlideIndex}`);
                this.slideIndex = bestSlideIndex >= 0 ? bestSlideIndex : 0;
            }
            this.changeSlide(0);
        },
        fadeInButtons() {
            const buttons = document.querySelector('.carousel-buttons');
            if (buttons) {
                buttons.animate(
                    [ { opacity: 1 }, { opacity: 0 }, { opacity: 1 } ],
                    { duration: 700, repeat: 1}
                );
            }
        },
        setClearButton() {
            const usingCustomImage = !!localStorage.getItem('croppedImage');
            const usingPredefinedImage = !!localStorage.getItem('predefinedImage');
            this.showClearButton = usingCustomImage || usingPredefinedImage;
        },
    },
    watch: {
        selectedMaterial: function () {
            this.filterCarousel();
        },
        layoutType: function(data) {
            this.debugWorkFlow && console.log(':Watch:layoutType', data, (!!this.unfilteredImageDetails));
            if (!this.unfilteredImageDetails) return;
            if (data !== 'none') {
                this.unfilteredImageDetails.forEach((element) => {
                    if (data === element.layout)
                        element['additionalImage'] = true;
                });
            }
            this.filterCarousel();
        },
        customImageDetails: function (propVal) {
            // entry point
            let customLayoutHasMaterials = true;
            let predefinedLayoutHasMaterials = true;

            const predefinedLayouts = propVal.filter(el => el.layout === 'predefined');
            const customLayouts = propVal.filter(el => el.layout === 'custom');

            if (!predefinedLayouts.find(el => el.material_id !== null) && propVal.find(el => el.material_id)) {
                predefinedLayoutHasMaterials = false;
            };

            if (!customLayouts.find(el => el.material_id !== null) && propVal.find(el => el.material_id)) {
                customLayoutHasMaterials = false;
            };

            if (propVal.find(el => el.layout === 'predefined') && predefinedLayoutHasMaterials) {
                bus.$emit('allowsPredefined', true);
            }
            if (propVal.find(el => el.layout === 'custom') && customLayoutHasMaterials) {
                bus.$emit('allowsCustom', true);
            }

            this.unfilteredImageDetails = propVal;
            this.createImageArray();
            this.setClearButton();
        },
        fontColour: function(props) {
            if (!this.slide) return;
            this.setTextOnImage(this.slide);
            this.drawCompositedImageToMain(this.slide, "bus.on updateColour");
        },
        customTextDetails: {
            handler: function(propVal) {
                this.customSavedLines = JSON.parse(localStorage.getItem('customSavedLines'));
                this.textDetails = propVal;
                this.drawSlide(this.slide);
            },
            deep: true
        },
        productTextFields: {
            handler: function(propVal) {
                this.customText = propVal;
                this.drawSlide(this.slide);
            },
            deep: true
        },
        product: {
            handler: function(propVal) {
                this.debugWorkFlow && console.log(':Watch:product has changed, route updated');
            },
            deep: true
        },
    },
    computed: {
        deceased() {
            return this.$store.state.deceased;
        },
        styles() {
            return this.$store.state.styles;
        },
    },
    mounted() {
        bus.$on('updateImage', () => {
            this.debugWorkFlow && console.log(':event:on:updateImage:');
            if (!this.slide) return;
        });
        bus.$on('filterCarousel', () => {
            this.debugWorkFlow && console.log(':event:on:filterCarousel:');
            this.filterCarousel();
            this.setClearButton();
        });
        bus.$on('updateFontFace', (data) => {
            this.selectedFontFace = data.font;
            if(this.imageDetails.length<1) return;
            this.setTextOnImage(this.slide);
            this.drawCompositedImageToMain(this.slide, "bus.on updateFont");
        });
        bus.$on('setMaterial', () => {
            this.materialFilter = true;            
        });
        bus.$on('setMaterial', () => {
            this.filterCarousel();
        });
    },
    destroyed() {
        bus.$off('updateImage');
        bus.$off('updateFontFace');
        bus.$off('filterCarousel');
    }
})
</script>
<style scoped>

@media screen and (min-width: 992px) {
.custom-image-component {

  
    max-width: 585px;
  }

}
.carouselImage {
    width: 100%;
    border-radius: 20px;
}
.custom-image-sticky {
    position: sticky;
    top: 250px;
}
.stepper {
    overflow: visible;
}

.carousel-buttons{
    animation-timing-function : ease-in-out;
}

</style>
