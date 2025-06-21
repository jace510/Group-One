// Make functions globally available immediately
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add("active");
    document.body.style.overflow = "hidden";
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove("active");
    document.body.style.overflow = "auto";
  }
}

function switchModal(fromModalId, toModalId) {
  closeModal(fromModalId);
  setTimeout(() => {
    openModal(toModalId);
  }, 150);
}

// Close modal when clicking outside
document.addEventListener("click", function (event) {
  if (event.target.classList.contains("modal-overlay")) {
    const modalId = event.target.id;
    closeModal(modalId);
  }
});

// Close modal with Escape key
document.addEventListener("keydown", function (event) {
  if (event.key === "Escape") {
    const activeModal = document.querySelector(".modal-overlay.active");
    if (activeModal) {
      closeModal(activeModal.id);
    }
  }
});

// Initialize modals on page load
document.addEventListener("DOMContentLoaded", function () {
  // Ensure all modals start hidden
  document.querySelectorAll(".modal-overlay").forEach((modal) => {
    modal.classList.remove("active");
  });
  document.body.style.overflow = "auto";
});

window.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  if (params.get('auth') === 'failed') {
    openModal('loginModal');

    // Remove the query parameter from the URL
    params.delete('auth');
    const newUrl = window.location.pathname + '?' + params.toString();
    window.history.replaceState({}, '', newUrl);
  }
});
