<template>
  <div class="panel panel-danger" style="margin:10px">
    <div class="panel-heading">
      <div class="panel-title ">{{ $t('personalInfo') }}</div>
      <br />
    </div>
    <div class="panel-body">
      <!-- Numéro d'inscription ANEM -->
      <div class="form-row">
        <div class="form-group">
        <label class="col-md-4 control-label">
          {{ $t('anemNumber') }} <span style="color: red">*</span>
        </label>
        <div class="col-md-4 inputGroupContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input 
              name="numinscritanem" 
              :placeholder="$t('anemNumber')" 
              id="numanem" 
              class="form-control" 
              type="text"  
              pattern="[0-9]*"  
              v-model="form.numinscritanem" 
              @input="setWilayaByAnemNumber"
              required 
            />
          </div>
          <span v-if="errors.numinscritanem" class="text-danger">{{ errors.numinscritanem }}</span>
        </div>
      </div>
      
      <div class="form-group">
        <label class="col-md-4 control-label">
          {{ $t('residence_wilaya') }} <span style="color: red">*</span>
        </label>
        <div class="col-md-4 selectContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
            <select 
              class="form-control selectpicker" 
              v-model="form.c_city_id" 
              @change="changeWilaya" 
              id="wilaya" 
              :disabled="wilayas.length === 0" 
              required 
            >
              <option value="">{{ $t('choose_wilaya') }}</option>
              <option v-for="wilaya in wilayas" :key="wilaya.c_city_id" :value="wilaya.c_city_id">
              {{ $i18n.locale === 'ar' ? wilaya.name_ar : wilaya.name }}
              </option>
            </select>
            <span v-if="errors.wilaya" class="text-danger">{{ errors.wilaya }}</span>
          </div>
        </div>
      </div>
       
      </div>
      <div v-if="dataArray.plus_info ==='Y'" class="form-group">
        <label class="col-md-4 control-label">
          {{ $t('residence_commune') }} <span style="color: red">*</span>
        </label>
        <div class="col-md-4 selectContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
            <select 
              class="form-control selectpicker" 
              v-model="form.hh_commune_id" 
              :disabled="wilayas.length === 0" 
              required 
            >
              <option value="">{{ $t('choose_commune') }}</option>
              <option v-for="commune in communes" :key="commune.hh_commune_id" :value="commune.hh_commune_id">
                {{ $i18n.locale === 'ar' ?  commune.hh_commune_libelle_ar :  commune.hh_commune_libelle }}
              </option>
            </select>
            <span v-if="errors.commune" class="text-danger">{{ errors.commune }}</span>
          </div>
        </div>
      </div>

      <div v-if="dataArray.plus_info ==='Y'" class="form-group">
        <label class="col-md-4 control-label">
          {{ $t('personal_address') }} <span style="color: red">*</span>
        </label>
        <div class="col-md-4 inputGroupContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
            <input 
              name="adresse" 
                :placeholder="$t('address_placeholder')" 
              class="form-control" 
              type="text" 
              v-model="form.adresse" 
              required 
            />
            <span v-if="errors.adresse" class="text-danger">{{ errors.adresse }}</span>
          </div>
        </div>
      </div>

<div class="form-row">
  <div class="form-group">
    <label for="nom">{{ $t('lastName') }} <span style="color: red">*</span></label>
    <input name="nom" :placeholder="$t('lastName')" class="form-control fr" type="text" v-model="form.nom" id="nom" required />
    <span v-if="errors.nom" class="text-danger">{{ errors.nom }}</span>
  </div>
  
  <div class="form-group">
    <label for="nom_ar" class="ar">{{ $t('lastName_ar') }} <span style="color: red">*</span></label>
    <input name="nom_ar" :placeholder="$t('lastName_ar')" class="form-control ar" type="text" v-model="form.nom_ar" id="nom_ar" required />
    <span v-if="errors.nom_ar" class="text-danger">{{ errors.nom_ar }}</span>
  </div>
</div>

