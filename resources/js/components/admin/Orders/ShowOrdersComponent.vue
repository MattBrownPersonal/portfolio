<template>
    <div class="px-4" v-if="order.itemorders">
        <v-row>
            <v-col class="col-2">
                <v-card>
                    <v-card-text>
                        <p class="text-h6">Actions</p>
                        <v-divider></v-divider>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>
                                    <v-icon left>mdi-keyboard-backspace</v-icon>
                                    <span @click="$router.go(-1)" class="actionLink">Back</span>
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col class="col-10">
                <v-row>
                    <v-col>
                        <v-card>
                            <v-card-title>
                                <p>Order Details</p>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col">
                                        <p class="font-weight-bold">Order Number</p>
                                        {{ order.order_number }}
                                    </v-col>
                                    <v-col class="col">
                                        <p class="font-weight-bold">Order Type (click to edit)</p>
                                        <v-edit-dialog :return-value.sync="order.type" large persistent
                                            @save="save('order_type')">
                                            <div class="order-dropdown">{{ order.type }}</div>
                                            <template v-slot:input v-if="order.itemorders[0]">
                                                <div class="mt-4 text-h6">
                                                    Update Order Type
                                                </div>
                                                <v-select :items="orderTypes" v-model="order.type" label="Order Status"
                                                    item-text="status" item-value="status" class="orderTypes"></v-select>
                                            </template>
                                        </v-edit-dialog>
                                    </v-col>
                                    <v-col class="col">
                                        <p class="font-weight-bold">Order Date</p>
                                        {{ order.order_date }}
                                    </v-col>
                                    <v-col class="col">
                                        <p class="font-weight-bold">Site</p>
                                        <span v-if="order.site">{{ order.site.name }}</span>
                                    </v-col>
                                    <v-col class="col">
                                        <p class="font-weight-bold">Status (click to edit)</p>
                                        <v-edit-dialog :return-value.sync="order.status" large persistent
                                            @save="save('status')">
                                            <div>{{ order.status.status }}</div>
                                            <template v-slot:input v-if="order.itemorders[0]">
                                                <div class="mt-4 text-h6">
                                                    Update Order Status
                                                </div>
                                                <v-select :items="orderStatus" v-model="order.itemorders[0].status"
                                                    label="Order Status" item-text="status" item-value="id"
                                                    :value=orderStatus.id></v-select>
                                            </template>
                                        </v-edit-dialog>
                                    </v-col>
                                    <v-col class="col" v-if="totalPrice">
                                        <p class="font-weight-bold">Price</p>
                                        <span v-if="order.itemorders">£{{ totalPrice }}</span>

                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card>
                            <v-card-title>
                                <p class="mb-1">Customer Details</p>
                                <v-btn text color="primary" @click="editModalDialogue('editCustomerDetails')">
                                    Edit Customer Details
                                </v-btn>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">Name</p>
                                        <span v-if="order.name && order.name !== 'null'">{{ order.name }}</span>
                                        <span v-else><i>Not available</i></span>
                                    </v-col>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">Email</p>
                                        <span v-if="order.email && order.email !== 'null'">{{ order.email }}</span>
                                        <span v-else><i>Not available</i></span>
                                    </v-col>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">Phone Number</p>
                                        <span
                                            v-if="order.phone_number && order.phone_number !== 'null' && order.phone_number !== 'undefined'">{{ order.phone_number }}</span>
                                        <span v-else><i>Not available</i></span>
                                    </v-col>                                    
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-if="order.message">
                    <v-col>
                        <v-card>
                            <v-card-title>
                                <p>Customer Message</p>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col-12">
                                        <span v-if="order.message && order.message !== 'null'">{{ order.message }}</span>
                                        <span v-else><i>The customer did not provide a message</i></span>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-if="order.deceased">
                    <v-col>
                        <v-card>
                            <v-card-title>
                                <p class="mb-1">Deceased</p>
                                <v-btn text color="primary" @click="editModalDialogue('editDeceasedDetails')">
                                    Edit Deceased Details
                                </v-btn>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">First Name</p>
                                        <span>{{ order.deceased.firstname }}</span>
                                    </v-col>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">Middle Name</p>
                                        <span>{{ order.deceased.middlename }}</span>
                                    </v-col>
                                    <v-col class="col-3">
                                        <p class="font-weight-bold">Last Name</p>
                                        <span>{{ order.deceased.lastname }}</span>
                                    </v-col>                                    
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card>
                            <v-card-title>
                                <p class="mb-1">Product Details</p>
                                <v-btn text color="primary" @click="editModalDialogue('editProductDetails')">
                                    Choose New Product
                                </v-btn>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col-3" v-if="product">
                                        <p class="font-weight-bold">Product</p>
                                        <span>{{ product.product_name }}</span>

                                    </v-col>
                                    <v-col class="col-3" v-if="product">
                                        <p class="font-weight-bold">Supplier</p>
                                        <span>{{ product.supplier_name }}</span>

                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card>
                            <v-card-title>
                                <p class="mb-1">Attributes</p>
                                <v-btn text color="primary" @click="editModalDialogue('editAttributeDetails')">
                                    Edit Attribute Details
                                </v-btn>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col" v-for="(attribute, index) in orderAttributes" :key="attribute.id">
                                        <p class="font-weight-bold">{{ attribute.attribute_type }}</p>
                                        <p>{{ attribute.attribute_name }} <span v-if="attribute.price">- Price
                                                £{{ attribute.price }}</span> </p>
                                    </v-col>                                    
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row v-if="order.itemorders[0] && order.itemorders[0].image_url">
                    <v-col>
                        <v-card>
                            <v-card-title>
                                <p>Product Image</p>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col-6 offset-3">
                                        <img :src="order.itemorders[0].image_url" alt="">
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card>
                            <v-card-title>
                                <p>Add New Note</p>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col class="col-12">
                                        <v-textarea name="input-7-1" label="Add new note" value="" hint="Enter Note"
                                            v-model="newNote"></v-textarea>
                                        <v-btn v-if="newNote" block color="primary" @click="addNote">
                                            Add Note
                                        </v-btn>
                                        <v-btn v-else block color="primary" @click="addNote" disabled>
                                            Add Note
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-card>
                            <v-card-title>
                                <p>Notes</p>
                            </v-card-title>
                            <v-card-text v-for="note in notes" :key="note.id">
                                <v-row>
                                    <div class="col-6">
                                        Posted by <span
                                            class="font-weight-bold">{{ note.user.firstname + ' ' + note.user.lastname }}</span>
                                        on {{ moment(note.created_at).format('MMMM Do YYYY, h:mm:ss a') }}
                                    </div>
                                    <div class="col-6"></div>
                                </v-row>
                                <v-row>
                                    <div class="col-8">
                                        {{ note.note }}
                                    </div>
                                    <div class="col-4 text-right"
                                        v-if="note.old_status_id != null && note.new_status_id != null">
                                        <v-chip class="ma-2" color="teal lighten-4">
                                            {{ note.old_status.status }}
                                        </v-chip>
                                        <span class="mdi mdi-arrow-right-bold"></span>
                                        <v-chip class="ma-2" color="primary">
                                            {{ note.new_status.status }}
                                        </v-chip>
                                    </div>
                                    <div class="col-4 text-right"
                                        v-else-if="note.old_type != null && note.new_type != null">
                                        <v-chip class="ma-2" color="teal lighten-4">
                                            {{ note.old_type }}
                                        </v-chip>
                                        <span class="mdi mdi-arrow-right-bold"></span>
                                        <v-chip class="ma-2" color="primary">
                                            {{ note.new_type }}
                                        </v-chip>
                                    </div>
                                    <div class="col-4 text-right" v-else-if="note.new_status_id != null">
                                        <v-chip class="ma-2" color="primary">
                                            {{ note.new_status.status }}
                                        </v-chip>
                                    </div>
                                </v-row>
                                <v-divider></v-divider>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
        <v-snackbar v-model="snackbar" :timeout="timeout" color="primary">
            {{ snackbarText }}

            <template v-slot:action="{ attrs }">
                <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        <EditCustomerDetails v-bind:editCustomerDetailsDialogue="editCustomerDetailsDialogue" v-bind:order="order"
            v-on:closeForm="closeFormChange($event)" v-on:fetchOrder="fetchOrder()" />
        <EditDeceasedDetails v-bind:editDeceasedDetailsDialogue="editDeceasedDetailsDialogue" v-bind:order="order"
            v-on:closeForm="closeFormChange($event)" v-on:fetchOrder="fetchOrder()" />
        <EditProductDetails v-bind:editProductDetailsDialogue="editProductDetailsDialogue" v-bind:order="order"
            v-on:closeForm="closeFormChange($event)" v-on:fetchOrder="fetchOrder()" />
        <EditAttributeDetails v-bind:editAttributeDetailsDialogue="editAttributeDetailsDialogue"
            v-bind:orderAttributes="orderAttributes" v-bind:order="order" v-on:closeForm="closeFormChange($event)"
            v-on:fetchOrder="fetchOrder()" />
    </div>
