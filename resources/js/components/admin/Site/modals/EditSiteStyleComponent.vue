<template>
    <v-dialog
      v-model="editSiteStyleDialog"
      max-width="1200px"
      persistent>
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit Site Details</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage">
                {{warningMessage}}
            </v-alert>
            <v-form id="newUser">
                <v-card-text class="py-0">
                    <v-container>
                        <v-row>
                            <v-col
                            cols="4"
                            >
                            <span class="text-h6">Site Name</span>
                            <v-text-field
                            placeholder="Site Name"
                            :rules="[() => !!site_name || 'This field is required']"
                            :error-messages="errorMessages"
                            v-model="site_name"
                            >
                            </v-text-field>

                            </v-col>

                            <v-col
                            cols="4"
                            >
                            <span class="text-h6">Site Logo</span>
                            <v-file-input
                            chips
                            truncate-length="15"
                            label="Upload Site Logo"
                            v-model='logo_file'
                            @change="previewImage()"
                            accept="image/*"
                            show-size
                            prepend-icon="mdi-camera"
                            ></v-file-input>
                            <v-img :src="url"></v-img>

                            </v-col>
                            <v-col cols="4">
                                <p>Does this site use product categories?</p>
                                <v-radio-group v-model="usesCategories" row mandatory>
                                    <v-radio label="Yes" value=1></v-radio>
                                    <v-radio label="No" value=0></v-radio>
                                </v-radio-group>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col
                            cols="3"
                            >
                            <span class="text-h6">Primary Colour</span>
                            <v-color-picker
                            dot-size="25"
                            swatches-max-height="200"
                            label="Primary Colour"
                            v-model='primary_colour'
                            mode="hexa"
                            ></v-color-picker>
                            </v-col>
                            <v-col
                            cols="3"
                            >
                            <span class="text-h6">Secondary Colour</span>
                            <v-color-picker
                            dot-size="25"
                            swatches-max-height="200"
                            label="Secondary Colour"
                            v-model='secondary_colour'
                            mode="hexa"
                            ></v-color-picker>
                            
                            </v-col>
                            <v-col
                            cols="3"
                            >
                            <span class="text-h6">Primary Font Colour</span>
                            <v-color-picker
                            dot-size="25"
                            swatches-max-height="200"
                            label="Primary Font Colour"
                            v-model='primary_font_colour'
                            mode="hexa"
                            ></v-color-picker>
                           
                            </v-col>
                            <v-col
                            cols="3"
                            >
                            <span class="text-h6">Secondary Font Colour</span>
                            <v-color-picker
                            dot-size="25"
                            swatches-max-height="200"
                            label="Secondary Font Colour"
                            v-model='secondary_font_colour'
                            mode="hexa"
                            ></v-color-picker>
                           
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-container v-if="primary_colour">
                    <div class="row mx-5">
                        <div class="col-12" :style="{backgroundColor: primary_colour}">
                            <span class="text-h6" :style="{color: primary_font_colour}">Example Secondary Text</span>
                        </div>
                    </div>
                    <div class="row mx-5">
                        <div class="col-12 pt-4" style="height: 150px" :style="{backgroundColor: secondary_colour}">
                            <p :style="{color: secondary_font_colour}">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam quis magna lacinia, maximus velit in, efficitur arcu. Mauris finibus magna nec mi euismod, eu accumsan odio vestibulum. Sed non dictum massa, ac finibus eros</p>
                            <v-btn
                            :style="{backgroundColor: primary_colour, color: primary_font_colour}"
                            >Example Button</v-btn>
                        </div>
                    </div>
                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="closeForm();"
                    >
                    Close
                    </v-btn>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="sendSiteForm();"
                    >
                    Save
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>

