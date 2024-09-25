<template>
<div id="conditionModal" v-show="!isModalVisible" class="modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ dataArray.titreconcours }}</h4>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger" role="alert" style="width:100%;margin:auto; height:300px;overflow:auto;">
              <div v-html="dataArray.texteconcours"></div>
            </div>
          </div>
          <div class="modal-footer">
          <div class="button-container">

            <button type="button"  @click="acceptConditionsAndClose">
             {{ $t('modal.accept') }}
            </button>
             </div>
          </div>
        </div>
      </div>
    </div>
  <div> 


<div class="container">
 <div class="container-language" v-show="form.isVisibleLn">
     <select v-model="currentLocale" @change="changeLanguage">
      <option value="en">English</option>
      <option value="fr">Français</option>
      <option value="ar">عربي</option>
    </select>
  </div> 
 <div class="row text-center">
  <div class="col-xs-12">
    <a href="#" class="img-responsive">
      <img src="images/logo.png" alt="SONATRACH">
    </a>
  </div>
  <div class="col-xs-12">
    <h2 class="h2C">{{ dataArray.intituleconcours }}</h2>
    <h3 style="font-weight: bold; text-align:center">
      {{ dataArray.soustitre }}
    </h3>
  </div>
</div>
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10 col-sm-12">
        <div class="card shadow-sm">
          <div class="card-body">
            <div v-if="currentStep === 1">
              <PersonalInfo :form="form"  :data-array="dataArray" @next-step="nextStep" />
            </div>
            <div v-if="currentStep === 2">
              <DiplomaInfo :form="form" :data-array="dataArray"  @next-step="nextStep" @previous-step="previousStep" />
            </div>
            <div v-if="currentStep === 3">
            <FileUpload :form="form" :data-array="dataArray"  @next-step="nextStep"  @previous-step="previousStep"/>
            </div>
            <div v-if="currentStep === 4">
            <SuccessPage :form="form" />
            </div>
          </div>
        </div>
      </div>
    </div> 
  </div>
  </div>
</template>

<script>
import PersonalInfo from './PersonalInfo.vue';
import DiplomaInfo from './DiplomaInfo.vue';
import FileUpload from './FileUpload.vue';
import SuccessPage from './SuccessPage.vue';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

import axios from 'axios';

export default {
  components: {
    PersonalInfo,
    DiplomaInfo,
    FileUpload,
    SuccessPage
  
  },
   mounted() {
     console.log('i18n:', this.$i18n);
    this.fetchDataArray();
  },
  data() {
    return {
      currentLocale: 'fr',
      currentStep: 1,
      type: Object,
      form: {
        numinscritanem:'',
        nom: '',
        nom_ar:'',
        prenom: '',
        prenom_ar: '',
        adresse: '',
        email: '',
        phone: '',
        sexe: '',
        situation: '',
        datenaiss: '',
        c_city_id: '',
        hh_commune_id: '',
        confirmation_specialite: '',
        wilayaInstitut: '',
        communeInstitut: '',
        sys_universites_id: '',
        sys_diplomecon_id: '',
        sys_specdiplome_id: '',
        sys_speciliteform_id: '',
        confirm_sys_speciliteform_id: '',
        inganneune: 0,
        ingannedeux: 0,
        ingannetrois: 0,
        ingannequatre: 0,
        ingannecinq: 0,
        ingannesix: 0,
        ingannesept: 0,
        moyeng: 0,
        document: null,
        disabled: false,
        disabledInstitut: false,
        agree: false,
        agree_formation:false,
        confirm_phone:'',
        confirm_email:'',
        centeExamen:'',
       confirm_sys_speciliteform:false,
       isVisibleLn:false,
       recaptchaToken:''
      },
      dataArray: {
            titreconcours: '',
            texteconcours: '',
            intituleconcours: '',
            agrementtext: '',
            soustitre: '',
            with_note:'',
            plus_info:'N'
            
      },
      isModalVisible:false,
    };
  },
  methods: {
    acceptConditionsAndClose() {
      this.isModalVisible = true;
      this.form.isVisibleLn = true;
    },
    nextStep() {
      console.log(this.form);
      this.currentStep++;
    },
    previousStep() {
      this.currentStep--;
    },

    fetchDataArray() {
      axios.get('/api/data')
        .then(response => {
          if (response.data) {
            console.log(response.data);
            this.dataArray = response.data[0]; 
          } else {
            console.error('Unexpected data format for dataArray:', response.data);
          }
        })
        .catch(error => {
          console.error('Error fetching dataArray:', error);
        });
    },
  
  },
   setup() {
    const { t, locale } = useI18n();
    const currentLocale = ref(locale.value);

    const changeLanguage = () => {
      locale.value = currentLocale.value;
      if (currentLocale.value === 'ar') {
         document.documentElement.classList.toggle('rtl', true);
        } else {
        document.documentElement.classList.toggle('rtl', false);
        }
    };

    return {
      t,
      changeLanguage,
      currentLocale
    };
  }
};
</script>
