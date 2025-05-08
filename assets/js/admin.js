document.addEventListener("DOMContentLoaded", () => {
    // Mobile sidebar toggle
    const menuToggle = document.querySelector(".menu-toggle")
    const sidebarClose = document.querySelector(".sidebar-close")
    const sidebar = document.querySelector(".sidebar")
  
    if (menuToggle && sidebar) {
      menuToggle.addEventListener("click", () => {
        sidebar.classList.add("open")
      })
    }
  
    if (sidebarClose && sidebar) {
      sidebarClose.addEventListener("click", () => {
        sidebar.classList.remove("open")
      })
    }
  
    // Tabs functionality
    const tabTriggers = document.querySelectorAll(".tabs-trigger")
    const tabContents = document.querySelectorAll(".tabs-content")
  
    tabTriggers.forEach((trigger) => {
      trigger.addEventListener("click", () => {
        // Remove active class from all triggers and contents
        tabTriggers.forEach((t) => t.classList.remove("active"))
        tabContents.forEach((c) => c.classList.remove("active"))
  
        // Add active class to clicked trigger and corresponding content
        trigger.classList.add("active")
        const tabId = trigger.getAttribute("data-tab")
        document.getElementById(tabId).classList.add("active")
      })
    })
  
    // Confirmation dialog
    const confirmButtons = document.querySelectorAll("[data-confirm]")
  
    confirmButtons.forEach((button) => {
      button.addEventListener("click", function (e) {
        const message = this.getAttribute("data-confirm")
        if (!confirm(message)) {
          e.preventDefault()
        }
      })
    })
  
    // Modal functionality
    const modalTriggers = document.querySelectorAll("[data-modal-trigger]")
  
    modalTriggers.forEach((trigger) => {
      trigger.addEventListener("click", () => {
        const modalId = trigger.getAttribute("data-modal-trigger")
        const modal = document.getElementById(modalId)
        if (modal) {
          modal.classList.add("active")
        }
      })
    })
  
    const modalCloseButtons = document.querySelectorAll("[data-modal-close]")
  
    modalCloseButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const modal = button.closest(".modal-backdrop")
        if (modal) {
          modal.classList.remove("active")
        }
      })
    })
  
    // Close modal when clicking outside
    const modals = document.querySelectorAll(".modal-backdrop")
  
    modals.forEach((modal) => {
      modal.addEventListener("click", (e) => {
        if (e.target === modal) {
          modal.classList.remove("active")
        }
      })
    })
  })
  