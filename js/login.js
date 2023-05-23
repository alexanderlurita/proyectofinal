document.addEventListener("DOMContentLoaded", () => {
  const username = document.querySelector("#username")
  const password = document.querySelector("#password")
  const btLogin = document.querySelector("#login")

  function login() {
    if (!username.value || !password.value) {
      alert("Por favor escriba su nombre de usuario y contraseña")
    } else {
      const pm = new URLSearchParams()
      pm.append('operacion', 'login')
      pm.append('nombreusuario', username.value)
      pm.append('claveacceso', password.value)
  
      fetch('./controllers/Usuario.controller.php', {
        method: 'POST',
        body: pm
      })
        .then(response => response.json())
        .then(data => {
          console.log(data)
          if (!data.login) {
            alert(data.mensaje)
          } else {
            alert(`¡Inicio de sesión correcto! Bienvenido ${data.nombres}`)
            location.href = './dashboard.php';
          }
        })
    }
  }

  //Eventos 
  username.addEventListener("keypress", (e) => {
    if (e.keyCode === 13) password.focus();
  })
  password.addEventListener("keypress", (e) => {
    if (e.keyCode === 13) login();
  })
  btLogin.addEventListener("click", login)
})