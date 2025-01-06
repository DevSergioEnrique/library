function frmLogin(e) {
    e.preventDefault();
    const clave = document.getElementById("clave");
    if (clave.value == "") {
        clave.classList.add("is-invalid");
        clave.focus();
    } else {
        const url = base_url + "Usuarios/validar";
        const frm = document.getElementById("frmLogin");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                if (res.icono == "success") {
                    window.location = base_url + "Configuracion/admin";
                } else {
                    document.getElementById("alerta").classList.remove("d-none");
                    document.getElementById("alerta").innerHTML = res.msg;
                }
            }
        }
    }
}

function loginEstudiantes(e){
    e.preventDefault();
    window.location = base_url + "Home/loginEstudiantes";
}

function login(e){
    e.preventDefault();
    window.location = base_url;
}