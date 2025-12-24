document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const layout = document.getElementById('layout');

  function toggleSidebar() {
    sidebar.classList.toggle('active');
    layout.classList.toggle('shift');
  }

  window.toggleSidebar = toggleSidebar;

  const profileDropdown = document.querySelector('.profile-dropdown');
  if(profileDropdown){
    profileDropdown.addEventListener('click', function(){
      profileDropdown.classList.toggle('active');
    });
  }

  document.addEventListener('click', function(e){
    if(profileDropdown && !profileDropdown.contains(e.target)){
      profileDropdown.classList.remove('active');
    }
  });

  const profileForm = document.getElementById('profileForm');
  profileForm.addEventListener('submit', function(e){
    e.preventDefault();
    alert('Profile updated successfully!');
  });
});
