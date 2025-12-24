document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const layout = document.querySelector('.layout');
  const profile = document.querySelector('.profile-dropdown');

  window.toggleSidebar = function() {
    sidebar.classList.toggle('active');
    layout.classList.toggle('shift');
  }

  if(profile) {
    profile.addEventListener('click', function(e) {
      e.stopPropagation();
      profile.classList.toggle('active');
    });

    document.addEventListener('click', function() {
      profile.classList.remove('active');
    });
  }
});
