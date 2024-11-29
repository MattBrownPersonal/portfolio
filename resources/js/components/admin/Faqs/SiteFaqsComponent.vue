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
          <UsefulLinksComponent v-bind:site="site" />
      </v-col>
      <v-col class="col-10">
        <v-card>
          <v-card-title>
            {{ (this.$route.params.id == 0) ? 'Global FAQs' : 'FAQs By Site' }}
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
            <template v-slot:[`item.action`]="{ item }">
              <div v-if="item.site_id">
                <v-btn @click="toggleFaqVisibility(item.id, 1)" v-if="item.hidden == 0" color="error" small>Hide FAQ
                </v-btn>
                <v-btn @click="toggleFaqVisibility(item.id, 0)" v-else color="primary" small>Show FAQ</v-btn>
              </div>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
      <v-snackbar
        v-model="snackbar"
        :timeout="timeout"
      >
      {{ snackbarText }}

      <template v-slot:action="{ attrs }">
        <v-btn
          color="blue"
          text
          v-bind="attrs"
          @click="snackbar = false"
        >
          Close
        </v-btn>
      </template>
      </v-snackbar>
      <NewFaqArticle
        v-bind:newFaqDialogue="newFaqDialogue"
        v-bind:site = site
        v-on:fetchSiteFaqs="fetchFaqs()"
        v-on:closeForm="newFaqDialogue = false"
      />
  </div>
</template>
<script>
import NewFaqArticle from './modals/NewFaqModalComponent.vue'
import UsefulLinksComponent from '../UsefulLinksComponent.vue'
export default ({
  components: {
    NewFaqArticle,
    UsefulLinksComponent,
  },
  data () {
    return {
      faqHeaders: [
        { text: 'Question (Click to Edit)', align: 'start', value: 'question', width: '20%'},
        { text: 'Answer (Click to Edit)', align: 'start', value: 'answer', width: '70%'},
        { text: 'Action', align: 'start', value: 'action', width: '10%'},
      ],
      faqs: [],
      faqsTableLoading: false,
      faqSearch: '',
      selectedFaq: '',
      faqName: '',
      faqId: '',
      snackbar: false,
      snackbarText: '',
      timeout: 6000,
      newFaqDialogue: false,
      site: null
    }
  },
  mounted () {
    this.faqsTableLoading = true;
    this.fetchFaqs();
    this.fetchStyleName();
  },
  methods: {
    fetchFaqs() {
      axios.get(`/api/faqs/${this.$route.params.id}`)
      .then(res => {
        this.faqsTableLoading = false;       
        this.faqs = res.data.filter(faq => {
          return (this.$route.params.id == 0) || (this.$route.params.id && faq.hidden == 0);
        });
        })
        .catch(err => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    },
    fetchStyleName() {
      axios.get(`/api/sites/${this.$route.params.id}`)
        .then(res => {
          this.site = res.data.site;
        })
        .catch(err => {
          this.snackbar = true;
          this.snackbarText = err.response.data.message;
        });
    }, 
    save(item, id, type) {            
      const faq = new FormData();
      if( type == 'question') {
        faq.append('question', item);
      }
      if( type == 'answer') {
        faq.append('answer', item);
      }
      faq.append('id', id);
      faq.append('_method', 'PUT'); 
      axios.post(`/api/faqs/${id}`, faq ).then(res => {
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
  }
})
</script>