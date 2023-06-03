document.addEventListener("DOMContentLoaded", () => {

  // Función para resaltar el enlace actual en la barra lateral
  const highlightCurrentLink = () => {
    const currentURL = window.location.href; // Obtiene la URL actual del navegador
    // Obtiene todos los elementos de enlace dentro de la lista en la barra lateral
    const sidebarLinks = document.querySelectorAll(".sidebar ul li a");
    
    // Recorre todos los enlaces de la barra lateral
    sidebarLinks.forEach(link => {
      if (link.href === currentURL) {
        link.parentNode.classList.add("active");
      } else {
        link.parentNode.classList.remove("active");
      }
    });
  };

  // Función para verificar si una vista de los reportes está abierto
  const checkReportView = () => {
    const currentURL = window.location.href;
    const reportLinks = document.querySelectorAll("#sidemenu li a");

    reportLinks.forEach(link => {
      if (link.href === currentURL) {
        // Si la URL coincide con la vista de reporte actual, se agrega la clase show
        sidemenu.classList.add("show");
      }
    });
  };

  // Evento click en la barra lateral
  document.querySelector(".sidebar").addEventListener("click", (e) => {
    if (e.target.tagName === 'A') {
      // Obtiene el último elemento activo en la barra lateral
      const lastActiveItem = document.querySelector(".sidebar ul li.active");
      if (lastActiveItem) {
        // Si existe un elemento activo, remueve la clase "active"
        lastActiveItem.classList.remove("active");
      }

      // Agrega la clase "active" al elemento padre del enlace clickeado
      const liElement = e.target.parentNode;
      liElement.classList.add("active");
    }
  });

  //Eventos clicks para abrir agregar y quitar la clase, responisivo en celular
  document.querySelector(".open-btn").addEventListener("click", () => {
    document.querySelector(".sidebar").classList.add("active")
  })
  
  document.querySelector(".close-btn").addEventListener("click", () => {
    document.querySelector(".sidebar").classList.remove("active")
  })

  // Resalta el enlace actual cuando se carga la página
  highlightCurrentLink();
  // Llama a la función para verificar la vista de reporte al cargar la página
  checkReportView();

})