<template>
    <div>
        <div class="row">
            <div class="col-6">
                <v-btn 
                @click="setShape('ellipse')"
                color="primary"
                rounded    
                block 
                >Ellipse</v-btn>
            </div>
            <div class="col-6">
                <v-btn 
                @click="setShape('rectangle')"
                color="primary"
                rounded    
                block 
                >Rectangle</v-btn>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <v-text-field
                    label="X Axis"
                    type="number"
                    v-model.number="shape.x"
                    @click="insertShape()"
                    @change="insertShape()"
                ></v-text-field>
            </div>
            <div class="col-6">
                <v-text-field
                    label="Y Axis"
                    type="number"
                    v-model.number="shape.y"
                    @click="insertShape()"
                    @change="insertShape()"
                ></v-text-field>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <v-text-field
                    label="Image Height"
                    type="number"
                    v-model.number="shape.height"
                    @click="insertShape()"
                    @change="insertShape()"
                ></v-text-field>
            </div>
            <div class="col-6">
                <v-text-field
                    label="Image Width"
                    type="number"
                    v-model.number="shape.width"  
                    @click="insertShape()"                  
                    @change="insertShape()"
                ></v-text-field>
            </div>
        </div>
        <div class="row">
            <div class="col-4 offset-2">
                <v-btn
                color="error"
                rounded
                block
                @click="cancel()"
                >Cancel</v-btn>
            </div>
            <div class="col-4">
                <v-btn 
                color="primary"
                rounded    
                block 
                @click="saveField()"
                >OK</v-btn>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["shape"],
    data: function() {
        return {
            smallImage: [],
            smallImgUrl: null,
        }
    },
    methods: {
        setShape(shape) {
            this.shape.type = shape;
            this.insertShape();
        },
        insertShape(){
            this.$emit('shapeDetails', this.shape);
        },
        cancel() {
            this.$emit('cancel');
        },
        saveField() {
            this.$emit('close');
        }
    }
}
</script>
