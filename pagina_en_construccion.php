<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página en Construcción</title>
    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            overflow: hidden;
        }
        
        .construction-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 90%;
            animation: fadeIn 1.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .construction-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            color: #FFC107;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        h1 {
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }
        
        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .progress {
            height: 10px;
            margin-bottom: 30px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .progress-bar {
            border-radius: 10px;
            background: #FFC107;
            transition: width 2s ease-in-out;
        }
        
        .countdown {
            font-size: 1.5rem;
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .social-icons a {
            color: #fff;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: all 0.3s ease;
        }
        
        .social-icons a:hover {
            color: #FFC107;
            transform: translateY(-5px);
        }
        
        .contact-info {
            margin-top: 30px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="construction-container">
        <div class="construction-icon">
            <i class="fas fa-hard-hat"></i>
            <i class="fas fa-tools"></i>
            <i class="fas fa-wrench"></i>
        </div>
        
        <h1>PÁGINA EN CONSTRUCCIÓN</h1>
        
        <p>Estamos trabajando duro para brindarte una experiencia increíble. Muy pronto tendremos novedades para ti.</p>
        
       
        
        
        
        
        
        

    <!-- jQuery and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    
    <script>
        // Animación de la barra de progreso
        $(document).ready(function() {
            // Simular progreso
            let progress = 65;
            const progressBar = $('.progress-bar');
            
            const animateProgress = () => {
                progressBar.css('width', progress + '%');
                
                // Aumentar progreso cada 5 segundos (simulación)
                setInterval(() => {
                    if (progress < 90) {
                        progress += 5;
                        progressBar.css('width', progress + '%');
                    }
                }, 5000);
            };
            
            animateProgress();
            
            // Contador regresivo (ejemplo: 15 días)
            const countdownDate = new Date();
            countdownDate.setDate(countdownDate.getDate() + 15);
            
            const updateCountdown = () => {
                const now = new Date().getTime();
                const distance = countdownDate - now;
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById("countdown-timer").innerHTML = 
                    `${days.toString().padStart(2, '0')}:${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            };
            
            updateCountdown();
            setInterval(updateCountdown, 1000);
            
            // Efecto de escritura para el mensaje
            const message = "Estamos trabajando duro para brindarte una experiencia increíble. Muy pronto tendremos novedades para ti.";
            let i = 0;
            const speed = 50;
            
            function typeWriter() {
                if (i < message.length) {
                    document.querySelector("p").innerHTML += message.charAt(i);
                    i++;
                    setTimeout(typeWriter, speed);
                }
            }
            
            // Iniciar efecto de escritura después de 1 segundo
            setTimeout(() => {
                document.querySelector("p").innerHTML = "";
                typeWriter();
            }, 1000);
        });
    </script>
</body>
</html>