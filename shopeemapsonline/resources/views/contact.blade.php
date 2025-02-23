<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Admin - ShopeeMapsOnline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            width: 100%;
        }
        .contact-container {
            background: white;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            margin: 1rem auto;
        }
        .btn-whatsapp {
            background: #ee4d2d;
            color: white;
            border: none;
            padding: 1.2rem;
            margin: 0.7rem 0;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
        }
        .btn-whatsapp i {
            font-size: 1.3rem;
        }
        .btn-whatsapp:hover {
            background: #f53d2d;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(238, 77, 45, 0.3);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .logo {
            color: #ee4d2d;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }
        .contact-icon {
            font-size: 3.5rem;
            color: #ee4d2d;
            margin-bottom: 1.5rem;
            animation: float 3s ease-in-out infinite;
        }
        .contact-title {
            color: #333;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .animate-hover:hover {
            animation: pulse 1s;
        }
        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
        }
        @media (max-width: 576px) {
            .contact-container {
                padding: 1.5rem 1rem;
                margin: 0.5rem;
            }
            .logo {
                font-size: 1.8rem;
            }
            .contact-icon {
                font-size: 3rem;
            }
            .contact-title {
                font-size: 1.3rem;
            }
            .btn-whatsapp {
                padding: 1rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="contact-container animate__animated animate__fadeIn">
            <div class="logo-container">
                <div class="logo">SPX Maps</div>
                <div class="contact-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="contact-title">Hubungi Admin</div>
            </div>
            
            <div class="buttons-container">
                <a href="https://wa.me/6281259747494" class="btn btn-whatsapp animate-hover">
                    <i class="fab fa-whatsapp"></i>
                    Admin Bre
                </a>
                {{-- <a href="https://wa.me/62895623409631" class="btn btn-whatsapp animate-hover">
                    <i class="fab fa-whatsapp"></i>
                    Admin Ilham
                </a> --}}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn-whatsapp');
            buttons.forEach(button => {
                button.addEventListener('mouseover', function() {
                    this.classList.add('animate__animated', 'animate__pulse');
                });
                button.addEventListener('animationend', function() {
                    this.classList.remove('animate__animated', 'animate__pulse');
                });
            });
        });
    </script>
</body>
</html>
