<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . 'index.php?action=getFormLoginUser');
    exit;
}
$success = $_SESSION['success'] ?? '';
$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['success'], $_SESSION['errores']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - Homfort</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#0f172a] text-slate-200 font-sans min-h-screen">

    <nav class="fixed w-full z-50 bg-[#0f172a]/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center">
                    <img src="img/logo.png" alt="Logo" class="h-12 w-auto">
                    <span class="ml-3 text-xl font-light tracking-widest uppercase">Homfort</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="index.php?action=getFormReserveUser" class="bg-amber-600 hover:bg-amber-500 text-white px-5 py-2 rounded-full text-sm font-bold transition">
                        <i class="fas fa-plus mr-1"></i> Nueva Reserva
                    </a>
                    <?php if (!empty($reservas)): ?>
                    <a href="index.php?action=descargarExcel"
                       title="Descargar todas mis reservas en Excel"
                       class="bg-green-700 hover:bg-green-600 text-white px-5 py-2 rounded-full text-sm font-bold transition flex items-center gap-2">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </a>
                    <?php endif; ?>
                    <a href="index.php?action=getFormInicio" class="text-amber-500 hover:text-amber-400 font-bold text-sm transition">
                        <i class="fas fa-arrow-left mr-2"></i>Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 pt-28 pb-16">

        <div class="mb-10">
            <h1 class="text-4xl font-bold text-white flex items-center gap-3">
                <span class="w-2 h-10 bg-amber-500 rounded-full inline-block"></span>
                Mis Reservas
            </h1>
            <p class="text-slate-400 mt-2 ml-6">Hola, <span class="text-amber-400 font-semibold"><?php echo htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['user_email'] ?? 'Usuario'); ?></span> — aquí están todas tus reservas.</p>
        </div>

        <?php if ($success): ?>
            <div class="bg-green-500/10 border border-green-500/40 text-green-400 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
                <i class="fas fa-circle-check text-lg"></i>
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($errores)): ?>
            <div class="bg-red-500/10 border border-red-500/40 text-red-400 px-5 py-4 rounded-xl mb-6 space-y-1">
                <?php foreach ($errores as $msg): ?>
                    <p class="text-sm"><i class="fas fa-circle-exclamation mr-2"></i><?php echo htmlspecialchars($msg); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($reservas)): ?>
            <div class="text-center py-24 bg-slate-900/40 rounded-3xl border border-slate-800">
                <i class="fas fa-calendar-xmark text-5xl text-slate-600 mb-4 block"></i>
                <p class="text-slate-400 text-lg mb-6">Aún no tienes ninguna reserva.</p>
                <a href="index.php?action=getFormReserveUser"
                   class="bg-amber-600 hover:bg-amber-500 text-white px-8 py-3 rounded-full font-bold transition text-sm">
                    <i class="fas fa-plus mr-2"></i>Hacer mi primera reserva
                </a>
            </div>
        <?php else: ?>
            <div class="bg-slate-900/50 rounded-3xl border border-slate-800 overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-800 bg-slate-900">
                                <th class="text-left px-4 py-4 text-[10px] uppercase tracking-widest text-amber-500 font-bold">Habitación</th>
                                <th class="text-left px-4 py-4 text-[10px] uppercase tracking-widest text-amber-500 font-bold">Tipo</th>
                                <th class="text-left px-4 py-4 text-[10px] uppercase tracking-widest text-amber-500 font-bold">Entrada</th>
                                <th class="text-left px-4 py-4 text-[10px] uppercase tracking-widest text-amber-500 font-bold">Salida</th>
                                <th class="text-left px-4 py-4 text-[10px] uppercase tracking-widest text-amber-500 font-bold">Personas</th>
                                <th class="text-left px-4 py-4 text-[10px] uppercase tracking-widest text-amber-500 font-bold">Pago</th>
                                <th class="text-left px-4 py-4 text-[10px] uppercase tracking-widest text-amber-500 font-bold">Total</th>
                                <th class="text-left px-4 py-4 text-[10px] uppercase tracking-widest text-amber-500 font-bold">Estado</th>
                                <th class="text-center px-4 py-4 text-[10px] uppercase tracking-widest text-amber-500 font-bold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <?php foreach ($reservas as $r): ?>
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="px-4 py-4 text-white font-semibold">Hab <?php echo htmlspecialchars($r['num_habitacion']); ?></td>
                                <td class="px-4 py-4 text-slate-300"><?php echo htmlspecialchars($r['tipo_habitacion']); ?></td>
                                <td class="px-4 py-4 text-slate-300"><?php echo date('d/m/Y', strtotime($r['fecha_inicio'])); ?></td>
                                <td class="px-4 py-4 text-slate-300"><?php echo date('d/m/Y', strtotime($r['fecha_final'])); ?></td>
                                <td class="px-4 py-4 text-slate-300 text-center"><?php echo $r['num_personas']; ?></td>
                                <td class="px-4 py-4 text-slate-300"><?php echo htmlspecialchars($r['metodo_pago']); ?></td>
                                <td class="px-4 py-4 text-amber-400 font-bold">$<?php echo number_format($r['precio'], 0, ',', '.'); ?></td>
                                <td class="px-4 py-4">
                                    <?php
                                        $estados = [1 => 'Activa', 2 => 'Cancelada', 3 => 'Completada'];
                                        $estadoLabel = $estados[$r['id_estado']] ?? 'Activa';
                                        $estadoClass = match((int)$r['id_estado']) {
                                            2 => 'bg-red-500/20 text-red-400',
                                            3 => 'bg-blue-500/20 text-blue-400',
                                            default => 'bg-green-500/20 text-green-400',
                                        };
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo $estadoClass; ?>">
                                        <?php echo $estadoLabel; ?>
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex justify-center gap-1.5">

                                        <form action="index.php" method="POST">
                                            <input type="hidden" name="action" value="getFormEditarReserva">
                                            <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                                            <button type="submit"
                                                    title="Editar reserva"
                                                    class="bg-amber-600/20 hover:bg-amber-600 text-amber-400 hover:text-white border border-amber-600/40 px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1">
                                                <i class="fas fa-pen-to-square"></i> Editar
                                            </button>
                                        </form>

                                        <!-- PDF -->
                                        <form action="index.php" method="POST">
                                            <input type="hidden" name="action" value="getPDFReserva">
                                            <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                                            <button type="submit"
                                                    title="Descargar PDF"
                                                    class="bg-blue-600/20 hover:bg-blue-600 text-blue-400 hover:text-white border border-blue-600/40 px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1">
                                                <i class="fas fa-file-pdf"></i> PDF
                                            </button>
                                        </form>

                                        <!-- Eliminar -->
                                        <form action="index.php?action=eliminarReserva" method="POST"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar la reserva? Esta acción no se puede deshacer.');">
                                            <input type="hidden" name="id_reserva" value="<?php echo $r['id']; ?>">
                                            <button type="submit"
                                                    title="Eliminar reserva"
                                                    class="bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white border border-red-600/40 px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-800 text-slate-500 text-xs">
                    Total: <span class="text-white font-bold"><?php echo count($reservas); ?></span> reserva(s)
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer style="background-color: #090e1a; padding: 40px 20px; text-align: center; border-top: 1px solid #1e293b;">
        <div class="container">
            <p class="text-slate-500">&copy; 2026 HOMFORT - Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
