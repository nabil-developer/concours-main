<template>
  <div class="panel panel-danger" style="margin:10px">
    <div class="panel-heading">
      <div class="panel-title">{{ $t('personal_information') }}</div>
      <br />
    </div>
    <div class="panel-body">
      <div class="form-row">
        <div class="form-group">
        <label class="col-md-4 control-label">
          {{ $t('phone') }} <span style="color: red">*</span>
        </label>
        <div class="col-md-4 inputGroupContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
            <input 
              id="tel" 
              name="tel" 
              :placeholder="$t('phone_placeholder')" 
              class="form-control" 
              type="text" 
              v-model="form.phone" 
              @input="validatePhone"
              required 
            />
            <span v-if="errors.phone" class="text-danger">{{ errors.phone }}</span>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-4 control-label">
          {{ $t('confirm_phone') }} <span style="color: red">*</span>
        </label>
        <div class="col-md-4 inputGroupContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
            <input 
              id="confirm_tel" 
              name="confirm_tel" 
              :placeholder="$t('confirm_phone_placeholder')" 
              class="form-control" 
              type="text" 
              v-model="form.confirm_phone" 
              @input="validateConfirmPhone"
              required 
            />
            <span v-if="errors.confirm_phone" class="text-danger">{{ errors.confirm_phone }}</span>
          </div>
        </div>
      </div>
      </div>
      <div class="form-row">
        <div class="form-group">
        <label class="col-md-4 control-label">
          {{ $t('email') }} <span style="color: red">*</span>
        </label>
        <div class="col-md-4 inputGroupContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
            <input 
              name="email" 
              :placeholder="$t('email_placeholder')" 
              class="form-control" 
              type="email" 
              v-model="form.email" 
              @input="validateEmail"
              required 
            />
            <span v-if="errors.email" class="text-danger">{{ errors.email }}</span>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-4 control-label">
          {{ $t('confirm_email') }} <span style="color: red">*</span>
        </label>
        <div class="col-md-4 inputGroupContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
            <input 
              name="confirm_email" 
              :placeholder="$t('confirm_email_placeholder')" 
              class="form-control" 
              type="email" 
              v-model="form.confirm_email" 
              @input="validateConfirmEmail"
              required 
            />
            <span v-if="errors.confirm_email" class="text-danger">{{ errors.confirm_email }}</span>
          </div>
        </div>
      </div>

     
      </div>  
      <div class="form-group">
        <label class="col-md-4 control-label">
          {{ $t('national_status') }} <span style="color: red">*</span>
        </label>
        <div class="col-md-4 selectContainer">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <select 
              class="form-control selectpicker" 
              v-model="form.situation" 
              required 
            >
              <option value="">{{ $t('choose_status') }}</option>
              <option v-for="situation in situations" :key="situation.id" :value="situation.id">
                {{ situation.name }}
              </option>
            </select>
            <span v-if="errors.situation" class="text-danger">{{ errors.situation }}</span>
          </div>
        </div>
      </div>

      <div class="button-container">
        <button @click="goToPreviousStep">{{ $t('previous') }}</button>
        <button @click="goToNextStep">{{ $t('next') }}</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['form','dataArray'],
  data() {
    return {
      situations: [
        { id: 1, name: "Non concernée" },
        { id: 2, name: "Accomplis" },
        { id: 3, name: "Dispensé" },
        { id: 4, name: "Sursitaire" },
        { id: 5, name: "Non-justifié"}
      ],
      errors: {},  // Garder les erreurs ici 
    };
  },
  computed: {

    isPhoneValid() {
      const phoneRegex = /^\d{10}$/;
      return phoneRegex.test(this.form.phone);
    },
    isConfirmPhoneValid() {
      return this.form.phone === this.form.confirm_phone;
    },
    isEmailValid() {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(this.form.email);
    },
    isConfirmEmailValid() {
      return this.form.confirm_email === this.form.email;
    },
    isSituationValid() {
      return this.form.situation !== '';
    }
  },
  methods: {
    goToNextStep() {
      if (this.validateForm()) {
        this.$emit('nextStep');
      }
    },
    goToPreviousStep() {
      this.$emit('previousStep');
    },
   
    validatePhone() {
      this.errors.phone = this.isPhoneValid ? '' : this.$t('phoneInvalid');
    },
    validateConfirmPhone() {
      this.errors.confirm_phone = this.isConfirmPhoneValid ? '' : this.$t('errors.confirm_phone_mismatch');
    },
    validateEmail() {
      this.errors.email = this.isEmailValid ? '' : this.$t('emailInvalid');
    },
    validateConfirmEmail() {
      this.errors.confirm_email = this.isConfirmEmailValid ? '' : this.$t('emailConfirmationMismatch');
    },
    validateForm() {
  let isValid = true;
  this.errors = {}; // Assurez-vous que this.errors est défini comme un objet vide


 
  if (!this.isPhoneValid) {
    this.errors.phone = this.$t('errors.phone_invalid');
    isValid = false;
  }
  if (!this.isConfirmPhoneValid) {
    this.errors.confirm_phone = this.$t('errors.confirm_phone_mismatch');
    isValid = false;
  }
  if (!this.isEmailValid) {
    this.errors.email = this.$t('errors.email_invalid');
    isValid = false;
  }
  if (!this.isConfirmEmailValid) {
    this.errors.confirm_email = this.$t('errors.confirm_email_mismatch');
    isValid = false;
  }
  if (!this.isSituationValid) {
    this.errors.situation = this.$t('errors.situation_required');
    isValid = false;
  }

  return isValid;
}
  }
};
</script>
