document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const layout = document.getElementById('layout');

  function toggleSidebar() {
    sidebar.classList.toggle('active');
    layout.classList.toggle('shift');
  }

  window.toggleSidebar = toggleSidebar;

  const profile = document.querySelector('.profile-dropdown');
  if(profile){
    profile.addEventListener('click', function(){
      profile.classList.toggle('active');
    });
  }

  document.addEventListener('click', function(e){
    const dropdown = document.querySelector('.profile-dropdown');
    if(dropdown && !dropdown.contains(e.target)){
      dropdown.classList.remove('active');
    }
  });

  const settingsForm = document.getElementById('settingsForm');
  settingsForm.addEventListener('submit', function(e){
    e.preventDefault();
    alert('Settings saved successfully!');
  });
});