export default ({
    props: ['editSiteStyleDialog', 'siteStyle', 'selectedSite'],
    data: function() {
        return {
            id: null,
            site_name: null,
            logo_url: null,
            logo_file: null,
            image_id: null,
            primary_colour: '#25FF74',
            secondary_colour: '#FDF12B',
            primary_font_colour: '#082DFF',
            secondary_font_colour: '#FF2222',
            warningMessage: '',
            url: null,
            site_name: null,
            errorMessages: '',
            usesCategories: null
        }
    },
    methods: {
        sendSiteForm() {
            let style = new FormData();
            if (this.logo_file)
            {
                this.logo_url = this.logo_file.name;
                style.append('logo_file', this.logo_file);
            }
            style.append('logo_url', this.logo_url);
            if(this.image_id != null) {
                style.append('image_id', this.image_id);
            }
            style.append('site_name', this.site_name);
            style.append('primary_colour', this.primary_colour);
            style.append('secondary_colour', this.secondary_colour);
            style.append('primary_font_colour', this.primary_font_colour);
            style.append('secondary_font_colour', this.secondary_font_colour);
            style.append('site_id', this.selectedSite.id);
            style.append('uses_categories', this.usesCategories);
            style.append('_method', 'PUT');

            axios.post('/api/siteStyles/'  + this.id, style)
            .then(res => {
                this.closeForm();
                this.$emit('fetchSites');
                this.$emit('triggerSnackBar', res.data.message);
            })
            .catch(err => {
                this.warningMessage = err.response.data.message;
            });
        },
        closeForm() {
            this.$emit('closeForm', false)
        },
        previewImage() {
            this.url = URL.createObjectURL(this.logo_file)
        },
        hexaToHSLA(hexAColour) {
            // original function taken from https://css-tricks.com/converting-color-spaces-in-javascript/
            // modified to return an object
            // Convert hex to RGB first
            let r = 0, g = 0, b = 0, a = 1;

            if (hexAColour.length == 5) {
            r = "0x" + hexAColour[1] + hexAColour[1];
            g = "0x" + hexAColour[2] + hexAColour[2];
            b = "0x" + hexAColour[3] + hexAColour[3];
            a = "0x" + hexAColour[4] + hexAColour[4];
            } else if (hexAColour.length == 9) {
            r = "0x" + hexAColour[1] + hexAColour[2];
            g = "0x" + hexAColour[3] + hexAColour[4];
            b = "0x" + hexAColour[5] + hexAColour[6];
            a = "0x" + hexAColour[7] + hexAColour[8];
            }
            // Then to HSL
            r /= 255;
            g /= 255;
            b /= 255;
            let cmin = Math.min(r,g,b),
                cmax = Math.max(r,g,b),
                delta = cmax - cmin,
                h = 0,
                s = 0,
                l = 0;

            if (delta == 0)
            h = 0;
            else if (cmax == r)
            h = ((g - b) / delta) % 6;
            else if (cmax == g)
            h = (b - r) / delta + 2;
            else
            h = (r - g) / delta + 4;

            h = Math.round(h * 60);

            if (h < 0)
            h += 360;

            l = (cmax + cmin) / 2;
            s = delta == 0 ? 0 : delta / (1 - Math.abs(2 * l - 1));
            s = +(s * 100).toFixed(1);
            l = +(l * 100).toFixed(1);

            a = (a / 255).toFixed(3);

            return {h : h, s : s, l : l, a : a};
        },
        HSLATohexa(hslaColour)
        {
            // function taken from https://css-tricks.com/converting-color-spaces-in-javascript/
            let sep = hslaColour.indexOf(",") > -1 ? "," : " ";
            hslaColour = hslaColour.substr(5).split(")")[0].split(sep);

            // strip the slash
            if (hslaColour.indexOf("/") > -1)
            hslaColour.splice(3,1);

            let h = hslaColour[0],
                s = hslaColour[1].substr(0,hslaColour[1].length - 1) / 100,
                l = hslaColour[2].substr(0,hslaColour[2].length - 1) / 100,
                a = hslaColour[3];

            // strip label and convert to degrees (if necessary)
            if (h.indexOf("deg") > -1)
                h = h.substr(0,h.length - 3);
            else if (h.indexOf("rad") > -1)
                h = Math.round(h.substr(0,h.length - 3) * (180 / Math.PI));
            else if (h.indexOf("turn") > -1)
                h = Math.round(h.substr(0,h.length - 4) * 360);
            if (h >= 360)
                h %= 360;

            // strip % from alpha, make fraction of 1 (if necessary)
            if (a.indexOf("%") > -1)
                a = a.substr(0,a.length - 1) / 100;

            let c = (1 - Math.abs(2 * l - 1)) * s,
                x = c * (1 - Math.abs((h / 60) % 2 - 1)),
                m = l - c/2,
                r = 0,
                g = 0,
                b = 0;

            if (0 <= h && h < 60) {
                r = c; g = x; b = 0;
            } else if (60 <= h && h < 120) {
                r = x; g = c; b = 0;
            } else if (120 <= h && h < 180) {
                r = 0; g = c; b = x;
            } else if (180 <= h && h < 240) {
                r = 0; g = x; b = c;
            } else if (240 <= h && h < 300) {
                r = x; g = 0; b = c;
            } else if (300 <= h && h < 360) {
                r = c; g = 0; b = x;
            }
            r = Math.round((r + m) * 255).toString(16);
            g = Math.round((g + m) * 255).toString(16);
            b = Math.round((b + m) * 255).toString(16);
            a = Math.round(a * 255).toString(16);

            if (r.length == 1)
                r = "0" + r;
            if (g.length == 1)
                g = "0" + g;
            if (b.length == 1)
                b = "0" + b;
            if (a.length == 1)
                a = "0" + a;

            return "#" + r + g + b + a;
        },
    },
    watch: {
        siteStyle: function(propVal) {
            if (propVal) {
                let secondaryColour = ''
                if (propVal.secondary_colour.length > 7) {
                    secondaryColour = propVal.secondary_colour.slice(0, -2);                     
                } else {
                    secondaryColour = propVal.secondary_colour
                }
                this.id = propVal.id;
                this.logo_url = propVal.logo_url;
                this.primary_colour = propVal.primary_colour;
                this.secondary_colour = secondaryColour;
                this.primary_font_colour = propVal.primary_font_colour;
                this.secondary_font_colour = propVal.secondary_font_colour;
                this.image_id = propVal.image_id
                if (propVal.logo_file)
                {
                    this.url = propVal.logo_file;
                }
            }
        },
        selectedSite: function(propVal) {
            this.site_name = propVal.name;
            this.id = propVal.id;
            this.usesCategories = String(propVal.uses_categories);
        },
        primary_colour: function(propVal) {
            // take the new primary_colour and add 10% brightness , saving the new value to secondary_colour
            if (propVal.length<9) propVal += "FF"; // set 100% opacity if not supplied
            const hsla = this.hexaToHSLA(propVal);
            if (hsla.l >= 90) {
                hsla.l = 100; // max brightness
            } else {
                hsla.l += 10; // as a percentage
            }
            const hslaString = `hsla(${hsla.h},${hsla.s}%,${hsla.l}%,${hsla.a})`;
            const hexa = this.HSLATohexa(hslaString);
            this.secondary_colour = hexa;
        }
    }
})
</script>
