const pendingTab = document.getElementById("pendingTab");
const completedTab = document.getElementById("completedTab");
const canceledTab = document.getElementById("canceledTab");

const pendingWrapper = document.getElementById("pendingWrapper");
const completedWrapper = document.getElementById("completedWrapper");
const canceledWrapper = document.getElementById("canceledWrapper");

pendingTab.addEventListener("click", () => {
  setActive(pendingTab, pendingWrapper);
});

completedTab.addEventListener("click", () => {
  setActive(completedTab, completedWrapper);
});

canceledTab.addEventListener("click", () => {
  setActive(canceledTab, canceledWrapper);
});

function setActive(tab, wrapper) {
  document.querySelectorAll(".tab-btn").forEach(t => t.classList.remove("active"));
  document.querySelectorAll(".table-wrapper").forEach(w => w.style.display = "none");

  tab.classList.add("active");
  wrapper.style.display = "block";
}

// Pending → Completed
document.querySelectorAll(".status.pending").forEach(status => {
  status.style.cursor = "pointer";
  status.addEventListener("click", () => {
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

        completedWrapper.querySelector("tbody").appendChild(row);
        completedTab.click();
      } else {
        alert("Error updating order: " + data.message);
      }
    })
    .catch(err => alert("Error: " + err));
  });
});

// Cancel button
document.querySelectorAll(".cancel-btn").forEach(button => {
  button.addEventListener("click", () => {
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

        canceledWrapper.querySelector("tbody").appendChild(row);
        canceledTab.click();
      } else {
        alert(data.message);
      }
    })
    .catch(err => alert("Error: " + err));
  });
});
