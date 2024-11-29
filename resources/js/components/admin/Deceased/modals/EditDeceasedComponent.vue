<template>
    <v-dialog
      v-model="editDeceasedDialog"
      max-width="800px"
      persistent
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">Edit</span>
            </v-card-title>
            <v-alert type="error" class="mx-3" v-model="warningMessage" v-if="warningMessage !== null">
                {{ warningMessage }}
            </v-alert>
            <v-form ref="form" v-model="valid">
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col
                            cols="12"
                            sm="4"
                            >
                            <v-text-field
                                label="First Name*"
                                v-model='firstname'
                                :rules="nameRules"
                                required
                            ></v-text-field>
                            </v-col>
                             <v-col
                            cols="12"
                            sm="4"
                            >
                            <v-text-field
                                label="Middle Name(s)"
                                v-model='middlename'
                            ></v-text-field>
                            </v-col>
                             <v-col
                            cols="12"
                            sm="4"
                            >
                            <v-text-field
                                label="Last Name*"
                                v-model='lastname'
                                :rules="nameRules"
                                required
                            ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="4"                          
                                >
                                    <v-menu
                                        ref="menu"
                                        v-model="dob"
                                        :close-on-content-click="false"                                  
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="date_of_birth_formatted"
                                            label="Date of Birth"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                        v-model="date_of_birth"
                                        no-title
                                        scrollable                                    
                                        min="1800-01-01"
                                        :max="moment().format('YYYY-MM-DD')"
                                        >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="dob = false"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.menu.save(date_of_birth), dob = false"
                                        >
                                            OK
                                        </v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col
                                cols="12"
                                sm="4"
                                >
                                    <v-menu
                                        ref="menu"
                                        v-model="dod"
                                        :close-on-content-click="false"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="date_of_death_formatted"
                                            label="Date of Death*"
                                            prepend-icon="mdi-calendar"
                                            readonly                                       
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                        v-model="date_of_death"
                                        no-title
                                        scrollable
                                        :min="date_of_birth"
                                        :max="moment().format('YYYY-MM-DD')"
                                        >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="dod = false"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.menu.save(date_of_death), dod = false"
                                        >
                                            OK
                                        </v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col
                                cols="12"
                                sm="4"                          
                                >
                                    <v-menu
                                        ref="menu"
                                        v-model="dos"
                                        :close-on-content-click="false"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="date_of_service_formatted"
                                            label="Date of Service"
                                            prepend-icon="mdi-calendar"
                                            readonly                                        
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                        v-model="date_of_service"
                                        no-title
                                        scrollable
                                        :min="date_of_death"
                                        >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="dos = false"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.menu.save(date_of_service), dos = false"
                                        >
                                            OK
                                        </v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="12" sm="4">
                                    <v-menu 
                                        ref="menu" 
                                        v-model="doc" 
                                        :close-on-content-click="false" 
                                        transition="scale-transition" offset-y min-width="auto">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field 
                                                v-model="date_of_cremation_formatted" 
                                                label="Date of Cremation*" 
                                                prepend-icon="mdi-calendar" 
                                                readonly
                                                v-bind="attrs" 
                                                v-on="on"
                                                ></v-text-field>
                                        </template>
                                        <v-date-picker 
                                            v-model="date_of_cremation" 
                                            no-title 
                                            scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn 
                                                text 
                                                color="primary" 
                                                @click="doc = false">
                                                Cancel
                                            </v-btn>
                                            <v-btn 
                                                text 
                                                color="primary" 
                                                @click="$refs.menu.save(date_of_cremation), doc = false">
                                                OK
                                            </v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                            <v-col cols="12" sm="4">
                                <v-text-field label="Funeral Director" v-model='funeralDirector'></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <v-text-field label="Cremation Number" v-model='cremNumber' required></v-text-field>
                            </v-col>
                            <v-col
                            cols="12"
                            sm="12"
                            >
                            <v-select
                                :items="allSites"
                                label="Site*"                                
                                v-model='site_id'
                                item-text="name"
                                item-value="id"
                                :rules="[v => !!v || 'Item is required']"
                                required
                            ></v-select>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col
                            cols="12"
                            sm="12"
                            class="text-center"
                            >
                            <h4>Contact Details</h4>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col
                            cols="12"
                            sm="4"
                            >
                            <v-text-field
                                label="First Name*"
                                v-model='contact_firstname'
                                :rules="nameRules"
                                required
                            ></v-text-field>
                            </v-col>
                            <v-col
                            cols="12"
                            sm="4"
                            >
                            <v-text-field
                                label="Last Name*"
                                v-model='contact_lastname'
                                :rules="nameRules"
                                required
                            ></v-text-field>
                            </v-col>
                            <v-col
                            cols="12"
                            sm="4"
                            >
                            <v-text-field
                                label="Email Address"
                                v-model='contact_email'
                            ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="close()"
                    >
                    Close
                    </v-btn>
                    <v-btn
                    color="blue darken-1"
                    text
                    @click="sendDeceasedDetails()"
                    >
                    Save
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>
import moment from 'moment';
export default ({
    props: ['editDeceasedDialog', 'selectedDeceased'],
    data: function() {
        return {
            warningMessage: null,
            allSites: [],
            date_of_birth: "",
            date_of_death: "",
            date_of_service: "",
            date_of_cremation: "",
            dod: false,
            dob: false,
            dos: false,
            doc: false,
            firstname: '',
            middlename: '',
            lastname: '',
            funeralDirector: '',
            cremNumber: '',
            date_of_birth_formatted: "",
            date_of_death_formatted: "",
            date_of_service_formatted: "",
            date_of_cremation_formatted: "",
            contact_firstname: null,
            contact_lastname: null,
            contact_email: null,
            site_id: '',
            errorMessages: '',
            nameRules: [
                v => !!v || 'Name is required',
                v => (v && v.length <= 255) || 'Name must be less than 255 characters',
            ],
            dateOfBirthRules: [
                v => !!v || 'Date of birth is required',
            ],
            dateOfDeathRules: [
                v => !!v || 'Date of death is required',
                v => moment(v,'DD/MM/YYYY').toDate() >= moment(this.date_of_birth).toDate() || 'Date of death must be the same or after date of birth',
            ],
            dateOfServiceRules: [
                v => moment(v,'DD/MM/YYYY').toDate() >= moment(this.date_of_death).toDate() || 'Date of service must be the same or after date of death',
            ],
            valid: true, 
        }
    },
    methods: {
        sendDeceasedDetails(){
          if (this.$refs.form.validate()) {
            const deceasedPerson = new FormData();
            deceasedPerson.append('firstname', this.firstname);
            deceasedPerson.append('middlename', this.middlename ?? '');
            deceasedPerson.append('lastname', this.lastname);
            deceasedPerson.append('contact_firstname', this.contact_firstname ?? '');
            deceasedPerson.append('contact_lastname', this.contact_lastname ?? '');
            deceasedPerson.append('contact_email', this.contact_email);
            deceasedPerson.append('date_of_death', this.date_of_death);
            deceasedPerson.append('date_of_birth', this.date_of_birth);
            deceasedPerson.append('date_of_service', this.date_of_service);
            deceasedPerson.append('selectedSite', this.site_id);
            deceasedPerson.append('date_of_cremation', this.date_of_cremation);
            deceasedPerson.append('funeralDirector', this.funeralDirector ?? '');
            deceasedPerson.append('cremNumber', this.cremNumber ?? '');
            deceasedPerson.append('_method', 'PUT');
            axios.post(`/api/deceased/${this.selectedDeceased.id}`, deceasedPerson)
            .then(res => {
                this.close();
                this.$emit('fetchSingleDeceased');
                this.$emit('triggerSnackBar', res.data.message); 
            })
            .catch(err => {
                this.warningMessage = false;
            });
          }
        },
        fetchAllSites() {
            axios.get('/api/sites')
            .then(res => {
                this.allSites = res.data;
            })
            .catch(err => {
                this.errorMessages = err.response.data.message;
            });
        },
        close() {
            this.$emit('closeForm', false)
        },
    },
    mounted() {
        this.fetchAllSites()
    },
    watch: {
        selectedDeceased: function (propVal) {
            this.date_of_death = propVal.date_of_death || ''
            this.date_of_birth = propVal.date_of_birth || ''
            this.date_of_service = propVal.date_of_service || ''
            this.date_of_cremation = propVal.date_of_cremation || ''
            
            this.cremNumber = propVal.cremation_number || ''
            this.funeralDirector = propVal.funeral_director || ''
            this.firstname = propVal.firstname || ''
            this.middlename = propVal.middlename || ''
            this.lastname = propVal.lastname || ''
            this.site_id = propVal.site_id || ''
            this.contact_firstname = propVal.contact_firstname || ''
            this.contact_lastname = propVal.contact_lastname || ''
            this.contact_email = propVal.contact_email || ''
        },
        date_of_birth(val) {
            if (val) {
                this.date_of_birth_formatted = new Date(val).toLocaleDateString('en-GB');
            } else {
                this.date_of_birth_formatted = "";
            }
        },
        date_of_death(val) {
            this.date_of_death_formatted = new Date(val).toLocaleDateString('en-GB');      
        },
        date_of_service(val) {
            this.date_of_service_formatted = new Date(val).toLocaleDateString('en-GB');       
        },
        date_of_cremation(val) {
            if (val) {
                this.date_of_cremation_formatted = new Date(val).toLocaleDateString('en-GB');
            } else {
                this.date_of_cremation_formatted = "";
            }
        },
    },
    computed: {
      form () {
        return {
          name: this.name,
          dob: this.date_of_birth,
          dod: this.date_of_death,
          dos: this.date_of_service,
          site: this.site_id
        }
      }
    },
})
</script>
