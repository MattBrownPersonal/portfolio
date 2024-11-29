<template>
  <div class="px-5">
    <v-row v-if="canCreate">
      <v-col class="col-12" no-gutters>
        <v-btn
          fab
          fixed
          right
          bottom
          color="primary"
          dark
          @click="newUserDialog = true"
        >
          <v-icon>add</v-icon>
        </v-btn>
      </v-col>
    </v-row>
    <v-card>
      <v-card-title>
        Staff
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        />
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="users"
        :items-per-page="10"
        class="elevation-1"
        :loading="usersTableLoading"
        loading-text="Loading... Please wait"
        :search="search"
      >
        <template v-slot:[`item.roles`]="{ item }">
          <div v-for="role in item.roles.filter( (v,i,s) => i === s.findIndex( (o) => (o.id ===v.id) ) )" :key="role.id" style="display:none">
            {{ role.name }}
          </div>
        </template>
        <template v-slot:[`item.edit`]="{ item }" v-if="canCreate">
          <v-btn
            rounded
            color="primary"
            dark
            :to="{ name: 'viewuser', params: { id: item.id } }"
          >
            View
          </v-btn>
        </template>
      </v-data-table>
    </v-card>

    <v-snackbar
      v-model="snackbar"
      :timeout="timeout"
      rounded="pill"
      color="primary"
    >
      {{ message }}

      <template v-slot:action="{ attrs }">
        <v-btn color="white" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
    <NewUserComponent
      v-bind:newUserDialog="newUserDialog"
      v-bind:canCreate="canCreate"
      v-on:closeForm="closeFormChange($event)"
      v-on:fetchUsers="fetchUsers()"
      v-on:savedMessage="savedMessage($event)"
    />
    <EditUserComponent
      v-bind:editUserDialog="editUserDialog"
      v-on:closeForm="closeFormChange($event)"
      v-bind:selectedUser="selectedUser"
      v-on:fetchUsers="fetchUsers()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
    <DeleteUserComponent
      v-bind:deleteUserDialog="deleteUserDialog"
      v-on:closeForm="closeFormChange($event)"
      v-bind:selectedUser="selectedUser"
      v-on:fetchUsers="fetchUsers()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
  </div>
</template>
<script>
import NewUserComponent from "./modals/AddNewUserComponent";
import EditUserComponent from "./modals/EditUserComponent";
import DeleteUserComponent from "./modals/DeleteUserComponent";
export default {
  components: {
    NewUserComponent,
    EditUserComponent,
    DeleteUserComponent,
  },
  inject: ["currentUser"],
  data() {
    return {
      headers: [
        { text: "", value: "roles", width: "5%" },
        { text: "First Name", align: "start", value: "firstname", width: "25%" },
        { text: "Surname", align: "start", value: "lastname", width: "25%" },
        { text: "Email", value: "email", width: "20%" },
        { text: "Action", value: "edit", width: "10%" },
      ],
      users: [],
      newUserDialog: false,
      editUserDialog: false,
      deleteUserDialog: false,
      usersTableLoading: false,
      search: "",
      selectedUser: "",
      message: "",
      snackbar: false,
      timeout: 4000,
      canCreate: null,
    };
  },
  mounted() {
    this.usersTableLoading = true;
    this.fetchUsers();
  },
  methods: {
    fetchUsers() {
      axios.get("/api/users").then((res) => {
        this.usersTableLoading = false;
        this.users = res.data.users;
        this.canCreate = res.data.canCreate;
      });
    },
    editUserDetails(item) {
      this.selectedUser = item;
    },
    closeFormChange(state) {
      this.newUserDialog = state;
      this.editUserDialog = state;
      this.deleteUserDialog = state;
    },
    savedMessage(event) {
      this.message = event;
      this.snackbar = true;
    },
  },
};
</script>
