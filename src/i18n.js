// src/i18n.js
import { createI18n } from 'vue-i18n';

// Importer les traductions
import en from './locales/en.json';
import fr from './locales/fr.json';
import ar from './locales/ar.json';

// Créer une instance de i18n
const i18n = createI18n({
  legacy: false,
  locale: 'fr', // Langue par défaut
  fallbackLocale: 'fr', // Langue de repli
  messages: {
    ar,
    en,
    fr
  }
});

export default i18n;