</template>
<script>
import EditCustomerDetails from "./modals/EditCustomerDetailsDialogue";
import EditDeceasedDetails from "./modals/EditDeceasedDetailsDialogue"
import EditProductDetails from "./modals/EditProductDetailsDialogue"
import EditAttributeDetails from "./modals/EditAttributeDetailsDialogue";
export default {
    components: {
        EditCustomerDetails,
        EditDeceasedDetails,
        EditProductDetails,
        EditAttributeDetails
    },
    data() {
        return {
            order: [],
            snackbar: false,
            snackbarText: '',
            timeout: 4000,
            orderStatus: [
                { id: 1, status: 'Unfulfilled' },
                { id: 2, status: 'Processing' },
                { id: 3, status: 'Fulfilled' },
            ],
            orderTypes: [
                { id: 1, status: 'Enquiry - from contact us' },
                { id: 2, status: 'Enquiry - from share with crem' },
                { id: 3, status: 'Order' },
            ],
            selectedOrderStatus: null,
            userId: null,
            orderItemId: null,
            notes: [],
            newNote: '',
            oldStatus: null,
            oldType: null,
            orderAttributes: null,
            totalPrice: null,
            editCustomerDetailsDialogue: false,
            editDeceasedDetailsDialogue: false,
            editProductDetailsDialogue: false,
            editAttributeDetailsDialogue: false,
            product: []
        }
    },
    methods: {
        fetchOrder() {
            axios.get(`/api/orders/${this.$route.params.id}`)
                .then(res => {
                    this.order = res.data.orders;
                    this.userId = res.data.id;
                    if (this.order.itemorders[0]) {
                        this.orderItemId = this.order.itemorders[0].id;
                        this.oldStatus = this.order.itemorders[0].status;
                        this.oldType = this.order.type;
                    }
                    this.calculateTotalPrice();
                    this.fetchNotes();
                    this.getAttributes();
                    this.setProduct();
                })
        },
        fetchNotes() {
            axios.get(`/api/notes/${this.orderItemId}`).then(res => {
                this.notes = res.data;
            })
        },
        triggerSnackBar($event) {
            this.snackbarText = $event
            this.snackbar = true;
        },
        addNote() {
            const newNote = new FormData();
            newNote.append('newNote', this.newNote);
            newNote.append('user_id', this.userId);
            newNote.append('new_status', this.order.itemorders[0].status.id);
            newNote.append('id', this.order.itemorders[0].id);
            newNote.append('_method', 'PUT');
            this.saveDetails(newNote);
        },
        save(updateSource) {
            const status = new FormData();
            if (updateSource === 'status') {
                status.append('old_status', this.oldStatus.id);
                status.append('new_status', this.order.itemorders[0].status);
            } else {
                status.append('old_type', this.oldType);
                status.append('new_type', this.order.type);
            }
            status.append('source', updateSource);
            status.append('user_id', this.userId);
            status.append('id', this.order.itemorders[0].id);
            status.append('_method', 'PUT');
            this.saveDetails(status);
        },
        saveDetails(payload) {
            axios.post(`/api/notes/${this.$route.params.id}`, payload).then(res => {
                this.fetchOrder();
                this.newNote = null;
            })
        },
        chipColor(state) {
            switch (state) {
                case 1:
                    return 'secondary';
                case 2:
                    return 'primary';
                case 3:
                    return 'success';

            }
        },
        calculateTotalPrice() {
            const calculatedPrice = [];
            this.order.itemorders.forEach(element => {
                if (element.price) {
                    calculatedPrice.push(parseFloat(element.price))
                }
            });
            if (calculatedPrice.length > 0) {
                this.totalPrice = parseFloat(calculatedPrice.reduce((a, b) => a + b)).toFixed(2);
            }
        },
        getAttributes() {
            const attributes = [];
            this.order.itemorders.forEach(element => {
                if (element.item_type !== "product") {
                    attributes.push(element);
                }
            });
            this.orderAttributes = attributes;
        },
        editModalDialogue(section) {
            switch (section) {
                case 'editCustomerDetails':
                    this.editCustomerDetailsDialogue = true;
                    break;
                case 'editDeceasedDetails':
                    this.editDeceasedDetailsDialogue = true;
                    break;
                case 'editProductDetails':
                    this.editProductDetailsDialogue = true;
                    return 'success';
                case 'editAttributeDetails':
                    this.editAttributeDetailsDialogue = true;
                    return 'success';

            }
        },
        setProduct()
        {
            this.product = this.order.itemorders.find( (x) => x.item_type === 'product')
        },
        closeFormChange($event) {
            this.editCustomerDetailsDialogue = $event;
            this.editDeceasedDetailsDialogue = $event;
            this.editProductDetailsDialogue = $event;
            this.editAttributeDetailsDialogue = $event;
        }
    },
    mounted() {
        this.fetchOrder();        
    }
}
</script>
<style scoped>
.crudMenu {
    list-style-type: none;
}

.actionLink {
    cursor: pointer;
}
</style>