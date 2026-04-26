import './bootstrap';

if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js?v=1.0.5', { scope: '/' }).then(function (registration) {
      console.log(`SW registered successfully!`);
  }).catch(function (registrationError) {
      console.log(`SW registration failed`);
  });
}