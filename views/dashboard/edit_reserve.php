<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . 'index.php?action=getFormLoginUser');
    exit;
}
$errores = $_SESSION['errores'] ?? [];
$old     = $_SESSION['old']     ?? [];
unset($_SESSION['errores'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reserva- Homfort</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#0f172a] text-slate-200 font-sans min-h-screen">

    <nav class="p-6 flex justify-between items-center border-b border-slate-800">
        <img src="img/logo.png" alt="Logo" class="w-24">
        <a class="ml-3 text-4xl font- tracking-widest uppercase">Homfort</a>
        <div class="flex gap-4">
            <a href="index.php?action=getMisReservas" class="text-amber-400 hover:text-amber-300 font-bold text-sm transition">
                <i class="fas fa-arrow-left mr-2"></i>Mis Reservas
            </a>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto p-6 lg:p-12">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                <span class="w-2 h-8 bg-amber-500 rounded-full inline-block"></span>
                Editar Reserva <span class="text-amber-500"></span>
            </h1>
        </div>

        <?php if (!empty($errores)): ?>
            <div class="bg-red-500/10 border border-red-500/40 text-red-400 px-5 py-4 rounded-xl mb-6 space-y-1">
                <?php foreach ($errores as $msg): ?>
                    <p class="text-sm"><i class="fas fa-circle-exclamation mr-2"></i><?php echo htmlspecialchars($msg); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <section class="bg-slate-900/50 p-8 rounded-3xl border border-slate-800 shadow-2xl">
                    <form action="index.php?action=actualizarReserva" method="POST" class="space-y-6">


                        <!-- Fechas -->
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
                            <p class="text-red-500 text-xs italic"><i class="fas fa-exclamation-circle mr-1"></i><?php echo $errores['fecha']; ?></p>
                        <?php endif; ?>

                        <!-- Tipo + Habitación -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col">
                                <label class="text-[10px] uppercase tracking-[2px] text-amber-500 font-bold mb-2">Tipo de Habitación</label>
                                <select id="id_categoria" name="id_categoria"
                                    class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white outline-none focus:border-amber-500 transition-all">
                                    <option value="">Seleccione categoría...</option>
                                    <?php foreach ($_SESSION['availableCategoria'] as $cat):
                                        $selCat = ($old['id_categoria'] ?? $reserva['id_categoria']) == $cat['id'];
                                    ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo $selCat ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-[10px] uppercase tracking-[2px] text-amber-500 font-bold mb-2">Habitación</label>
                                <select name="room_id" id="room_select"
                                    class="bg-slate-950 border <?php echo isset($errores['habitacion']) ? 'border-red-500' : 'border-slate-800'; ?> rounded-xl px-4 py-3 text-white outline-none focus:border-amber-500 transition-all">
                                    <option value="">Selecciona una habitación...</option>
                                    <?php foreach ($_SESSION['availableRooms'] as $hab):
                                        $selRoom = ($old['room_id'] ?? $reserva['id_habitacion']) == $hab['id'];
                                    ?>
                                        <option value="<?php echo $hab['id']; ?>"
                                            data-precio="<?php echo $hab['precio']; ?>"
                                            data-categoria="<?php echo $hab['id_categoria']; ?>"
                                            <?php echo $selRoom ? 'selected' : ''; ?>>
                                            Hab <?php echo $hab['num_habitacion']; ?> - <?php echo htmlspecialchars($hab['name']); ?> ($<?php echo number_format($hab['precio'], 0, ',', '.'); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col">
                                <label class="text-[10px] uppercase tracking-[2px] text-amber-500 font-bold mb-2">Método de Pago</label>
                                <select name="id_pago" required
                                    class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white outline-none focus:border-amber-500 transition-all">
                                    <?php foreach ($_SESSION['metodosPago'] as $pago):
                                        $selPago = ($old['id_pago'] ?? $reserva['id_metodo_pago']) == $pago['id'];
                                    ?>
                                        <option value="<?php echo $pago['id']; ?>" <?php echo $selPago ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($pago['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-[10px] uppercase tracking-[2px] text-amber-500 font-bold mb-2">Personas</label>
                                <input type="number" name="num_personas" id="num_personas" min="1" max="5"
                                    value="<?php echo intval($old['num_personas'] ?? $reserva['num_personas']); ?>"
                                    class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white outline-none focus:border-amber-500 transition-all">
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-[10px] uppercase tracking-[2px] text-amber-500 font-bold mb-2">Peticiones Especiales</label>
                            <textarea rows="3" name="descripcion"
                                class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white outline-none focus:border-amber-500 transition-all resize-none"><?php echo htmlspecialchars($old['descripcion'] ?? $reserva['Descripcion']); ?></textarea>
                        </div>

                        <div class="flex gap-4">
                            <a href="index.php?action=getMisReservas"
                               class="flex-1 text-center bg-slate-700 hover:bg-slate-600 text-white font-bold py-4 rounded-xl transition text-sm uppercase tracking-widest">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="flex-1 bg-amber-600 hover:bg-amber-500 text-white font-black py-4 rounded-xl transition shadow-lg shadow-amber-900/40 uppercase tracking-widest text-sm">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </section>
            </div>

            <div>
                <div class="bg-gradient-to-br from-amber-600 to-amber-700 p-8 rounded-3xl text-white shadow-2xl relative overflow-hidden">
                    <i class="fas fa-receipt absolute -right-4 -bottom-4 text-9xl opacity-10 rotate-12"></i>
                    <h3 class="text-2xl font-black mb-6 italic">Resumen</h3>
                    <div class="space-y-4 text-sm border-b border-white/20 pb-6 mb-6">
                        <div class="flex justify-between opacity-80">
                            <span>Precio / noche</span>
                            <span id="res-precio-noche" class="font-bold">$0</span>
                        </div>
                        <div class="flex justify-between opacity-80">
                            <span>Estancia</span>
                            <span id="res-noches" class="font-bold">0 noches</span>
                        </div>
                        <div class="flex justify-between opacity-80">
                            <span>Cargo extra personas</span>
                            <span id="res-cargo-personas" class="font-bold">$0</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[10px] uppercase font-bold opacity-70">Total Estancia</p>
                            <span id="res-total" class="text-4xl font-black italic">$0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<script src="<?php echo SITE_URL; ?>js/javascript.js"></script>
</body>
</html>