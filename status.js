const pendingTab = document.getElementById("pendingTab");
const completedTab = document.getElementById("completedTab");

const pendingWrapper = document.getElementById("pendingWrapper");
const completedWrapper = document.getElementById("completedWrapper");

// Tab switching
pendingTab.addEventListener("click", () => {
  pendingTab.classList.add("active");
  completedTab.classList.remove("active");

  pendingWrapper.classList.add("active");
  completedWrapper.classList.remove("active");
});

completedTab.addEventListener("click", () => {
  completedTab.classList.add("active");
  pendingTab.classList.remove("active");

  completedWrapper.classList.add("active");
  pendingWrapper.classList.remove("active");
});

document.querySelectorAll(".status.pending").forEach(status => {
  status.addEventListener("click", () => {
    status.classList.remove("pending");
    status.classList.add("completed");
    status.textContent = "Completed";
  });
});
