<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;
    use PhpOffice\PhpSpreadsheet\Style\Border;

    if (empty($reservas) || empty($usuario)) {
        die('Error: No hay datos.');
    }

    // Limpiar CUALQUIER output previo
    if (ob_get_length()) ob_end_clean();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Mis Reservas');
    // ── Anchos ──
    $sheet->getColumnDimension('A')->setWidth(8);
    $sheet->getColumnDimension('B')->setWidth(16);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(16);
    $sheet->getColumnDimension('E')->setWidth(16);
    $sheet->getColumnDimension('F')->setWidth(12);
    $sheet->getColumnDimension('G')->setWidth(18);
    $sheet->getColumnDimension('H')->setWidth(18);

    // ── Fila 1: Título ──
    $sheet->mergeCells('A1:H1');
    $sheet->setCellValue('A1', 'HOMFORT — MIS RESERVAS');
    $sheet->getStyle('A1')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D97706']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    ]);
    $sheet->getRowDimension(1)->setRowHeight(36);

    // ── Fila 2: Usuario ──
    $sheet->mergeCells('A2:H2');
    $nombre = $usuario['name'] . ' ' . $usuario['last_name'];
    $sheet->setCellValue('A2', "Usuario: $nombre  |  Email: {$usuario['email']}  |  Generado: " . date('d/m/Y H:i'));
    $sheet->getStyle('A2')->applyFromArray([
        'font'      => ['size' => 10, 'color' => ['rgb' => '94A3B8']],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    ]);
    $sheet->getRowDimension(2)->setRowHeight(22);

    // ── Fila 3: Encabezados ──
    $headers = ['Habitación', 'Tipo', 'Entrada', 'Salida', 'Personas', 'Método Pago', 'Total', 'Estado'];
    $cols = ['A','B','C','D','E','F','G','H'];
    foreach ($headers as $i => $h) {
        $sheet->setCellValue($cols[$i] . '3', $h);
    }
    $sheet->getStyle('A3:H3')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        'borders'   => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => 'D97706']]],
    ]);
    $sheet->getRowDimension(3)->setRowHeight(24);

    // ── Filas de datos ──
    $estados = [1 => 'Activa', 2 => 'Cancelada', 3 => 'Completada'];
    $fila = 4;

    foreach ($reservas as $r) {
        $colorFondo  = ($fila % 2 === 0) ? '1E293B' : '0F172A';
        $estadoLabel = $estados[$r['id_estado']] ?? 'Activa';

        $sheet->setCellValue('A' . $fila, 'Hab. ' . $r['num_habitacion']);
        $sheet->setCellValue('B' . $fila, $r['tipo_habitacion']);
        $sheet->setCellValue('C' . $fila, date('d/m/Y', strtotime($r['fecha_inicio'])));
        $sheet->setCellValue('D' . $fila, date('d/m/Y', strtotime($r['fecha_final'])));
        $sheet->setCellValue('E' . $fila, $r['num_personas']);
        $sheet->setCellValue('F' . $fila, $r['metodo_pago']);
        $sheet->setCellValue('G' . $fila, '$' . number_format($r['precio'], 0, ',', '.'));
        $sheet->setCellValue('H' . $fila, $estadoLabel);

        $sheet->getStyle("A{$fila}:H{$fila}")->applyFromArray([
            'font'      => ['size' => 10, 'color' => ['rgb' => 'CBD5E1']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $colorFondo]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '334155']]],
        ]);

        $colorEstado = match((int)$r['id_estado']) {
            2 => 'EF4444',
            3 => '3B82F6',
            default => '22C55E',
        };
        $sheet->getStyle("H{$fila}")->getFont()->getColor()->setRGB($colorEstado);
        $sheet->getStyle("H{$fila}")->getFont()->setBold(true);
        $sheet->getRowDimension($fila)->setRowHeight(20);
        $fila++;
    }

    // ── Fila total ──
    $sheet->mergeCells("A{$fila}:G{$fila}");
    $sheet->setCellValue("A{$fila}", 'TOTAL DE RESERVAS: ' . count($reservas));
    $sheet->setCellValue("H{$fila}", '$' . number_format(array_sum(array_column($reservas, 'precio')), 0, ',', '.'));
    $sheet->getStyle("A{$fila}:H{$fila}")->applyFromArray([
        'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D97706']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    ]);
    $sheet->getRowDimension($fila)->setRowHeight(24);

    // ── Guardar en archivo temporal y luego enviarlo ──
    $tmpFile = tempnam(sys_get_temp_dir(), 'excel_') . '.xlsx';
    $writer  = new Xlsx($spreadsheet);
    $writer->save($tmpFile);

    // Headers limpios
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Reservas_' . date('Ymd') . '.xlsx"');
    header('Content-Length: ' . filesize($tmpFile));
    header('Cache-Control: max-age=0');
    header('Pragma: public');

    // Enviar archivo y limpiar
    readfile($tmpFile);
    unlink($tmpFile);
    exit;
?>