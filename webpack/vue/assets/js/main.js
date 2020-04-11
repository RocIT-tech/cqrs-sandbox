import '@fortawesome/fontawesome-free/js/all';

const navContent = document.getElementById('nav-content');

document.getElementById('nav-toggle').addEventListener('click', () => {
  navContent.classList.toggle('hidden');
});
