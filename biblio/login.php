 
<style type="text/css">
body{
    background-image: url(img/imagen.jpg);
    background-repeat: no-repeat;
    background-size: 100% 100%;
    background-attachment: fixed;
}


</style>
    <main>  
        <section class="registro">  
            

            <div class="form">
                <h2>Ingrese sus Credenciales</h2> 
                <form id="form" action="validarLogin.php" method="POST">  
                <div class="campo input-group mt-1">  
                    <label for="email">Correo Electrónico:</label>  
                    <input class="input" type="email" id="email" name="email"  required>  
                </div>  
                <div class="campo inputLg input-group mt-1">  
                    <label for="pass">Contraseña:</label>  
                    <input class="input" type="password" id="pass" name="pass" required> 
                    <button for="pass" id="boton" type="button"><i id="ojo" class="icon icon-eye-blocked"></i></button> 
                </div>  
                <div class="campo input-group mt-1">
                    
                    <span id="noVilid" class="noValid">¡Los datos ingresados no son validos!</span>  

                    <button name="btn" id="btn" type="submit">Iniciar Sesión</button> 
                </div>
                 
            </form> 
                
            </div> 
             
        </section>  
    </main>  
<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/validarLogin.js"></script>
<script src="js/verClave.js"></script>
