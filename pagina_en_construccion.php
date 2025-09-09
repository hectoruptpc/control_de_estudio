<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página en Construcción</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #ffffff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        
        .construction-card {
            background: #0267ffff; 
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            max-width: 600px;
            width: 90%;
            animation: fadeIn 1.5s ease;
            color: #fff;
            margin: 20px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .construction-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: #FFC107;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.08); }
            100% { transform: scale(1); }
        }
        
        h1 {
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        p {
            font-size: 1.1rem;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        
        .progress {
            height: 8px;
            margin-bottom: 25px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .progress-bar {
            border-radius: 10px;
            background: #FFC107;
            height: 100%;
            width: 65%;
            transition: width 2s ease-in-out;
        }
        
        .countdown {
            font-size: 1.4rem;
            margin-bottom: 25px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        .contact-info {
            margin-top: 20px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
        }
        
        .social-icons {
            margin-top: 20px;
        }
        
        .social-icons a {
            color: #fff;
            font-size: 1.3rem;
            margin: 0 8px;
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .social-icons a:hover {
            color: #FFC107;
            transform: translateY(-3px);
        }
        
        /* Estilos para el botón de volver */
        .back-button {
            margin-top: 25px;
            padding: 12px 25px;
            background-color: #FFC107;
            color: #333;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .back-button:hover {
            background-color: #ffca28;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="construction-card">
        <div class="university-logo">
            <img src="uptpc.png" alt="Logo Universidad" style="width: 160px; height: 160px; object-fit: contain;">
        </div>
        
        <div class="construction-icon">
            <i class="fas fa-tools"></i>
        </div>
        
        <h1>PÁGINA EN CONSTRUCCIÓN</h1>
        
        <p>Estamos trabajando duro para brindarte una experiencia increíble. Muy pronto tendremos novedades para ti.</p>
        
        <!-- Botón de volver con PHP -->
        <button class="back-button" onclick="goBack()">Volver Atrás</button>
        
        <script>
            function goBack() {
                window.history.back();
            }
        </script>
        
        <?php
        // Alternativa con PHP - si JavaScript está deshabilitado
        echo '<script>';
        echo 'function goBack() {';
        echo '  window.history.back();';
        echo '}';
        echo '</script>';
        ?>
        
    </div>

    <!-- Font Awesome para los iconos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    
    <script>
        // Animación de la barra de progreso
        document.addEventListener('DOMContentLoaded', function() {
            // Simular progreso
            let progress = 65;
            const progressBar = document.getElementById('progress-bar');
            
            const animateProgress = () => {
                progressBar.style.width = progress + '%';
                
                // Aumentar progreso cada 5 segundos (simulación)
                setInterval(() => {
                    if (progress < 90) {
                        progress += 5;
                        progressBar.style.width = progress + '%';
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
            const messageElement = document.querySelector("p");
            let i = 0;
            const speed = 50;
            
            function typeWriter() {
                if (i < message.length) {
                    messageElement.innerHTML += message.charAt(i);
                    i++;
                    setTimeout(typeWriter, speed);
                }
            }
            
            // Iniciar efecto de escritura después de 1 segundo
            setTimeout(() => {
                messageElement.innerHTML = "";
                typeWriter();
            }, 1000);
        });
    </script>
</body>
</html>