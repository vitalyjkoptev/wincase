/**
 * WinCase CRM — i18n (Internationalization)
 * Supported: en, pl, ua
 * Persists to localStorage. Translates [data-lang] and [data-lang-placeholder].
 */
(function () {
  'use strict';

  var STORAGE_KEY = 'wc-lang';
  var DEFAULT_LANG = 'en';
  var SUPPORTED = ['en', 'pl', 'ua'];
  var FLAG_MAP = { en: 'us', pl: 'pl', ua: 'ua' };

  var translations = {};
  var currentLang = DEFAULT_LANG;

  function getSavedLang() {
    try {
      var saved = localStorage.getItem(STORAGE_KEY);
      return (SUPPORTED.indexOf(saved) !== -1) ? saved : DEFAULT_LANG;
    } catch (e) { return DEFAULT_LANG; }
  }

  /** Build URL — use absolute path from root, no meta tag dependency */
  function getLangUrl(lang) {
    return '/assets/lang/' + lang + '.json?v=' + Date.now();
  }

  /** Load translations via XHR (no async/await for max compat) */
  function loadTranslations(lang, callback) {
    var url = getLangUrl(lang);
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          try { callback(JSON.parse(xhr.responseText)); }
          catch (e) { console.warn('[i18n] Parse error:', e); callback({}); }
        } else {
          console.warn('[i18n] Load failed: ' + url + ' → HTTP ' + xhr.status);
          callback({});
        }
      }
    };
    xhr.send();
  }

  /** Apply translations to all tagged elements */
  function applyTranslations() {
    var els = document.querySelectorAll('[data-lang]');
    for (var i = 0; i < els.length; i++) {
      var key = els[i].getAttribute('data-lang');
      if (translations[key]) els[i].textContent = translations[key];
    }
    var phs = document.querySelectorAll('[data-lang-placeholder]');
    for (var j = 0; j < phs.length; j++) {
      var pk = phs[j].getAttribute('data-lang-placeholder');
      if (translations[pk]) phs[j].setAttribute('placeholder', translations[pk]);
    }
  }

  /** Update dropdown flag and active state */
  function updateDropdownUI(lang) {
    var dd = document.getElementById('language-dropdown');
    if (!dd) return;

    var triggerImg = dd.querySelector('[data-bs-toggle="dropdown"] img');
    if (triggerImg) {
      var flag = FLAG_MAP[lang] || lang;
      triggerImg.src = triggerImg.src.replace(/flags\/[\w-]+\.svg/, 'flags/' + flag + '.svg');
    }

    var items = dd.querySelectorAll('[data-lang-switch]');
    for (var i = 0; i < items.length; i++) {
      if (items[i].getAttribute('data-lang-switch') === lang) {
        items[i].classList.add('active');
      } else {
        items[i].classList.remove('active');
      }
    }
  }

  /** Switch language */
  function switchLanguage(lang) {
    if (SUPPORTED.indexOf(lang) === -1) lang = DEFAULT_LANG;
    currentLang = lang;
    try { localStorage.setItem(STORAGE_KEY, lang); } catch (e) {}

    loadTranslations(lang, function (data) {
      translations = data;
      applyTranslations();
      updateDropdownUI(lang);
      document.documentElement.setAttribute('lang', lang === 'ua' ? 'uk' : lang);
    });
  }

  /** Bind click handlers on dropdown */
  function bindDropdown() {
    var items = document.querySelectorAll('[data-lang-switch]');
    for (var i = 0; i < items.length; i++) {
      items[i].addEventListener('click', function (e) {
        e.preventDefault();
        switchLanguage(this.getAttribute('data-lang-switch'));
      });
    }
  }

  function init() {
    currentLang = getSavedLang();
    bindDropdown();
    switchLanguage(currentLang);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  window.WcI18n = {
    switchLang: switchLanguage,
    current: function () { return currentLang; },
    t: function (key) { return translations[key] || key; }
  };
})();