<!-- Prénom et Prénom en Arabe sur la même ligne -->
<div class="form-row">
  <div class="form-group">
    <label for="prenom">{{ $t('firstName') }} <span style="color: red">*</span></label>
    <input name="prenom" :placeholder="$t('firstName')" class="form-control" type="text" v-model="form.prenom" id="prenom" required />
    <span v-if="errors.prenom" class="text-danger">{{ errors.prenom }}</span>
  </div>
  
  <div class="form-group">
    <label for="prenom_ar" class="ar">{{ $t('firstName_ar') }} <span style="color: red">*</span></label>
    <input name="prenom_ar" :placeholder="$t('firstName_ar')" class="form-control ar" type="text" v-model="form.prenom_ar" id="prenom_ar" required />
    <span v-if="errors.prenom_ar" class="text-danger">{{ errors.prenom_ar }}</span>
  </div>
</div>
      <!-- Date de naissance -->
      <div class="form-group">
        <label class="col-md-4 control-label">
          {{ $t('dateOfBirth') }} <span style="color: red">*</span>
        </label>
        <div class="col-md-4 inputGroupContainer">
          <div class="input-group">
            <span class="input-group-addon"></span>
            <input type="date" v-model="form.datenaiss" class="form-control" />
          </div>
          <span v-if="errors.datenaiss" class="text-danger">{{ errors.datenaiss }}</span>
        </div>
      </div>

      <!-- Sexe -->
      <div class="form-group">
        <label class="col-md-4 control-label">
          {{ $t('gender') }} <span style="color: red">*</span>
        </label>
        <div class="col-md-4 inputGroupContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <div class="test">
              <label class="radio-inline">
                <input 
                  type="radio" 
                  name="sexe" 
                  v-model="form.sexe" 
                  value="M" 
                  required 
                /> {{ $t('male') }}
              </label>
              <label class="radio-inline">
                <input 
                  type="radio" 
                  name="sexe" 
                  v-model="form.sexe" 
                  value="F" 
                  required 
                /> {{ $t('female') }}
              </label>
            </div>
          </div>
          <span v-if="errors.sexe" class="text-danger">{{ errors.sexe }}</span>
        </div>
      </div>

      <!-- Bouton suivant -->
      <div class="button-container">
        <button @click="goToNextStep">{{ $t('next') }}</button>
      </div>
    </div>
  </div>
</template>

<script> 
import DatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios';

