<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homfort login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <div class="min-h-screen w-full flex items-center justify-center bg-[#070b14] relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="img/login.jpg" class="w-full h-full object-cover opacity-20 blur-sm" alt="">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#0f172a]"></div>
        </div>

        <div class="relative z-10 w-full max-w-4xl flex flex-col lg:flex-row bg-[#0f172a] rounded-2xl overflow-hidden shadow-2xl border border-slate-800 mx-4">
            
            <div class="hidden lg:flex lg:w-1/2 bg-cover bg-center relative" 
                style="background-image: url('img/login.jpg');">
                
                <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-black/20"></div>
                
                <div class="relative z-10 flex flex-col items-start justify-end p-12 text-white">
                    
                    <img src="img/logo.png" alt="Logo" class="h-10 md:h-12 w-auto object-contain self-start">

                    <h3 class="text-2xl font-light tracking-widest uppercase text-left">
                        HOMFORT
                    </h3>

                    <p class="text-slate-300 text-sm mt-2 text-left">
                        Confort y descanso en un solo lugar.
                    </p>
            </div>
        </div>
        

            <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 md:px-12 py-16">
                <div class="flex justify-end">
                    <a href="index.php?action=getFormInicio" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-full text-sm font-bold transition shadow-lg"> Home</a>
                </div>
                <div class="max-w-sm w-full mx-auto">
                    <h2 class="text-3xl font-semibold text-white mb-2 text-center lg:text-left">Iniciar sesión</h2>
                    <p class="text-slate-400 mb-8 text-center lg:text-left">¡Es bueno volver a verte!</p>

                    <?php if (isset($errores['login'])): ?>
                        <div class="bg-red-500/10 border border-red-500/50 text-red-500 px-4 py-3 rounded-md mb-6 flex items-center gap-3 animate-pulse">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span class="text-sm font-medium"><?php echo $errores['login']; ?></span>
                        </div>
                    <?php endif; ?>

                    <form id="form-login" action="index.php?action=loginUser" method="POST" class="space-y-5">
                        
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-300 mb-1">Email</label>
                            <input type="text" name="email" placeholder="correo@ejemplo.com" 
                                class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700 text-white rounded-md focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all placeholder:text-slate-600 <?php echo isset($errores['login']) ? 'border-red-500/50' : ''; ?>">
                        </div>

                        <div class="flex flex-col relative">
                            <label class="text-sm font-medium text-slate-300 mb-1">Contraseña</label>
                            <input type="password" name="password" placeholder="su contraseña"
                                class="w-full px-4 py-2.5 bg-slate-900/50 border border-slate-700 text-white rounded-md focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all <?php echo isset($errores['login']) ? 'border-red-500/50' : ''; ?>">
                            <i class="fa-regular fa-eye-slash absolute right-3 top-10 text-slate-600 cursor-pointer hover:text-amber-500 transition"></i>
                        </div>

                        <div class="text-right">
                            <a href="#" class="text-xs text-slate-500 hover:text-amber-500 transition">¿Olvidaste tu contraseña?</a>
                        </div>

                        <button type="submit" class="w-full bg-amber-600 text-white py-3 rounded-md font-semibold mt-2 hover:bg-amber-700 hover:shadow-[0_0_20px_rgba(217,119,6,0.4)] transition-all duration-300 active:scale-[0.98]">
                            Entrar
                        </button>   

                        <div class="flex-grow border-t border-slate-800"></div>

                        <p class="text-center text-sm text-slate-400">
                            ¿No tienes cuenta? 
                            <a href="index.php?action=getFormRegisterUser" class="text-amber-500 font-bold hover:text-amber-400 transition ml-1">Regístrate</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
