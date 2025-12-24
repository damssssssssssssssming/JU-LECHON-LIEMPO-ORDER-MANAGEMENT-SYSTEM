const sidebar = document.getElementById('sidebar');
const layout = document.getElementById('layout');

function toggleSidebar() {
  sidebar.classList.toggle('active');
  layout.classList.toggle('shift');
}

document.addEventListener('click', function(e) {
  const dropdown = document.querySelector('.profile-dropdown');
  if(dropdown && !dropdown.contains(e.target)) {
    dropdown.classList.remove('active');
  }
});

const profile = document.querySelector('.profile-dropdown');
if(profile){
  profile.addEventListener('click', function(e){
    e.stopPropagation();
    profile.classList.toggle('active');
  });
}
