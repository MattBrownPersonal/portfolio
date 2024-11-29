// Graphic effects Composite Manager
//
// Holds offscreen buffers and will composite them as required.

class GFXCompositeManager {

    constructor(buffers) {
        if (buffers) {
            this.buffers = buffers;
        } else {
            this.buffers = [];
        }
    }

    scale = 1.0;
    width = 0;
    height = 0;

    addOffscreenBuffer(name) {
        let buffer = this.getOffscreenBuffer(name);
        if (!buffer) {
            buffer = this.createNewBuffer(name);
            this.buffers.push(buffer);
        }
        return buffer
    }

    createNewBuffer(name) {
        const canvas = document.createElement("canvas");
        return {
            "name" : name, 
            "canvas" : canvas, 
            "context" : canvas.getContext("2d"),
            "dirty" : false
        };
    }

    getOffscreenBuffer(name) {
        return this.buffers.filter(buffer => buffer.name === name)[0];
    }

    compositeBuffersByName(bufferNames, context) {
        bufferNames.forEach(name => {
            let canvas = this.getOffscreenBuffer(name).canvas;
            if (canvas.width !== 0 || canvas.height !== 0) {
                context.drawImage(canvas, 0, 0);
            }
        });
    }

    isDirty() {
        return this.buffers.some((v) => v.dirty);
    }

}

window.GFXCompositeManager = GFXCompositeManager;