export default {
  components: {
    DatePicker
  },
  props: ['form','dataArray'],
  data() {
    return {
      wilayas: [],
      communes: [],
      errors: {
        numinscritanem: null,
        nom: '',
        nom_ar: '',
        prenom: '',
        prenom_ar: '',
        datenaiss: '',
        sexe: '',
        wilaya:''
      },
      age: null
    };
  },
  mounted() {
  
    this.getWilayas();
   const storedCityId = localStorage.getItem('selectedCityId');
     if (storedCityId) {
         this.form.c_city_id = storedCityId;
         this.changeWilaya(); 
      }
  }, 
  methods: {
      async getWilayas() {
    const storedWilayas = localStorage.getItem('wilayas');
    if (storedWilayas) {
      this.wilayas = JSON.parse(storedWilayas); // Charger depuis localStorage
      console.log( this.wilayas);
    } else {
      try {
        const response = await axios.get('api/wilayas');
        this.wilayas = response.data;
        localStorage.setItem('wilayas', JSON.stringify(this.wilayas)); // Stocker dans localStorage
        console.log("Wilayas récupérées de l'API et stockées dans le localStorage", this.wilayas);
      } catch (error) {
        console.error('Erreur lors du chargement des wilayas:', error);
      }
    }
  },
    setWilayaByAnemNumber() {
    const anemNumber = String(this.form.numinscritanem).trim();
    if (anemNumber.length >= 2) {
      const locodePrefix = anemNumber.substring(0, 2); 
       console.log(locodePrefix);
      const wilaya = this.wilayas.find(wilaya => wilaya.locode === locodePrefix);
    
      if (wilaya) {
        this.form.c_city_id = wilaya.c_city_id; 
        this.changeWilaya(); 
      } else {
        this.form.c_city_id = ''; 
      }
    }
  },
    async changeWilaya() {
      if (this.form.c_city_id) {
        this.form.centeExamen = this.$i18n.locale === 'ar'
              ? this.wilayas.find(type => type.c_city_id === this.form.c_city_id).sys_centreexamen_ar
              : this.wilayas.find(type => type.c_city_id === this.form.c_city_id).sys_centreexamen;
         console.log(this.form.centeExamen);
     localStorage.setItem('selectedCityId', this.form.c_city_id);
        if(this.dataArray.plus_info ==='Y') {
          axios.get('api/communes', {
          params: { wilayaId: this.form.c_city_id }
        })
        .then(response => {
          console.log(response.data);
          this.communes = response.data;
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des communes:', error);
        });
        } else {
          this.communes = [];
        }
        
      } else {
         console.error('Erreur  :', this.form.c_city_id);
       }
   
    },
    validateForm() {
      let isValid = true;
      this.errors = {}; // Reset errors

      // Validate ANEM number
      const numinscritanemStr = String(this.form.numinscritanem).trim();
      if (numinscritanemStr === '') {
        this.errors.numinscritanem = this.$t('errors.anemNumberRequired');
        isValid = false;
      } else if (numinscritanemStr.length < 5 || numinscritanemStr.length > 15) {
        this.errors.numinscritanem = this.$t('errors.anemNumberLength');
        isValid = false;
      } else {
        this.errors.numinscritanem = '';
      }
      if (this.form.c_city_id === '') {
    this.errors.wilaya = this.$t('errors.wilaya_required');
    isValid = false;
  }
  if(this.dataArray.plus_info ==='Y') {
    if (this.form.hh_commune_id === '') {
    this.errors.commune = this.$t('errors.commune_required');
    isValid = false;
  }
  if (this.form.adresse=== '') {
    this.errors.adresse = this.$t('errors.adresse_required');
    isValid = false;
  }
  }
      // Validate last name
      if (this.form.nom.trim() === '') {
        this.errors.nom = this.$t('errors.lastNameRequired');
        isValid = false;
      } else if (!/^[a-zA-Z\s]+$/.test(this.form.nom)) {
        this.errors.nom = this.$t('errors.invalid_nom');
        isValid = false;
      }

      // Validate last name in Arabic
      if (this.form.nom_ar.trim() === '') {
        this.errors.nom_ar = this.$t('errors.lastNameArRequired');
        isValid = false;
      } else if (!/^[\u0621-\u064A\s]+$/.test(this.form.nom_ar)) {
        this.errors.nom_ar = this.$t('errors.invalid_nom_ar');
        isValid = false;
      }

      // Validate first name
      if (this.form.prenom.trim() === '') {
        this.errors.prenom = this.$t('errors.firstNameRequired');
        isValid = false;
      } else if (!/^[a-zA-Z\s]+$/.test(this.form.prenom)) {
        this.errors.prenom = this.$t('errors.invalid_prenom');
        isValid = false;
      }

      // Validate first name in Arabic
      if (this.form.prenom_ar.trim() === '') {
        this.errors.prenom_ar = this.$t('errors.firstNameArRequired');
        isValid = false;
      } else if (!/^[\u0621-\u064A\s]+$/.test(this.form.prenom_ar)) {
        this.errors.prenom_ar = this.$t('errors.invalid_prenom_ar');
        isValid = false;
      }

      // Validate date of birth
      if (!this.form.datenaiss) {
        this.errors.datenaiss = this.$t('errors.dateOfBirthRequired');
        isValid = false;
      } else {
        const age = this.calculateAge(new Date(this.form.datenaiss));
        if (age < 18 || age > 65) {
          this.errors.datenaiss = this.$t('errors.confirm_age_min');
          isValid = false;
        } else {
          this.age = age;
          this.errors.datenaiss = '';
        }
      }

      // Validate gender
      if (!this.form.sexe) {
        this.errors.sexe = this.$t('errors.genderRequired');
        isValid = false;
      } else {
        this.errors.sexe = '';
      }

     

      return isValid;
    },
    calculateAge(dateOfBirth) {
      const today = new Date();
      let age = today.getFullYear() - dateOfBirth.getFullYear();
      const monthDifference = today.getMonth() - dateOfBirth.getMonth();
      if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dateOfBirth.getDate())) {
        age--;
      }
      return age;
    },
    formatDate(date) {
      const d = new Date(date);
      const year = d.getFullYear();
      const month = ('0' + (d.getMonth() + 1)).slice(-2);
      const day = ('0' + d.getDate()).slice(-2);
      return `${year}-${month}-${day}`;
    },
    goToNextStep() {
      if (this.validateForm()) {
        this.form.datenaiss = this.formatDate(this.form.datenaiss);
        // Proceed to the next step
        this.$emit('nextStep');
      }
    }
  }
};
</script>
