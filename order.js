function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const layout = document.querySelector(".layout");
  sidebar.classList.toggle("active");
  layout.classList.toggle("shift");
}

function changeQty(btn, change) {
  const card = btn.closest(".menu-card");
  const qtySpan = card.querySelector(".qty");
  const stockBadge = card.querySelector(".stock-badge");

  let stock = parseInt(card.dataset.stock);
  let qty = parseInt(qtySpan.textContent);

  if (change === 1 && stock <= 0) return;

  if (change === 1) {
    qty += 1;
    stock -= 1;
  } else if (change === -1 && qty > 0) {
    qty -= 1;
    stock += 1;
  }

  qtySpan.textContent = qty;
  card.dataset.stock = stock;

  if (stock <= 0) {
    stockBadge.textContent = "Out of stock";
    stockBadge.classList.add("low");
    card.querySelector(".order-btn").disabled = true;
  } else {
    stockBadge.textContent = stock + " left";
    stockBadge.classList.remove("low");
    card.querySelector(".order-btn").disabled = false;
  }
}

const profile = document.querySelector(".profile-dropdown");
if(profile){
  profile.addEventListener("click", function(e){
    e.stopPropagation();
    profile.classList.toggle("active");
  });
  document.addEventListener("click", () => profile.classList.remove("active"));
}
