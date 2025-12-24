document.addEventListener("DOMContentLoaded", () => {
  const profileBtn = document.getElementById("profile-btn");
  const profileDropdown = document.querySelector(".profile-dropdown");

  if (!profileBtn || !profileDropdown) return;

  profileBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    profileDropdown.classList.toggle("active");
  });

  window.addEventListener("click", () => {
    profileDropdown.classList.remove("active");
  });
});
