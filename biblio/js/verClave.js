/*==========================================*/
/*=   "verClave" documento java script     =*/
/*=	  para el funcionamiento del login     =*/
/*=	para que el usuario pueda ver su clave =*/
/*==========================================*/


(function() {


var ojo = document.getElementById('ojo'),
	clave = document.getElementById('pass'),
	boton = document.getElementById('boton');





function verClave() {

		
		if (ojo.className == 'icon icon-eye') {

			ojo.className = 'icon icon-eye-blocked';
			clave.setAttribute("type", "password");
		} else {

			ojo.className = 'icon icon-eye';
			clave.setAttribute("type", "text");
		}

	}	



boton.addEventListener('click', verClave);

}())