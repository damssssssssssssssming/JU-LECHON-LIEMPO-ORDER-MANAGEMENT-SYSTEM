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
    qty++;
    stock--;
  } else if (change === -1 && qty > 0) {
    qty--;
    stock++;
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
if (profile) {
  profile.addEventListener("click", e => {
    e.stopPropagation();
    profile.classList.toggle("active");
  });
  document.addEventListener("click", () => profile.classList.remove("active"));
}

let cart = [];
let cartItems = 0;
let cartTotal = 0;

const floatingCart = document.getElementById("floating-cart");
const cartCount = document.getElementById("cart-count");
const cartTotalText = document.getElementById("cart-total");
const cartModal = document.getElementById("cart-modal");
const cartItemsBox = document.getElementById("cart-items");
const modalTotal = document.getElementById("modal-total");
const viewCartBtn = document.querySelector(".view-cart-btn");
const closeCartBtn = document.getElementById("close-cart");

document.querySelectorAll(".order-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    const card = btn.closest(".menu-card");
    const name = card.querySelector("h3").textContent;
    const price = parseInt(card.querySelector(".price").textContent.replace("₱", ""));
    const qty = parseInt(card.querySelector(".qty").textContent);

    if (qty === 0) return;

    const existing = cart.find(item => item.name === name);
    if (existing) {
      existing.qty += qty;
    } else {
      cart.push({ name, price, qty });
    }

    card.querySelector(".qty").textContent = 0;

    recalcCart();
    renderFloatingCart();

    floatingCart.classList.add("pop");
    setTimeout(() => floatingCart.classList.remove("pop"), 300);
  });
});

function recalcCart() {
  cartItems = 0;
  cartTotal = 0;

  cart.forEach(item => {
    cartItems += item.qty;
    cartTotal += item.qty * item.price;
  });

  cartCount.textContent = `${cartItems} item${cartItems > 1 ? "s" : ""}`;
  cartTotalText.textContent = `₱${cartTotal}`;

  if (cartItems > 0) {
    floatingCart.classList.remove("hidden");
  } else {
    floatingCart.classList.add("hidden");
  }
}

function renderFloatingCart() {
  cartItemsBox.innerHTML = "";

  cart.forEach((item, index) => {
    const itemTotal = item.price * item.qty;

    cartItemsBox.innerHTML += `
      <div class="cart-item">
        <div>
          <strong>${item.name}</strong><br>
          <small>₱${item.price} × ${item.qty} = ₱${itemTotal}</small>
        </div>
        <div class="cart-actions">
          <button onclick="editQty(${index}, 1)">+</button>
          <button onclick="editQty(${index}, -1)">−</button>
          <button onclick="removeItem(${index})">🗑</button>
        </div>
      </div>
    `;
  });

  modalTotal.textContent = `₱${cartTotal}`;
}

function editQty(index, change) {
  cart[index].qty += change;
  if (cart[index].qty <= 0) {
    cart.splice(index, 1);
  }
  recalcCart();
  renderFloatingCart();
}

function removeItem(index) {
  cart.splice(index, 1);
  recalcCart();
  renderFloatingCart();
}

viewCartBtn.addEventListener("click", () => {
  renderFloatingCart();
  cartModal.classList.remove("hidden");
});

closeCartBtn.addEventListener("click", () => {
  cartModal.classList.add("hidden");
});

const checkoutBtn = document.getElementById("checkout-btn");
if (checkoutBtn) {
  checkoutBtn.addEventListener("click", () => {
    if (cart.length === 0) return alert("Cart is empty!");

    fetch("checkout.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ cart }),
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert("Order submitted successfully!");
        cart = [];
        recalcCart();
        renderFloatingCart();
        window.location.href = "status.php"; 
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch(err => alert("Error: " + err));
  });
}
function updateStock() {
  fetch("get_stock.php") // this PHP returns {id: stock, ...}
    .then(res => res.json())
    .then(data => {
      document.querySelectorAll(".menu-card").forEach(card => {
        const id = card.dataset.id; // make sure each card has data-id
        if(data[id] !== undefined){
          const stock = data[id];
          card.dataset.stock = stock;

          const badge = card.querySelector(".stock-badge");
          badge.textContent = stock > 0 ? `${stock} left` : "Out of stock";

          if(stock <= 5) badge.classList.add("low");
          else badge.classList.remove("low");

          const orderBtn = card.querySelector(".order-btn");
          orderBtn.disabled = stock === 0;
          if(stock === 0) orderBtn.textContent = "Out of Stock";
          else orderBtn.textContent = "ADD TO ORDER";
        }
      });
    })
    .catch(err => console.error("Stock update error:", err));
}

updateStock();

setInterval(updateStock, 5000);