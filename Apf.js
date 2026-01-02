document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const layout = document.getElementById('layout');

  window.toggleSidebar = function() {
    sidebar.classList.toggle('active');
    layout.classList.toggle('shift');
  };

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

  const editBtn = document.getElementById('editProfileBtn');
  const editForm = document.getElementById('editProfileForm');

  editBtn.addEventListener('click', () => {
    editForm.classList.toggle('hidden');
    editForm.classList.toggle('visible');

    if (editForm.classList.contains('visible')) {
      editBtn.innerHTML = '<i class="fa-solid fa-xmark"></i> Cancel';
    } else {
      editBtn.innerHTML = '<i class="fa-solid fa-pen"></i> Edit';
    }
  });
});
