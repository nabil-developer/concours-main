<template>
  <div class="success-page">
    <div class="card">
      <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
        <i class="checkmark">✓</i>
      </div>
      <h1>{{ $t('success.inscription_title') }}</h1> 
       <p class="centre_examen" v-if="form && form.centeExamen">
        {{ $t('success.text_centre') }} {{ form.centeExamen }}
      </p>
      <p>{{ $t('success.inscription_message') }}</p>
    </div>
  </div>
</template>

<script>
export default {
   props: ['form'],
  mounted() {
    console.log(this.form); 
    console.log(this.form.centeExamen);  // Vérifiez que centeExamen est bien passé
  }
};
</script>

<style scoped>
.success-page {
  display: flex;
  justify-content: center;
  align-items: center;

}

.card {
  text-align: center;
  background: white;
  padding: 60px;
  border-radius: 4px;
  box-shadow: 0 2px 3px #C8D0D8;
  display: inline-block;
  margin: 0 auto;
}

h1 {
  color: #FF6B01;
  font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
  font-weight: 900;
  font-size: 30px;
  margin-bottom: 40px;
}

p {
  color: #103e5b;
  font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
  font-size: 20px;
  margin: 12px;
}

i {
  color: #FF6B01;
  font-size: 100px;
  line-height: 200px;
  margin-left: -15px;
}
.centre_examen{
   font-weight: bold;
}
</style>
