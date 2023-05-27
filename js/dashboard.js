document.addEventListener("DOMContentLoaded", () => {

  const highlightCurrentLink = () => {
    const currentURL = window.location.href;
    const sidebarLinks = document.querySelectorAll(".sidebar ul li a");
    
    sidebarLinks.forEach(link => {
      if (link.href === currentURL) {
        link.parentNode.classList.add("active");
      } else {
        link.parentNode.classList.remove("active");
      }
    });
  };
  
  highlightCurrentLink();

  document.querySelector(".sidebar").addEventListener("click", (e) => {
    if (e.target.tagName === 'A') {
      const lastActiveItem = document.querySelector(".sidebar ul li.active");
      if (lastActiveItem) {
        lastActiveItem.classList.remove("active");
      }
      
      const liElement = e.target.parentNode;
      liElement.classList.add("active");
    }
  });

  document.querySelector(".open-btn").addEventListener("click", () => {
    document.querySelector(".sidebar").classList.add("active")
  })
  
  document.querySelector(".close-btn").addEventListener("click", () => {
    document.querySelector(".sidebar").classList.remove("active")
  })

})