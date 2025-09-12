/*codigo par validar el movimiento de los formularios*/



const slidePage = document.querySelector(".slide-page");
const nextBtnFirst = document.querySelector(".firstNext");
const prevBtnSec = document.querySelector(".prev-1");
const nextBtnSec = document.querySelector(".next-1");
const prevBtnThird = document.querySelector(".prev-2");
const nextBtnThird = document.querySelector(".next-2");
const prevBtnFourth = document.querySelector(".prev-3");
const submitBtn = document.querySelector(".submit");
const progressText = document.querySelectorAll(".step p");
const progressCheck = document.querySelectorAll(".step .check");
const bullet = document.querySelectorAll(".step .bullet");
let current = 1;

var correo = /^\w([.-_+]?\w+)*@\w+([.-]?\w)*(\.\w{2,10})+$/;

/*
nextBtnFirst.addEventListener("click", function(event){
  event.preventDefault();
  slidePage.style.marginLeft = "-25%";
  bullet[current - 1].classList.add("active");
  progressCheck[current - 1].classList.add("active");
  progressText[current - 1].classList.add("active");
  current += 1;
});
nextBtnSec.addEventListener("click", function(event){
  event.preventDefault();
  slidePage.style.marginLeft = "-50%";
  bullet[current - 1].classList.add("active");
  progressCheck[current - 1].classList.add("active");
  progressText[current - 1].classList.add("active");
  current += 1;
});
nextBtnThird.addEventListener("click", function(event){
  event.preventDefault();
  slidePage.style.marginLeft = "-75%";
  bullet[current - 1].classList.add("active");
  progressCheck[current - 1].classList.add("active");
  progressText[current - 1].classList.add("active");
  current += 1;
});
submitBtn.addEventListener("click", function(){
  bullet[current - 1].classList.add("active");
  progressCheck[current - 1].classList.add("active");
  progressText[current - 1].classList.add("active");
  current += 1;
  setTimeout(function(){
    alert("Your Form Successfully Signed up");
    location.reload();
  },800);
});

prevBtnSec.addEventListener("click", function(event){
  event.preventDefault();
  slidePage.style.marginLeft = "0%";
  bullet[current - 2].classList.remove("active");
  progressCheck[current - 2].classList.remove("active");
  progressText[current - 2].classList.remove("active");
  current -= 1;
});

//Principal
prevBtnThird.addEventListener("click", function(event){
  event.preventDefault();
  slidePage.style.marginLeft = "-25%";
  bullet[current - 2].classList.remove("active");
  progressCheck[current - 2].classList.remove("active");
  progressText[current - 2].classList.remove("active");
  current -= 1;
});
prevBtnFourth.addEventListener("click", function(event){
  event.preventDefault();
  slidePage.style.marginLeft = "-50%";
  bullet[current - 2].classList.remove("active");
  progressCheck[current - 2].classList.remove("active");
  progressText[current - 2].classList.remove("active");
  current -= 1;
});

*/



//alertas
function alerta() {
     (async() => {

                      const {value:title } = await Swal.fire({

                                    title:'Condiciones',
                                    html:`<ol style="text-align: left; font-size: 15px;">
                                            
                                            <li>Nombre y apellido completo </li>
                                            <li>El numero de teléfono debe ser de 11 caracteres</li>
                                            <li>El correo debe ser, ejp: exsample@exsample.exsample</li>
                                            
                                            </ol> `,

                                    backdrop:false,
                                    background:'#FFF',
                                    allowOutsideClick:false,
                                    allowEscapeKey:false,
                                    stopKeydownPropagation:false,
                                    toast:false,
                                    width:'300px',
                                    padding:'1rem',
                                    grow:false,
                                    position:'top-end',
                                    showCancelButton:false,
                                    showConfirmButton:false,
                                    showCloseButton:true,
                                    closeButtonAriaLabel:'boton de canselacion'


                        
                             });


                          })();



}





