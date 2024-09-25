<template>
  <div class="panel panel-danger" style="margin:10px">
    <div class="panel-heading">
      <div class="panel-title pull-left">{{ $t('educationAndDegrees') }}</div>
      <br />
    </div>
    <div class="panel-body">
      <div  class="form-group" :class="{'has-error': errors.wilayaInstitut}">
        <label class="col-md-4 control-label">{{ $t('institutionWilaya') }}</label>
        <div class="col-md-4 selectContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
            <select id="wilayaInstitut" class="form-control selectpicker" v-model="form.wilayaInstitut" :disabled="wilayasInstitut.length === 0" @change="changeWilayasInstitut">
              <option value="">{{ $t('chooseWilaya') }}</option>
              <option v-for="wilaya in wilayasInstitut" :value="wilaya.c_city_id" :key="wilaya.c_city_id">
              {{ $i18n.locale === 'ar' ?  wilaya.name_ar :  wilaya.name }}
              </option>
            </select>
          </div>
          <span v-if="errors.wilayaInstitut" class="text-danger">{{ errors.wilayaInstitut }}</span>
        </div>
      </div>
      <div class="form-group" :class="{'has-error': errors.sys_universites_id}">
        <label class="col-md-4 control-label">{{ $t('trainingInstitution') }} <span style="color: red">*</span></label>
        <div class="col-md-4 selectContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <select class="form-control selectpicker" v-model="form.sys_universites_id" @change="changeUniversite" :disabled="universites.length === 0"  required>
              <option value="">{{ $t('chooseTraining') }}  </option>
              <option v-for="universite in universites" :value="universite.sys_universites_id" :key="universite.sys_universites_id">
              {{ $i18n.locale === 'ar' ?  universite.factulte :  universite.factulte }}
              </option>
            </select>
          </div>
          <span v-if="errors.sys_universites_id" class="text-danger">{{ errors.sys_universites_id }}</span>
        </div>
      </div>
   
    
      <div class="form-group" :class="{'has-error': errors.sys_diplomecon_id}">
        <label class="col-md-4 control-label"> {{ $t('diplomaType') }} <span style="color: red">*</span></label>
        <div class="col-md-4 selectContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <select id="selectedTypeDiplomes" class="form-control selectpicker"  :disabled="typeDiplomes.length === 0" v-model="form.sys_diplomecon_id" @change="getSpecialitesDiplome" required>
              <option value="">{{ $t('chooseDiploma') }}</option>
              <option v-for="type in typeDiplomes" :value="type.sys_diplomecon_id" :key="type.sys_diplomecon_id">
              {{ $i18n.locale === 'ar' ?  type.diplom:  type.diplom }}
              </option>
            </select>
          </div>
          <span v-if="errors.sys_diplomecon_id" class="text-danger">{{ errors.sys_diplomecon_id }}</span>
          <span v-if="errors.age" class="text-danger">{{ errors.age }}</span>
        </div>
        <div  v-if="select_diplome && select_diplome.is_texte==='Y'">{{select_diplome.texte_typedeplome}}</div>
      </div>

      <div class="form-group" :class="{'has-error': errors.sys_specdiplome_id}">
        <label class="col-md-4 control-label"> {{ $t('specialty') }} <span style="color: red">*</span></label>
        <div class="col-md-4 selectContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <select id="selectedSpecialiteDiplome" class="form-control selectpicker" :disabled="specialitesDiplome.length === 0"  v-model="form.sys_specdiplome_id" @change="getFormationAutorisees" required>
              <option value="">{{ $t('chooseSpecialty') }} </option>
              <option v-for="specialite in specialitesDiplome" :value="specialite.sys_specdiplome_id" :key="specialite.sys_specdiplome_id">
              {{ $i18n.locale === 'ar' ?  specialite.specdiplome :  specialite.specdiplome}}
              </option>
            </select>
          </div>
          <span v-if="errors.sys_specdiplome_id" class="text-danger">{{ errors.sys_specdiplome_id }}</span>
        </div>
      </div>
      <div class="form-group" :class="{'has-error': errors.confirmation_specialite}">
        <label class="col-md-4 control-label">{{ $t('institution') }} </label>
        <div class="col-md-4 inputGroupContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input id="confirmation_specialite" class="form-control" type="text" v-model="form.confirmation_specialite" />
          </div>
          <span  v-if="errors.invalid_institution" class="text-danger">{{ errors.invalid_institution }}</span>
        </div>
      </div>
      <div class="form-group">
               <div class="col-md-4 col-md-offset-4">
                 <div class="checkbox-container">
             <input
                 type="checkbox"
                 v-model="form.agree_formation"
                 id="agree_formation"
                 required
                 />
              <label for="agree_formation">{{ $t('agree_formation') }} 
                <template v-if="select_diplome && select_diplome.diplom && select_specdiplome">
                    {{ select_diplome.diplom }} - {{ select_specdiplome.specdiplome }}
             </template>
              </label>   
            </div>
          </div>
         </div>
         <span v-if="errors.agree_formation" class="text-danger">    {{ $t('errors.agree_formation_required') }} </span>
      <div class="form-group" :class="{'has-error': errors.sys_speciliteform_id}">
        <label class="col-md-4 control-label">{{ $t('positionToCompete') }} <span style="color: red">*</span></label>
        <div class="col-md-4 selectContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <select id="selectedFormationAutorisee" class="form-control selectpicker"  :disabled="formationAutorisees.length === 0" v-model="form.sys_speciliteform_id" required>
              <option value="">{{ $t('chooseFormation') }} </option>
              <option v-for="formation in formationAutorisees" :value="formation.sys_speciliteform_id" :key="formation.sys_speciliteform_id">
              {{ $i18n.locale === 'ar' ?  formation.speciliteform :  formation.speciliteform }}
              </option>
            </select>
          </div>
          <span v-if="!form.sys_speciliteform_id && errors.sys_speciliteform_id" class="text-danger">{{ errors.sys_speciliteform_id }}</span>
          <p v-if="isFormationAutorisees" class="text-danger">{{ $t('noMatchingFormation') }} </p>
        </div>
      </div>


      <div class="form-group" :class="{'has-error': errors.confirm_sys_speciliteform_id}">
        <label class="col-md-4 control-label">{{ $t('confirm_positionToCompete') }} <span style="color: red">*</span></label>
        <div class="col-md-4 selectContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <select  class="form-control selectpicker"  :disabled="formationAutorisees.length === 0" v-model="form.confirm_sys_speciliteform_id" required>
              <option value="">{{ $t('chooseFormation') }} </option>
              <option v-for="formation in conf_formationAutorisees" :value="formation.sys_speciliteform_id" :key="formation.sys_speciliteform_id">
                {{ $i18n.locale === 'ar' ?  formation.speciliteform :  formation.speciliteform }}
              </option>
            </select>
          </div>
          <span v-if="errors.confirm_sys_speciliteform_id" class="text-danger">{{ errors.confirm_sys_speciliteform_id }}</span>
        </div>
      </div>

          <div class="form-group">
               <div class="col-md-4 col-md-offset-4">
                 <div class="checkbox-container">
             <input
                 type="checkbox"
                 v-model="form.confirm_sys_speciliteform"
                 id="agree_specilite"
                 required
                 />
              <label for="agree_specilite" class="text-danger">{{ $t('agree_speciallite') }}</label>   
            </div>
          </div>
         </div>

    <span v-if="errors.valide_sys_speciliteform" class="text-danger">{{ errors.valide_sys_speciliteform }}</span>

      <div v-if="dataArray.with_note ==='Y'"  class="form-group" v-for="(annee, index) in annees" :key="index" :class="{'has-error': errors[`annee${index + 1}`]}">
        <label :for="'annee' + (index + 1)" class="col-md-4 control-label">{{ index + 1 }}ère année</label>
        <div class="col-md-4 inputGroupContainer">
          <div class="input-group">
            <input 
              :id="'annee' + (index + 1)" 
              class="form-control" 
              type="number" 
              placeholder="00.00" 
              step="0.01" 
              min="0" 
              max="20" 
              v-model.number="annees[index]" 
              @change="calculeMoyenne"
            >
          </div>
          <span v-if="errors[`annee${index + 1}`]" class="text-danger">{{ errors[`annee${index + 1}`] }}</span>
        </div>
      </div>

      <div v-if="dataArray.with_note ==='Y'" class="form-group" :class="{'has-error': errors.moyeng}">
        <label class="col-md-4 control-label">{{ $t('generalAverage') }}</label>
        <div class="col-md-4 inputGroupContainer">
          <div class="input-group">
            <span class="input-group"><i class="glyphicon glyphicon-user"></i></span>
            <input id="moyeng" class="form-control" v-model="form.moyeng" disabled>
          </div>
          <span v-if="errors.moyeng" class="text-danger">{{ errors.moyeng }}</span>
        </div>
      </div>

     <div class="form-group" :class="{'has-error': errors.document}">
        <label>Joindre un fichier :</label>
        <input type="file" @change="handleFileUpload" />
        <span v-if="errors.document" class="text-danger">{{ errors.document }}</span>
      </div>
