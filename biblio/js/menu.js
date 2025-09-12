const mobileMenu = document.getElementById("menu");  
const navList = document.querySelector(".nav-list");  

mobileMenu.addEventListener("click", () => {  
    navList.classList.toggle("active");  
});

$('.nav-list li').click( function(){


 this.classList.toggle("mostrar");  

});

$('.nav-list li a').click( function(event){

    // Verifica si el href contiene un '#'
    if ($(this).attr('href').includes('#')) {
        event.preventDefault(); // Previene la acción predeterminada sólo si contiene '#'
    }

});