//verificar datos
function verificar(e) {




    for (var i =$('.formInput').length-1; i >= 0; i--) {

    

        if ($('.formInput')[i].value === '') {

          var texto =$('.formInput')[i].getAttribute("title").substr(1,1);

             var newTex =$('.formInput')[i].getAttribute("title").substr(2,$('.formInput')[i].getAttribute("title").length);

          if (texto == " ") {

             newTex =$('.formInput')[i].getAttribute("title").substr(2,$('.formInput')[i].getAttribute("title").length);



          }else{
             newTex =$('.formInput')[i].getAttribute("title").substr(0,$('.formInput')[i].getAttribute("title").length);
          }

                
            Swal.fire({

                                    title:`¡Complete el campo  '${newTex}' !`,
                                    backdrop:true,
                                    background:'#FFF',
                                    allowOutsideClick:false,
                                    allowEscapeKey:false,
                                    stopKeydownPropagation:false,
                                    toast:true,
                                    position:'top',
                                    icon: 'warning',


                                     customClass:{
    
                                  title : 'icon'
                     
                                 }
                        
                             });


            e.preventDefault();
          

           

        }else if($('.formInput')[i].getAttribute('type')== 'email'){


            
            

            if (!correo.exec($('.formInput')[i].value)) {

               
            var newclass ='n';
            var invalid = $('.formInput')[i].className.lastIndexOf('invalidInput');
            

            if (invalid <= 0 ) {

                newclass = $('.formInput')[i].className=`${$('.formInput')[i].getAttribute("class")} invalidInput`;
                

            }

                alerta();
            }




        }else if($('.formInput')[i].getAttribute('type')== 'number'){
            
            

            if (!parseInt($('.formInput')[i].value)) {

               
            var newclass ='n';
            var invalid = $('.formInput')[i].className.lastIndexOf('invalidInput');
            

            if (invalid <= 0 ) {

                newclass = $('.formInput')[i].className=`${$('.formInput')[i].getAttribute("class")} invalidInput`;
                

            }

                alerta();
            }




        }

    }



}



































//ver formulario

$(page());

function page(){



    for (var i =0; i <= $('.page').length-1; i++) {

        if ($('.page')[i].className =="page") {

           $('.page')[i].style.display ="none";

           
           


        }

    }





}



//movimientos de los formularios

$('.prev').click(function(event) {
 


     for (var i =0; i <= $('.prev').length-1; i++) {

        if ($('.page')[i].className =="page slide-page") {

          var prev = i-1;

           $('.page')[i].className ="page";
           $('.page')[i].style.display ="none";
           $('.page')[prev].style.display ="block";
           $('.page')[prev].className ="page slide-page";
           bullet[prev].classList.remove("active");
           progressCheck[prev].classList.remove("active");
          progressText[prev].classList.remove("active");
         

           break


        }

    }



});

//movimientos de los formularios next

$('.next').click(function(event) {
  

    for (var i =0; i <= $('.prev').length-1; i++) {

     

      var next= i+1;

      var siginte = 0;


  var inpus = $('.page')[i].querySelectorAll('.formInput').length;


        if ($('.page')[i].className =="page slide-page") {


          for (var j =0; j <= inpus -1; j++) {


            if ($('.page')[i].querySelectorAll('.formInput')[j].value === '') {



                 

                 console.log('si');
                 siginte = 0;
                 break

            }else{


                siginte = 1;

                
                 



            }



          }

          if (siginte == 1) {


            $('.page')[i].className ="page";
           $('.page')[i].style.display ="none";
           $('.page')[next].style.display ="block";
           $('.page')[next].className ="page slide-page";
           bullet[i].classList.add("active");
           progressCheck[i].classList.add("active");
           progressText[i].classList.add("active");

           break







          }else if(siginte == 0){

            var inputs = $('.page')[i].querySelectorAll('.formInput')[j];


            var texto =inputs.getAttribute("title").substr(1,1);

             var newTex =inputs.getAttribute("title").substr(2,inputs.getAttribute("title").length);

          if (texto == " ") {

             newTex =inputs.getAttribute("title").substr(2,inputs.getAttribute("title").length);



          }else{
             newTex =inputs.getAttribute("title").substr(0,inputs.getAttribute("title").length);
          }

          event.preventDefault();


            Swal.fire({

                                    title:`¡Complete el campo  '${newTex}' !`,
                                    backdrop:true,
                                    background:'#FFF',
                                    allowOutsideClick:false,
                                    allowEscapeKey:false,
                                    stopKeydownPropagation:false,
                                    toast:true,
                                    position:'top',
                                    icon: 'warning',


                                     customClass:{
    
                                  title : 'icon'
                     
                                 }
                        
                             });


            







          }








          




        }

    }


});