</div>

   
       <fieldset class="alert alert-danger" style="background-color: #e8e8e8;">
       <div v-if="dataArray.agrementtext" v-html="dataArray.agrementtext"></div>
       <div v-else>Aucun texte d'agrément disponible</div>
     </fieldset>

     <div class="form-row">
        <div class="form-group">
               <div class="col-md-4 col-md-offset-4">
                 <div class="checkbox-container">
             <input
                 type="checkbox"
                 v-model="form.agree"
                 id="agree"
                 required
                 />
              <label for="agree">{{ $t('read_and_accept') }}</label>   
            </div>
          </div>
          <div class="form-group">
            <div class="g-recaptcha" :data-sitekey="siteKey" @verify="onCaptchaVerified"></div>
          </div>
          <span v-if="errors.valide_captcha" class="text-danger">{{ $t('errors.valide_captcha') }} </span>     
         </div>

     <span v-if="errors.agree" class="text-danger">    {{ $t('errors.agreement_required') }} </span>
    <span v-if="errors.general" class="text-danger">{{ errors.general }}</span>
    <span v-for="errorMessage in errorMessages" :key="errorMessage" class="text-danger">{{ errorMessage[0] }}</span>
   </div>
     
    <!-- Ajouter les autres champs ici -->
         <div class="button-container">
           <button :disabled="isVisible" @click="$emit('previous-step')">{{ $t('previous') }}</button>
         <button @click="validateForm" :disabled="isVisible">
           <span v-if="isVisible" class="loader"></span>
           <span v-else>{{ $t('save_and_submit') }}</span>
         </button>
       
          </div>
        
  </div>
