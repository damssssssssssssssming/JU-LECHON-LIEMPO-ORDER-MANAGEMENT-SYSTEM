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

  const toggleButtons = document.querySelectorAll('.toggle-btn');

  toggleButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const row = this.closest('tr');
      const statusSpan = row.querySelector('.status');

      if(statusSpan.classList.contains('available')){
        statusSpan.classList.remove('available');
        statusSpan.classList.add('disabled');
        statusSpan.textContent = 'Disabled';
        this.textContent = 'Enable';
      } else {
        statusSpan.classList.remove('disabled');
        statusSpan.classList.add('available');
        statusSpan.textContent = 'Available';
        this.textContent = 'Disable';
      }
    });
  });
});
