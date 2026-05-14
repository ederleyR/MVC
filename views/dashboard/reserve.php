<?php
    $errores = $_SESSION['errores'] ?? [];
    $old     = $_SESSION['old']     ?? [];
    unset($_SESSION['errores'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Habitación - Homfort</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#0f172a] text-slate-200 font-sans min-h-screen">

    <nav class="p-6 flex justify-between items-center border-b border-slate-800 bg-[#0f172a]/80 backdrop-blur-md sticky top-0 z-50">
        <img src="img/logo.png" alt="Logo" class="w-24">
        <a class="ml-3 text-4xl font- tracking-widest uppercase">Homfort</a>
        <div class="flex gap-4">
            <a href="index.php?action=getMisReservas" class="text-amber-400 hover:text-amber-300 font-bold text-sm transition flex items-center">
                <i class="fas fa-list mr-2"></i> Mis Reservas
            </a>
            <a href="index.php?action=getFormInicio" class="text-slate-400 hover:text-white font-bold text-sm transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Inicio
            </a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-4 md:p-8 lg:p-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-8">
                <section class="bg-slate-900/50 p-6 md:p-10 rounded-3xl border border-slate-800 shadow-2xl">
                    <h2 class="text-2xl font-bold text-white mb-8 flex items-center italic">
                        <span class="w-2 h-8 bg-amber-500 rounded-full mr-4"></span>
                        Detalles de tu Estancia
                    </h2>

                    <form action="index.php?action=GuardandoReserva" method="POST" class="space-y-8">

                        <!-- SECCIÓN FECHAS -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] uppercase tracking-[2px] text-amber-500 font-bold">Fecha inicio</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" required
                                    value="<?php echo htmlspecialchars($old['fecha_inicio'] ?? ''); ?>"
                                    min="<?php echo date('Y-m-d'); ?>"
                                    class="bg-slate-950 border <?php echo isset($errores['fecha']) ? 'border-red-500' : 'border-slate-800'; ?> rounded-xl px-4 py-3 text-white outline-none focus:border-amber-500 transition-all focus:ring-2 focus:ring-amber-500/20">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] uppercase tracking-[2px] text-amber-500 font-bold">Fecha fin</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" required
                                    value="<?php echo htmlspecialchars($old['fecha_fin'] ?? ''); ?>"
                                    min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                    class="bg-slate-950 border <?php echo isset($errores['fecha']) ? 'border-red-500' : 'border-slate-800'; ?> rounded-xl px-4 py-3 text-white outline-none focus:border-amber-500 transition-all focus:ring-2 focus:ring-amber-500/20">
                            </div>
                        </div>
                        <?php if (isset($errores['fecha'])): ?>
                            <p class="text-red-500 text-xs italic -mt-4"><i class="fas fa-exclamation-circle mr-1"></i><?php echo $errores['fecha']; ?></p>
                        <?php endif; ?>

                        <!-- SECCIÓN CATEGORÍA Y HABITACIÓN -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] uppercase tracking-[2px] text-amber-500 font-bold">Tipo de Habitación</label>
                                <select id="id_categoria" name="id_categoria"
                                    class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white outline-none focus:border-amber-500 transition-all appearance-none cursor-pointer">
                                    <option value="">Seleccione categoría...</option>
                                    <?php foreach ($_SESSION['availableCategoria'] as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>"
                                            <?php echo (($old['id_categoria'] ?? '') == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] uppercase tracking-[2px] text-amber-500 font-bold">Número de Habitación</label>
                                <select name="room_id" id="room_select"
                                    class="bg-slate-950 border <?php echo isset($errores['habitacion']) ? 'border-red-500' : 'border-slate-800'; ?> rounded-xl px-4 py-3 text-white outline-none focus:border-amber-500 transition-all appearance-none cursor-pointer">
                                    <option value="">Primero selecciona una categoría...</option>
                                    <?php foreach ($_SESSION['availableRooms'] as $hab): ?>
                                        <option value="<?php echo $hab['id']; ?>"
                                            data-precio="<?php echo $hab['precio']; ?>"
                                            data-categoria="<?php echo $hab['id_categoria']; ?>"
                                            <?php echo (($old['room_id'] ?? '') == $hab['id']) ? 'selected' : ''; ?>>
                                            Hab <?php echo $hab['num_habitacion']; ?> - <?php echo htmlspecialchars($hab['name']); ?> ($<?php echo number_format($hab['precio'], 0, ',', '.'); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errores['habitacion'])): ?>
                                    <p class="text-red-500 text-xs italic mt-1"><i class="fas fa-exclamation-circle mr-1"></i><?php echo $errores['habitacion']; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- PAGO Y PERSONAS -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] uppercase tracking-[2px] text-amber-500 font-bold">Método de Pago</label>
                                <select name="id_pago" required
                                    class="bg-slate-950 border <?php echo isset($errores['pago']) ? 'border-red-500' : 'border-slate-800'; ?> rounded-xl px-4 py-3 text-white outline-none focus:border-amber-500 transition-all">
                                    <option value="">¿Cómo deseas pagar?</option>
                                    <?php foreach ($_SESSION['metodosPago'] as $pago): ?>
                                        <option value="<?php echo $pago['id']; ?>"
                                            <?php echo (($old['id_pago'] ?? '') == $pago['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($pago['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] uppercase tracking-[2px] text-amber-500 font-bold">Personas</label>
                                <input type="number" name="num_personas" id="num_personas" min="1" max="5"
                                    value="<?php echo intval($old['num_personas'] ?? 1); ?>"
                                    class="bg-slate-950 border <?php echo isset($errores['personas']) ? 'border-red-500' : 'border-slate-800'; ?> rounded-xl px-4 py-3 text-white outline-none focus:border-amber-500 transition-all">
                            </div>
                        </div>


                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] uppercase tracking-[2px] text-amber-500 font-bold">Peticiones Especiales</label>
                            <textarea rows="3" name="descripcion" placeholder="¿Algún requerimiento adicional?"
                                class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white outline-none focus:border-amber-500 transition-all resize-none"><?php echo htmlspecialchars($old['descripcion'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-black py-4 rounded-xl transition-all shadow-lg shadow-amber-900/40 uppercase tracking-[3px] text-sm active:scale-[0.98]">
                            Confirmar Reserva Ahora
                        </button>
                    </form>
                </section>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="bg-gradient-to-br from-amber-600 to-amber-700 p-8 rounded-3xl text-white shadow-2xl relative overflow-hidden flex flex-col justify-between min-h-[300px]">
                    <i class="fas fa-receipt absolute -right-4 -bottom-4 text-9xl opacity-10 rotate-12"></i>
                    
                    <div>
                        <h3 class="text-2xl font-black mb-6 italic">Resumen</h3>
                        <div class="space-y-4 text-sm border-b border-white/20 pb-6 mb-6">
                            <div class="flex justify-between items-center opacity-90">
                                <span>Costo Habitación / noche</span>
                                <span id="res-precio-noche" class="font-bold">$0</span>
                            </div>
                            <div class="flex justify-between items-center opacity-90">
                                <span>Estancia</span>
                                <span id="res-noches" class="font-bold">0 noches</span>
                            </div>
                            <div class="flex justify-between items-center opacity-90">
                                <span>Cargo extra personas</span>
                                <span id="res-cargo-personas" class="font-bold">$0</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[10px] uppercase font-bold opacity-80">Total Estancia</p>
                            <span id="res-total" class="text-4xl font-black italic">$0</span>
                        </div>
                        <i class="fas fa-check-circle text-2xl opacity-50"></i>
                    </div>
                </div>

                <div class="bg-slate-900/80 p-8 rounded-3xl border border-slate-800 text-center">
                    <h4 class="text-white font-bold mb-4 uppercase text-xs tracking-widest">¿Necesitas ayuda?</h4>
                    <p class="text-slate-400 text-xs leading-relaxed mb-4">Contáctanos 24/7 para cualquier duda sobre tu reserva.</p>
                    <span class="text-amber-500 font-bold text-sm block mb-1">+57 300 000 0000</span>
                    <span class="text-slate-300 text-xs">reservas@homfort.com</span>
                </div>
            </div>

        </div>
    </main>
</body>
</html>
<script src="<?php echo SITE_URL; ?>js/javascript.js"></script>
</body>
</html>
