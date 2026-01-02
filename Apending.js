document.addEventListener('DOMContentLoaded', function() {

  const sidebar = document.getElementById('sidebar');
  const layout = document.getElementById('layout');

  function toggleSidebar() {
    sidebar.classList.toggle('active');
    layout.classList.toggle('shift');
  }

  const profile = document.querySelector('.profile-dropdown');
  if (profile) {
    profile.addEventListener('click', function() {
      profile.classList.toggle('active');
    });
  }

  document.addEventListener('click', function(e) {
    const dropdown = document.querySelector('.profile-dropdown');
    if (dropdown && !dropdown.contains(e.target)) {
      dropdown.classList.remove('active');
    }
  });

  const completeButtons = document.querySelectorAll('.complete-btn');
  completeButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      this.textContent = 'Completed';
      this.disabled = true;
      this.style.background = '#5cb85c'; 
    });
  });

  document.querySelectorAll(".status.pending").forEach(status => {
    status.style.cursor = "pointer";
    status.addEventListener("click", function() {
      const row = status.closest("tr");
      const orderCode = status.dataset.order;
      fetch("update_order_ajax.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ order_code: orderCode })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          status.textContent = "Completed";
          status.classList.remove("pending");
          status.classList.add("completed");
          const completedWrapper = document.getElementById("completedWrapper");
          completedWrapper.querySelector("tbody").appendChild(row);
          document.getElementById("completedTab").click();
        } else {
          alert("Error updating order: " + data.message);
        }
      })
      .catch(err => alert("Error: " + err));
    });
  });

  document.querySelectorAll(".cancel-btn").forEach(button => {
    button.addEventListener("click", function() {
      if (!confirm("Are you sure you want to cancel this order?")) return;

      const orderCode = button.dataset.order;
      const row = button.closest("tr");

      fetch("cancel_order_ajax.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ order_code: orderCode })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          row.querySelector(".status").textContent = "Canceled";
          row.querySelector(".status").className = "status canceled";
          button.remove();
          const canceledWrapper = document.getElementById("canceledWrapper");
          canceledWrapper.querySelector("tbody").appendChild(row);
          document.getElementById("canceledTab").click();
        } else {
          alert(data.message);
        }
      })
      .catch(err => alert("Error: " + err));
    });
  });
  window.toggleSidebar = toggleSidebar;
});
