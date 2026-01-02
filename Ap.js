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

      const id = row.dataset.id;
      fetch("toggle_item.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
      })
      .then(res => res.json())
      .then(data => {
        if(!data.success){
          console.warn("Toggle item warning:", data.message);
        }
      })
      .catch(err => console.error("Toggle item error:", err));
    });
  });

  const stockButtons = document.querySelectorAll(".stock-btn");

  stockButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      const row = btn.closest("tr");
      const id = row.dataset.id;
      const action = btn.dataset.action;

      fetch("update_stock.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id, action })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          row.querySelector(".stock-count").textContent = data.stock;
        } else {
          console.warn("Stock update warning:", data.message);
        }
      })
      .catch(err => console.error("Stock update error:", err));
    });
  });
});
