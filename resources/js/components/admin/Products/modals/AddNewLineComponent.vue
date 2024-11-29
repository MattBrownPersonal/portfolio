<template>
    <v-dialog
      v-model="AddNewLineDialog"
      max-width="600px"
      persistent
    >
        <v-card>
            <div class="row pr-3 pt-5">
                <div class="col-6">
                    <v-card-title>
                        <span class="text-h5">Add New Line Type</span>
                    </v-card-title>
                </div>
                <div class="col-6 pr-5">
                    <v-select
                        label="Select a line type"
                        v-model="selectedLine"
                        :items="allLines"
                        :menu-props="{ maxHeight: '400' }"
                        persistent-hint
                        item-text="type"
                        return-object    
                        @change="setIndex()"                    
                    ></v-select>
                    <v-btn 
                        small
                        @click="addNewLine(allLines)"
                        color="primary"
                        class="float-right"
                        :disabled="!selectedLine"
                        >Add Line</v-btn>
                </div>
            </div>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage !== null">
                <p>{{ warningMessageText }}</p>
                <v-btn color="success" block @click="closeForm()" class="text-center">
                    Close
                </v-btn>
            </v-alert>
            <v-form id="newUser">
                <v-card-text>
                    <v-container>
                        <v-row class="field-types" v-for="(line, index) in visibleSelectedLines" :key="line.id" v-if="line.removed === 0">
                            <v-col cols="6">
                                Line Type: {{ line.line_types }}
                                <v-text-field
                                    label="Default Text"
                                    v-model="line.custom_message_text"
                                    v-if="line.is_custom"
                                    class="pt-4"
                                    clearable
                                ></v-text-field> 
                            </v-col>
                            <div class="delete-button" >
                                <v-icon
                                large
                                color="primary"
                                @click="moveLine('up', index, line)"
                                v-if="index !== 0"
                                >
                                mdi-arrow-up
                            </v-icon>
                            <v-icon
                                large
                                color="primary"
                                @click="moveLine('down', index, line)"
                                v-if="index !== visibleSelectedLines.length - 1"
                                >
                                mdi-arrow-down
                            </v-icon>
                            <v-icon
                                large
                                color="primary"
                                @click="removeLine(line, index)"
                                >
                                mdi-delete
                            </v-icon>
                            </div>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions v-if="warningMessage === null">
                    <v-spacer></v-spacer>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="closeForm()"
                    >
                    Close
                    </v-btn>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="sendLineTypes()"
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
    props: ['AddNewLineDialog', 'product', 'lines', 'productLines'],
    data: function() {
        return {
            warningMessage: null,
            warningMessageText: null,
            allLines: [],
            selectedLine: null,
            visibleSelectedLines: [],
            hiddenSelectedLines: [],
            customIndex: 1,
            lastIndex: null,
            custom_message_text: 'In our hearts',
        }
    },
    methods: {
        setIndex() {
            this.customIndex = this.visibleSelectedLines.slice(-1)[0].custom_index++;
            this.lastIndex = this.visibleSelectedLines.slice(-1)[0].order_index;
        },
        addNewLine(line) {
            this.lastIndex++;
            if (this.selectedLine.type === 'custom') {
                this.customIndex++; 
                this.visibleSelectedLines.push(
                    {
                        line_types: 'custom line ' + this.customIndex,
                        custom_index: this.customIndex,
                        order_index: this.lastIndex,
                        is_custom: 1,
                        custom_message_text: this.custom_message_text,
                        removed: 0
                    });
                this.selectedLine = null;                
                return;
            }
            this.visibleSelectedLines.push({
                line_types: this.selectedLine.type,
                custom_index: this.customIndex,
                order_index: this.lastIndex,
                is_custom: 0,
                custom_message_text: null,
                removed: 0
            });            
            this.selectedLine = null;
        },
        removeLine(text, index) {
            if (text.custom_index) {
                let filtered = this.visibleSelectedLines.filter(e => e.custom_index)
                this.customIndex = filtered.slice(-1)[0].custom_index + 1;
            }
            if (text.original) {
                this.$set(this.visibleSelectedLines[index], 'removed', 1)
                this.$set(this.visibleSelectedLines, index, this.visibleSelectedLines[index])
                this.visibleSelectedLines.splice(index, 1)
                this.hiddenSelectedLines.push(text)
            } else {
                this.visibleSelectedLines.splice(index, 1);
            }
        },
        sendLineTypes() {    
            const allSelectedLines = this.visibleSelectedLines.concat(this.hiddenSelectedLines);
            const lines = new FormData();
            lines.append('lines', JSON.stringify(allSelectedLines));
            lines.append('product_id', this.product.id);
            axios.post('/api/linetypes', lines) 
                .then(res => {
                    this.visibleSelectedLines = [];
                    this.hiddenSelectedLines = [];
                    this.$emit('linesSaved');
                    this.closeForm();
                })
                .catch(err => {
                    this.warningMessage = true;
                    this.warningMessageText = err.response.data.message;
                });
        },
        fetchAllLines() {
            axios.get('/api/linetypes')
            .then(res => {                
                res.data.forEach(element => {
                    this.allLines.push({type: element.type});
                });
            })
            .catch(err => {
                this.warningMessage = true;
                this.warningMessageText = err.response.data.message;
            });
        },
        moveLine(direction, oldIndex, line) {
            let newIndex = null;
            let oldOrderIndex = null;
            let newOrderIndex = null;

            if (direction === 'up') {
                newIndex = oldIndex - 1        
                oldOrderIndex = this.visibleSelectedLines[oldIndex].order_index;
                newOrderIndex = this.visibleSelectedLines[newIndex].order_index;   

                this.visibleSelectedLines[oldIndex].order_index = newOrderIndex;
                this.visibleSelectedLines[newIndex].order_index = oldOrderIndex;
            } else {
                newIndex = oldIndex + 1
                oldOrderIndex = this.visibleSelectedLines[oldIndex].order_index;
                newOrderIndex = this.visibleSelectedLines[newIndex].order_index;

                this.visibleSelectedLines[oldIndex].order_index = newOrderIndex;
                this.visibleSelectedLines[newIndex].order_index = oldOrderIndex;
            }  
            this.visibleSelectedLines.splice(oldIndex, 1)
            this.visibleSelectedLines.splice(newIndex, 0, line)
        },
        closeForm() {
            this.$emit('closeForm', false)
        },
    },
    watch: {
        productLines: function (propVal) {
            this.visibleSelectedLines = [];
            this.hiddenSelectedLines = [];
            propVal.forEach(e => {
                e.original = true;
                if (e.removed === 0) {
                    this.visibleSelectedLines.push(e)
                } else {
                    this.hiddenSelectedLines.push(e)
                }
            })            
        }
    } ,
    mounted() {
        this.fetchAllLines();
    }
})
</script>
<style scoped>
.field-types{
    border: 1px solid grey;
    margin-bottom: 5px;
    position: relative
}
.delete-button{
    position: absolute;
    bottom: 0;
    right: 0;
}
</style>
