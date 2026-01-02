document.addEventListener("DOMContentLoaded", function () {

  const users = [
    { username: "admin", password: "admin123", role: "admin" },
    { username: "ana", password: "ana123", role: "user" },
    {username: "paul", password: "paul123", role: "user"},
  ];

  const form = document.getElementById("loginForm");
  const warning = document.getElementById("warning");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const username = form.username.value.trim();
    const password = form.password.value.trim();

    const foundUser = users.find(
      u => u.username === username && u.password === password
    );

    if (foundUser) {
      if (foundUser.role === "admin") {
        window.location.href = "adminD.php";
      } else {
        window.location.href = "dash.php";
      }
    } else {
      showWarning("⚠️ Invalid username or password!");
    }
  });

  function showWarning(message) {
    warning.textContent = message;
    warning.style.display = "block";

    setTimeout(() => {
      warning.style.display = "none";
    }, 3000);
  }

});