</template>

<script>
import axios from 'axios';
export default {
    props: ['form', 'dataArray'],

  data() {
  return {
    siteKey: '6LdNPjkqAAAAAG74nl-IYFPhqNwptzh5rq82_qK1', 
    isCaptchaVerified: false,
    wilayasInstitut: [],
    communesInstitut: [],
    universites: [],
    typeDiplomes: [],
    specialitesDiplome: [],
    formationAutorisees: [], 
    conf_formationAutorisees: [], // Ajouter les universités ici
    errors: {},
    errorMessages: {},
    selectedDiplome: null,
    annees: [],
    file:null,
    isFormationAutorisees:false,
    isVisible:false,
    submitted:"",
    age: null,
    max_age1:0,
    sys_specdiplome_id:null,
    select_diplome:null,
    select_specdiplome:null
  };
},
mounted() {
  const wilayas = localStorage.getItem('wilayasInstitut');
  const typeDiplomes = localStorage.getItem('typeDiplomes');




    if (wilayas) {
    this.wilayasInstitut = JSON.parse(wilayas);
      const universites = localStorage.getItem('universites');
      this.universites = JSON.parse(universites);
    } else {
    this.getWilayas(); 
  }
  if (typeDiplomes) {
    this.typeDiplomes = JSON.parse(typeDiplomes);
  
      const specialitesDiplome = localStorage.getItem('specialitesDiplome');
       this.specialitesDiplome = JSON.parse(specialitesDiplome);

       const select_diplome =JSON.parse(localStorage.getItem('select_diplome'));
       const select_specdiplome = JSON.parse(localStorage.getItem('select_specdiplome'));

       this.select_diplome  = select_diplome;
       this.select_specdiplome = select_specdiplome;
       
  if (specialitesDiplome) {
      const formationAutorisees = localStorage.getItem('formationAutorisees');
      const confformationAutorisees = localStorage.getItem('conf_formationAutorisees');
      this.formationAutorisees = JSON.parse(formationAutorisees);
      this.conf_formationAutorisees = JSON.parse(confformationAutorisees);
      }
     } else {
    this.getTypeDiplomes(); 
  }


  window.grecaptcha.ready(() => {
      window.grecaptcha.render(this.$el.querySelector('.g-recaptcha'), {
        sitekey: this.siteKey,
        callback: this.onCaptchaVerified,
      });
    });
},
  methods: { 
    onCaptchaVerified(token) {
      this.isCaptchaVerified = true;
      console.log("Captcha vérifié avec le token : ", token);
      this.form.recaptchaToken = token;
    },
     validateForm() {
  this.errors = {}; // Reset errors

  if (!this.form.sys_universites_id) {
    this.errors.sys_universites_id = this.$t('errors.university_required');
  }
  if (!this.form.sys_diplomecon_id) {
    this.errors.sys_diplomecon_id = this.$t('errors.diploma_type_required');
  }
  if (!this.form.sys_specdiplome_id) {
    this.errors.sys_specdiplome_id = this.$t('errors.specialty_required');
  }
  if (!this.form.sys_speciliteform_id) {
    this.errors.sys_speciliteform_id = this.$t('errors.training_required');
  }

  if (!this.form.confirm_sys_speciliteform_id) {
    this.errors.confirm_sys_speciliteform_id = this.$t('errors.confirm_sys_speciliteform_id');
  } else if ( this.form.confirm_sys_speciliteform_id != this.form.sys_speciliteform_id) {
    this.errors.confirm_sys_speciliteform_id = this.$t('errors.confirm_equel_sys_speciliteform_id');
  }
  
  if (!this.form.confirm_sys_speciliteform) {
    this.errors.valide_sys_speciliteform = this.$t('errors.valide_sys_speciliteform');
  }
  
  if(this.dataArray.with_note ==='Y') {
     if (this.annees.some(annee => !annee)) {
       this.errors[`annee${this.annees.indexOf(null) + 1}`] = this.$t('errors.all_years_required');
      }
      if (!this.form.moyeng) {
        this.errors.moyeng = this.$t('errors.average_required');
       }
  }
  console.log(this.specialitesDiplome);
  if (!this.form.confirmation_specialite) {
    this.errors.invalid_institution = this.$t('errors.invalid_institution');
    console.log(this.specialitesDiplome.specdiplome);
  } else if (this.form.confirmation_specialite != this.select_specdiplome.specdiplome) {
    this.errors.invalid_institution = this.$t('errors.institution_mismatch');
  }
  



  
  if (!this.form.document) {
    this.errors.document = this.$t('errors.file_required');
  } else {
     const maxSize = 15 * 1024 * 1024; // Taille maximale de 15 Mo
    if (this.form.document.size > maxSize) {
      this.errors.document = this.$t('errors.file_size_exceeded');
    }
  }
  if (!this.form.agree) {
    this.errors.agree = this.$t('errors.agreement_required');
  } 
  if (!this.form.agree_formation) {
    this.errors.agree_formation = this.$t('errors.agree_formation_required');
  }
  if (!this.isCaptchaVerified) {
    this.errors.valide_captcha = this.$t('errors.valide_captcha');
  }

  if (Object.keys(this.errors).length === 0) {
    // No errors, proceed to next step
    this.submitForm();
  }
},

    calculeMoyenne() {
      let total = 0;
      let count = 0;
      this.annees.forEach(annee => {
        if (annee !== null) {
        switch (count) {
          case 0:
            this.form.inganneune = annee;
            break;
          case 1:
            this.form.ingannedeux = annee;
            break;
          case 2:
            this.form.ingannetrois = annee;
            break;
          case 3:
            this.form.ingannequatre = annee;
            break;
          case 4:
            this.form.ingannecinq = annee;
            break;
          case 5:
            this.form.ingannesix = annee;
            break;
          case 6:
            this.form.ingannesept = annee;
            break;
          default:
            break;
        }
 
          total += annee;
          count++;
        }
      });
      this.form.moyeng = (count > 0) ? (total / count).toFixed(2) : '';
    },
  handleFileUpload(event) {
   this.file = event.target.files[0];
   this.form.document =  this.file;
   },

  
      getUniversites() {
      axios.get('api/universites')
        .then(response => {
          this.universites = response.data;
          // Stocker dans localStorage
          localStorage.setItem('universites', JSON.stringify(this.universites));
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des universités:', error);
        });
    },

     changeUniversite() {
        console.log('Université sélectionnée :', this.form.sys_universites_id);
     },
      getWilayas() {
      axios.get('api/wilayas')
        .then(response => {
          this.wilayasInstitut = response.data; // Stocke les wilayas récupérées dans l'état local
          // Stocker dans localStorage
          localStorage.setItem('wilayasInstitut', JSON.stringify(this.wilayasInstitut));
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des wilayas:', error);
        });
    },

    changeWilayasInstitut() {
      console.log('Changement détecté');

      if (this.form.wilayaInstitut) {
        axios.get('api/universites', {
          params: { wilayaId: this.form.wilayaInstitut }
        })
        .then(response => {
          console.log(response.data);
          this.universites = response.data;
          // Stocker dans localStorage
          localStorage.setItem('universites', JSON.stringify(this.universites));
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des communes:', error);
        });
      } else {
        this.universites = [];
        console.error('Erreur  :', this.form.c_city_id);
      }
    },
   getTypeDiplomes() {
      axios.get('api/typediplomes')
        .then(response => {
          this.typeDiplomes = response.data;
          console.log(response.data);
          // Stocker dans localStorage
          localStorage.setItem('typeDiplomes', JSON.stringify(this.typeDiplomes));
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des types de diplômes:', error);
        });
    },
    // Changement du type de diplôme
    getSpecialitesDiplome() {
      if (this.form.sys_diplomecon_id) {
        this.select_diplome = this.typeDiplomes.find(type => type.sys_diplomecon_id === this.form.sys_diplomecon_id);
        console.log(this.select_diplome);
        localStorage.setItem('select_diplome', JSON.stringify(this.select_diplome));
        axios.get('api/specialitesdiplome', {
          params: { sys_diplomecon_id: this.form.sys_diplomecon_id }
        })
        .then(response => {
          this.specialitesDiplome = response.data;
          console.log(response.data);
          // Stocker dans localStorage
          localStorage.setItem('specialitesDiplome', JSON.stringify(this.specialitesDiplome));
          this.onDiplomeChange();
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des spécialités du diplôme:', error);
        });
      } else {
        this.specialitesDiplome = []; // Réinitialiser la liste si aucun type de diplôme n'est sélectionné
        console.warn('Aucun type de diplôme sélectionné.');
      }
    },

   getFormationAutorisees() {
     if (this.form.sys_specdiplome_id && this.form.c_city_id) {
      this.select_specdiplome = this.$i18n.locale === 'ar'
              ? this.specialitesDiplome.find(type => type.sys_specdiplome_id === this.form.sys_specdiplome_id)
              : this.specialitesDiplome.find(type => type.sys_specdiplome_id === this.form.sys_specdiplome_id);
      console.log(this.select_specdiplome);
       localStorage.setItem('select_specdiplome', JSON.stringify(this.select_specdiplome));
       axios.get('api/formationautorisees', {
       params: {
        sys_specdiplome_id: this.form.sys_specdiplome_id,
        c_city_id: this.form.wilayaInstitut
      } 
      })
     .then(response => {
        console.log(response.data);
      

      this.formationAutorisees = response.data;
      this.conf_formationAutorisees = response.data;
      localStorage.setItem('formationAutorisees', JSON.stringify(this.formationAutorisees));
      localStorage.setItem('conf_formationAutorisees', JSON.stringify(this.conf_formationAutorisees));
      if(this.formationAutorisees.length == 0){
         this.isFormationAutorisees = true;
      }else {
        this.isFormationAutorisees = false;
      }
      })
      .catch(error => {
      console.error('Erreur lors de la récupération des formations autorisées:', error);
      });
     } else {
      this.formationAutorisees = []; // Réinitialiser la liste si les paramètres sont absents
       console.warn('Les paramètres sys_specdiplome_id ou c_city_id sont manquants.');
    }
   },
   onDiplomeChange() {
     this.selectedDiplome = this.typeDiplomes.find(type => type.sys_diplomecon_id === this.form.sys_diplomecon_id);
     console.log(this.selectedDiplome);
     this.max_age1 = this.selectedDiplome.max_age1;
      console.log(this.selectedDiplome.max_age1);
      this.annees = Array(this.selectedDiplome.nbr_annee || 0).fill(0);
      if (this.dataArray.with_note ==='Y') {
            this.calculeMoyenne();
      }
      this.calculateAge();
    
  },

 submitForm() {
   
  console.log("submitForm");
  console.log(this.form);
   this.isVisible = true;
  axios.post('/api/enregistrement', this.form, {
    headers: {
      'Content-Type': 'multipart/form-data', 
    }
  })
  .then(response => {
    console.log(response.data);
       this.$emit('nextStep');
       this.form.isVisibleLn = false;
    console.log('Form submitted successfully');
    this.errorMessages = {};
  })
  .catch(error => {
   this.isVisible = false;
    if (error.response && error.response.data && error.response.data.errors) {
      this.errorMessages = error.response.data.errors;
    } else {
      this.errors.general = this.$t('submission_error');    
    }
    console.error('There was an error submitting the form:', error);
  });
},

 calculateAge() {
      const today = new Date();
      const birthDate = new Date(this.form.datenaiss);
      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDifference = today.getMonth() - birthDate.getMonth();
      // Si le mois de naissance est après le mois actuel ou le mois est le même mais le jour de naissance est après le jour actuel
      if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      } 
      this.age = age;      
      if (this.age > this.max_age1) {
       this.errors.age = this.$t('errors.confirm_age') + this.max_age1 ;
      } else {
        this.errors.age = '';
      }
    }

},
};
</script>