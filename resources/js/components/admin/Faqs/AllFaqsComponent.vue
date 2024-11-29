<template>
  <div class="px-5">
    <v-row>
      <v-row class="addNewButtonWrapper">
        <v-col class="col-md-4 offset-4 d-flex justify-center" no-gutters>
          <v-btn
          fab fixed bottom right
          color="primary"
          dark
          @click="newFaqDialogue = true"
          >
          <v-icon>add</v-icon>
          </v-btn>
        </v-col>
      </v-row>
      <v-col class="col-12">
        <v-card>
          <v-card-title>
            Global FAQs
            <v-spacer></v-spacer>
            <v-text-field
              v-model="faqSearch"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            />
          </v-card-title>
          <v-data-table
              :headers="faqHeaders"
              :items="faqs"
              :items-per-page="10"
              class="elevation-1"
              :loading="faqsTableLoading"
              loading-text="Loading... Please wait"
              :search="faqSearch"
              >
              <template v-slot:[`item.answer`]="props">
                <v-edit-dialog
                :return-value.sync="props.item.answer"
                large
                persistent
                @save="save(props.item.answer, props.item.id, 'answer')"
                >
                <div>{{ props.item.answer }}</div>
                <template v-slot:input>
                <div class="mt-4 text-h6">
                Update
                </div>
                <v-textarea
                v-model="props.item.answer"
                label="Edit"
                single-line
                counter
                autofocus
                ></v-textarea>
                </template>
                </v-edit-dialog>
              </template>
              <template v-slot:[`item.question`]="props">
                <v-edit-dialog
                :return-value.sync="props.item.question"
                large
                persistent
                @save="save(props.item.question, props.item.id, 'question')"
                >
                <div>{{ props.item.question }}</div>
                <template v-slot:input>
                <div class="mt-4 text-h6">
                Update
                </div>
                <v-textarea
                v-model="props.item.question"
                label="Edit"
                single-line
                counter
                autofocus
                ></v-textarea>
                </template>
                </v-edit-dialog>
              </template>
              <template v-slot:[`item.site_id`]="{ item }">
                {{ item.site ? item.site.name : 'All Sites'}}
              </template>
              <template v-slot:[`item.delete`]="{ item }">
                <v-btn @click="toggleFaqVisibility(item.id, 1)" v-if="item.hidden == 0" color="error" small>Hide FAQ
                </v-btn>
                <v-btn @click="toggleFaqVisibility(item.id, 0)" v-else color="primary" small>Show FAQ</v-btn>
              </template>
            </v-data-table>
        </v-card>
      </v-col>
    </v-row>
    <DeleteFaqComponent
      v-bind:deleteFaqDialog="deleteFaqDialog" 
      v-bind:selectedFaq="selectedFaq" 
      v-on:closeForm="closeFormChange($event)"
      v-on:fetchFaqs="fetchFaqs()"
      v-on:triggerSnackBar="triggerSnackBar($event)"
    />
    <NewFaqArticle
        v-bind:newFaqDialogue="newFaqDialogue"
        v-on:fetchSiteFaqs="fetchFaqs()"
        v-on:closeForm="newFaqDialogue = false"
      />
    <v-snackbar v-model="snackbar" :timeout="timeout">
      {{ snackbarText }}

      <template v-slot:action="{ attrs }">
        <v-btn color="blue" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>
<script>
import NewFaqArticle from './modals/NewFaqModalComponent.vue'
import DeleteFaqComponent from './modals/DeleteFaqComponent'
export default {
  components: {
    NewFaqArticle,
    DeleteFaqComponent
  },
  data() {
    return {
      faqHeaders: [
        { text: 'Question (Click to Edit)', align: 'start', value: 'question', width: '25%' },
        { text: 'Answer (Click to Edit)', align: 'start', value: 'answer', width: '45%' },
        { text: 'Site', align: 'start', value: 'site_id', width: '10%' },
        { text: 'Action', value: 'delete', width: '10%' },
      ],
      faqs: [],
      faqsTableLoading: false,
      faqSearch: "",
      selectedSite: "",
      siteName: "",
      siteId: "",
      snackbar: false,
      snackbarText: "",
      timeout: 6000,
      deleteFaqDialog: false,
      selectedFaq: null,
      newFaqDialogue: false,
    };
  },
  mounted() {
    this.faqsTableLoading = true;
    this.fetchFaqs();
  },
  methods: {
    fetchFaqs() {
      axios.get("/api/faqs")
      .then((res) => {
        this.faqsTableLoading = false;
        this.faqs = res.data;
      })
      .catch((err) => {
        this.snackbar = true;
        this.snackbarText = err.response.data.message;
      });
    },
    save(item, id, type) {
      const faq = new FormData();
      if (type == 'question') {
        faq.append('question', item);
      }
      if (type == 'answer') {
        faq.append('answer', item);
      }
      faq.append('id', id);
      faq.append('_method', 'PUT');
      axios.post(`/api/faqs/${id}`, faq).then(res => {
        this.snackbar = true;
        this.snackbarText = res.data.message;
      })
        .catch(err => {
          this.snackbar = true;
          this.snackbarText = 'Error saving setting';
        });
    },
    toggleFaqVisibility(id, visibilityStatus) {
      const changeVisibility = new FormData();
      changeVisibility.append("visibility", visibilityStatus);
      changeVisibility.append("_method", "PUT");
      axios
        .post(`/api/editFaq/${id}`, changeVisibility)
        .then((res) => {
          this.fetchFaqs();
        })
        .catch((error) => {
          this.message = error;
        });
    },
    closeFormChange(state) {
      this.deleteFaqDialog = state;
    },
  },
};
</script>
