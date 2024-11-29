<template>
  <div class="px-5">
    <v-row>
      <v-col class="col-12">
        <v-card>
          <v-card-title>
          Orders
          <v-spacer></v-spacer>
          <v-text-field
            v-model="OrdersSearch"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
          />
          </v-card-title>
          <v-data-table
            :headers="ordersHeaders"
            :items="orders"
            :items-per-page="10"
            class="elevation-1"
            :loading="ordersTableLoading"
            loading-text="Loading... Please wait"
            :search="OrdersSearch"
          >
          <template v-slot:[`item.fullname`]="{ item }">
            {{ item.fullname ? item.fullname : item.site.name}}
          </template>
          <template v-slot:[`item.created_at`]="{ item }">
            {{ moment(item.created_at).format('Do MMMM YYYY, h:mm:ss a') }}
          </template>
          <template v-slot:[`item.view`]="{ item }">
            <v-btn
              rounded
              color="primary"
              dark
              :to="{ name: 'showorders', params: { id: item.id } }"
              aria-label="View"
              class="viewOrderButton"
            >
              View
            </v-btn>
          </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>
<script>
export default {
  data() {
    return {
      ordersHeaders: [
        { text: "Order Number", value: "order_number", width: "10%" },
        { text: "Deceased name", value: "fullname", width: "20%" },
        { text: "Site", value: "site.name", width: "15%" },
        { text: "Order Date", value: "created_at", width: "20%" },
        { text: "Status", value: "status.status", width: "10%" },
        { text: "Contact Type", value: "type", width: "15%" },
        { text: "View", value: "view", width: "10%" },
      ],
      orders: [],
      ordersTableLoading: false,
      OrdersSearch: "",
    };
  },
  mounted() {
    this.ordersTableLoading = true;
    this.fetchOrders();
  },
  methods: {
    fetchOrders() {
      axios.get("/api/orders").then((res) => {
        this.ordersTableLoading = false;
        this.orders = res.data;
        this.orders.forEach(el => {
          if (el.deceased) {
            el.fullname = el.deceased.firstname + ' ' + el.deceased.lastname;
          }
        })
      });
    },
  },

};
</script>
<style scoped>
a {
  text-decoration: none;
}
</style>
