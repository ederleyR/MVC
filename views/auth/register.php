<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Homfort</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">
        <div class="hidden lg:flex lg:w-1/2 bg-cover bg-center relative" 
             style="background-image: url('img/hotel register.jpg');">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-black/20"></div>
            <div class="relative z-10 flex flex-col justify-end p-20 text-white">
                <img src="img/logo.png" alt="Logo" class="w-32 mb-8">
                <h1 class="text-4xl font-light leading-tight">devuelta al confort y comodidad</h1>
                <p class="mt-4 text-gray-300">Reserva una habitacion perfecta para ti</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center bg-[#0f172a] px-8 md:px-24 py-12">
            <div class="flex justify-end">
                <a href="index.php?action=getFormInicio" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-full text-sm font-bold transition shadow-lg"> Home</a>
            </div>
            <div class="max-w-md w-full mx-auto">
                <h2 class="text-3xl font-semibold text-white mb-2">Crear Cuenta</h2>
                <p class="text-slate-400 mb-8">Regístrate para comenzar tu experiencia.</p>
                <?php if(isset($_SESSION['errores']) && is_array($_SESSION['errores'])): ?>
                    <div class="bg-red-500/20 border border-red-500 text-red-500 p-3 rounded mb-4 shadow-sm">
                        <ul class="list-disc list-inside text-sm">
                            <?php foreach($_SESSION['errores'] as $errores): ?>
                                <li><?php echo $errores; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php 
                        unset($_SESSION['errores']); 
                    ?>
                <?php endif; ?>
                <form id="form-login" action="index.php?action=registerUser" method="POST" class="space-y-4">
                    <div class="flex flex-col">
                        <label class="text-[10px] text-slate-400 uppercase mb-1">Tipo de Documento</label>
                        <select name="document_type_id" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 text-white rounded text-xs focus:ring-1 focus:ring-amber-500 outline-none transition">
                            <option value="" selected disabled>Seleccione tipo de documento</option> 
                            <?php if (isset($_SESSION['documentTypes'])): ?>
                                <?php foreach ($_SESSION['documentTypes'] as $tipo): ?>
                                    <option value="<?php echo $tipo['ID']; ?>">
                                        <?php echo $tipo['Name']; ?> 
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-slate-300 mb-1">Documento</label>
                        <input type="text" name="document_number" placeholder="12345678" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 text-white rounded-md focus:ring-2 focus:ring-amber-500 outline-none placeholder:text-slate-600">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-300 mb-1">Nombre</label>
                            <input type="text" name="name" placeholder="nombre" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 text-white rounded-md focus:ring-2 focus:ring-amber-500 outline-none placeholder:text-slate-600">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-300 mb-1">Apellido</label>
                            <input type="text" name="last_name" placeholder="apellido" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 text-white rounded-md focus:ring-2 focus:ring-amber-500 outline-none placeholder:text-slate-600">
                        </div>
                    </div>
                                    
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-slate-300 mb-1">Teléfono</label>
                        <input type="tel" name="phone" placeholder="+57 300..." class="w-full px-4 py-2 bg-slate-900 border border-slate-700 text-white rounded-md focus:ring-2 focus:ring-amber-500 outline-none placeholder:text-slate-600">
                    </div>

                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-slate-300 mb-1">Email</label>
                        <input type="email" name="email" placeholder="correo@ejemplo.com" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 text-white rounded-md focus:ring-2 focus:ring-amber-500 outline-none placeholder:text-slate-600">
                    </div>

                    <div class="flex flex-col relative">
                        <label class="text-sm font-medium text-slate-300 mb-1">Contraseña</label>
                        <input type="password" name="password" placeholder="tu contraseña" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 text-white rounded-md focus:ring-2 focus:ring-amber-500 outline-none">
                        <i class="fa-regular fa-eye-slash absolute right-3 top-9 text-slate-500 cursor-pointer"></i>
                    </div>
                    <div class="flex flex-col relative">
                        <label class="text-sm font-medium text-slate-300 mb-1">Confirmar Contraseña</label>
                        <input type="password" name="Cpassword" placeholder="confirmar tu contraseña" class="w-full px-4 py-2 bg-slate-900 border border-slate-700 text-white rounded-md focus:ring-2 focus:ring-amber-500 outline-none">
                        <i class="fa-regular fa-eye-slash absolute right-3 top-9 text-slate-500 cursor-pointer"></i>
                    </div>
                    
                    <button type="submit" class="w-full bg-amber-600 text-white py-3 rounded-md font-semibold mt-6 hover:bg-amber-700 transition-all duration-300 shadow-[0_0_20px_rgba(217,119,6,0.2)]">
                        Registrarse
                    </button>

                    <p class="text-center text-sm text-slate-400">
                        ¿Ya tienes cuenta? <a href="index.php?action=getFormLoginUser" class="text-amber-500 font-bold hover:text-amber-400 transition">Iniciar sesión</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>