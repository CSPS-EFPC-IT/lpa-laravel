export default Object.freeze({
  DEFAULT_LANGUAGE: document.querySelector('html').lang,
  FALLBACK_LANGUAGE: 'en',
  SUPPORTED_LANGUAGES: ['en', 'fr'],
  DEBUG: process.env.NODE_ENV !== 'production',
  // this is to ensure that actions are debounced to avoid user click spam
  // 300 is the ElementUI value that is used to debounce actions
  DEBOUNCE_WAIT_TIME: 300,
  // Will add a notice on the login page when user attempts to launch the application using IE/Edge.
  ENABLE_IE_DETECTION: true
});
