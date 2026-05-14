<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homfort Hotel - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="bg-[#0f172a] text-white font-sans">

    <nav class="fixed w-full z-50 bg-[#0f172a]/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <img src="img/logo.png" alt="Logo" class="h-12 w-auto">
                    <span class="ml-3 text-xl font-light tracking-widest uppercase">Homfort</span>
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="#" class="text-amber-500 px-3 py-2 text-sm font-medium">Inicio</a>
                        <a href="index.php?action=getFormReserveUser" class="text-slate-300 hover:text-white px-3 py-2 text-sm font-medium transition">Reservas</a>
                        <a href="index.php?action=getMisReservas" class="text-slate-300 hover:text-white px-3 py-2 text-sm font-medium transition">Mis reservas</a>
                        
                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                            <span class="text-amber-500 text-sm font-medium border-l border-slate-700 pl-8">
                                <i class="fa-solid fa-user mr-2"></i><?php echo $_SESSION['user_email']; ?>
                            </span>
                            <a href="index.php?action=cerrarSesion" class="bg-red-600/20 hover:bg-red-600 text-red-500 hover:text-white border border-red-600/50 px-6 py-2 rounded-full text-sm font-bold transition">
                                <i class="fa-solid fa-power-off mr-2"></i>Cerrar Sesión
                            </a>
                        <?php else: ?>
                            <a href="index.php?action=getFormRegisterUser" class="bg-slate-800 hover:bg-slate-700 text-white px-6 py-2 rounded-full text-sm font-bold transition shadow-lg border border-slate-700">Registrarse</a>
                            <a href="index.php?action=getFormLoginUser" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-full text-sm font-bold transition shadow-lg">Iniciar Sesión</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="md:hidden text-slate-300">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </div>
            </div>
        </div>
    </nav>
    <div class="relative h-[80vh] w-full overflow-hidden pt-20">
        <div class="absolute inset-0">
            <img src="img/home.jpg" alt="Hotel Lobby" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-[#0f172a] to-transparent opacity-80"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 h-full flex items-center">
            <div class="max-w-2xl">
                <span class="text-amber-500 uppercase tracking-[0.3em] text-sm font-bold">Bienvenido al Lujo</span>
                <h1 class="text-5xl md:text-7xl font-semibold mt-4 leading-tight">Donde la elegancia se encuentra con el confort</h1>
                <p class="text-slate-300 mt-6 text-lg">Descubra una experiencia inigualable en el corazón de la ciudad.</p>
                <div class="mt-10 flex space-x-4">
                    <a href="#suit" class="bg-amber-600 hover:bg-amber-700 px-8 py-4 rounded-md font-bold transition">Explorar Suites</a>
                </div>
            </div>
        </div>
    </div>
    <section class="py-24 bg-[#0f172a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id = "suit">
            <div class="text-center mb-16">
                <h2 class="text-amber-500 font-bold uppercase tracking-widest text-sm">Nuestras Joyas</h2>
                <h3 class="text-4xl font-semibold mt-2">Habitaciones y Suites</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group bg-slate-900 rounded-xl overflow-hidden border border-slate-800 hover:border-amber-500/50 transition-all duration-500">
                    <div class="h-64 overflow-hidden">
                        <img src="img/pretidential.jpg" alt="Suite" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-amber-500 font-bold">$150.000 / Noche</span>
                            <div class="flex text-amber-500 text-xs">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>
                        </div>
                        <h4 class="text-xl font-bold mb-2">Presidential Suite</h4>
                        <p class="text-slate-400 text-sm mb-6">Vista panorámica a la ciudad con acabados en mármol y jacuzzi privado.</p>
                        <a href="#" class="text-white font-bold border-b-2 border-amber-600 pb-1 hover:text-amber-500 transition">Detalles</a>
                    </div>
                </div>
                <div class="group bg-slate-900 rounded-xl overflow-hidden border border-slate-800 hover:border-amber-500/50 transition-all duration-500">
                    <div class="h-64 overflow-hidden">
                        <img src="img/ocean.jpg" alt="Suite" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-amber-500 font-bold">$250.000 / Noche</span>
                            <div class="flex text-amber-500 text-xs">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>
                        </div>
                        <h4 class="text-xl font-bold mb-2">Deluxe Ocean View</h4>
                        <p class="text-slate-400 text-sm mb-6">Despierte con el sonido de las olas en nuestra habitación más solicitada.</p>
                        <a href="#" class="text-white font-bold border-b-2 border-amber-600 pb-1 hover:text-amber-500 transition">Detalles</a>
                    </div>
                </div>
                <div class="group bg-slate-900 rounded-xl overflow-hidden border border-slate-800 hover:border-amber-500/50 transition-all duration-500">
                    <div class="h-64 overflow-hidden">
                        <img src="img/suit normal.jpg" alt="Suite" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-amber-500 font-bold">$550.000 / Noche</span>
                            <div class="flex text-amber-500 text-xs">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                            </div>
                        </div>
                        <h4 class="text-xl font-bold mb-2">Classic Boutique</h4>
                        <p class="text-slate-400 text-sm mb-6">El balance perfecto entre modernidad y la calidez de un hogar.</p>
                        <a href="#" class="text-white font-bold border-b-2 border-amber-600 pb-1 hover:text-amber-500 transition">Detalles</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer style="background-color: #090e1a; padding: 40px 20px; text-align: center; border-top: 1px solid #1e293b;">
        <div class="container">
            <p class="text-slate-500">&copy; 2026 HOMFORT - Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>