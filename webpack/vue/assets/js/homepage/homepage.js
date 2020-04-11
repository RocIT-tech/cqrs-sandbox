import Vue from 'vue';
import HomepageApp from './components/HomepageApp';

new Vue({
  el: document.getElementById('app'),
  components: { HomepageApp },
  template: '<HomepageApp />',
});
