<?php
// Ruta relativa a la librería FPDF (funciona en cualquier PC)
require_once __DIR__ . '/../Lib/fpdf.php';

error_reporting(0);
ini_set('display_errors', 0);

class MiPDF extends FPDF {

    function Header() {
        // Logo
        $logoPath = __DIR__ . '/../img/logo.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 8, 6, 23);
        }
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(15, 23, 42);      // slate-900
        $this->SetXY(40, 10);
        $this->Cell(0, 8, 'HOMFORT', 0, 1, 'L');
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(100, 116, 139);   // slate-400
        $this->SetX(40);
        $this->Cell(0, 6, utf8_decode('Comprobante de Reserva'), 0, 1, 'L');

        // Línea separadora dorada
        $this->SetDrawColor(217, 119, 6);     // amber-600
        $this->SetLineWidth(0.8);
        $this->Line(10, 28, 200, 28);
        $this->Ln(8);
    }

    function Footer() {
        $this->SetY(-18);
        $this->SetDrawColor(203, 213, 225);
        $this->SetLineWidth(0.3);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(2);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(148, 163, 184);
        $this->Cell(0, 6, utf8_decode('HOMFORT © 2026 - Todos los derechos reservados'), 0, 0, 'L');
        $this->Cell(0, 6, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'R');
    }
}

// ── Verificar que $reserva existe ──
if (empty($reserva)) {
    die('Error: No se encontraron datos de la reserva.');
}

$pdf = new MiPDF();
$pdf->SetAuthor('HOMFORT');
$pdf->AddPage();
$pdf->SetMargins(15, 35, 15);
$pdf->SetAutoPageBreak(true, 20);

// ══════════════════════════════════════
// BLOQUE: Número de reserva + fecha emisión
// ══════════════════════════════════════
$pdf->SetFillColor(217, 119, 6);    // amber-600
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 13);
$pdf->Ln(1);

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(100, 116, 139);
$fechaEmision = isset($reserva['created_at'])
    ? date('d/m/Y H:i', strtotime($reserva['created_at']))
    : date('d/m/Y H:i');
$pdf->Cell(0, 6, utf8_decode('Emitido el: ') . $fechaEmision, 0, 1, 'L');
$pdf->Ln(4);

// ══════════════════════════════════════
// Helper: fila de dos columnas
// ══════════════════════════════════════
function fila($pdf, $label, $valor, $fill = false) {
    $pdf->SetFillColor(248, 250, 252);
    $pdf->SetTextColor(71, 85, 105);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(60, 9, utf8_decode($label), 0, 0, 'L', $fill);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(15, 23, 42);
    $pdf->Cell(0, 9, utf8_decode($valor), 0, 1, 'L', $fill);
}

// ══════════════════════════════════════
// SECCIÓN: Detalles de la estancia
// ══════════════════════════════════════
$pdf->SetFillColor(241, 245, 249);
$pdf->SetTextColor(15, 23, 42);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 9, utf8_decode('  DETALLES DE LA ESTANCIA'), 'B', 1, 'L', true);
$pdf->Ln(2);

// Calcular noches
$inicio = new DateTime($reserva['fecha_inicio']);
$fin    = new DateTime($reserva['fecha_final']);
$noches = max(1, $inicio->diff($fin)->days);

fila($pdf, 'Habitacion:',    'No. ' . $reserva['num_habitacion'] . '  ' . $reserva['tipo_habitacion'], false);
fila($pdf, 'Fecha de entrada:', date('d/m/Y', strtotime($reserva['fecha_inicio'])), true);
fila($pdf, 'Fecha de salida:', date('d/m/Y', strtotime($reserva['fecha_final'])), false);
fila($pdf, 'Noches:',         $noches . ($noches == 1 ? ' noche' : ' noches'), true);
fila($pdf, 'Personas:',       $reserva['num_personas'], false);

if (!empty($reserva['num_camas'])) {
    fila($pdf, 'Camas:', $reserva['num_camas'], true);
}

$pdf->Ln(4);

// ══════════════════════════════════════
// SECCIÓN: Pago
// ══════════════════════════════════════
$pdf->SetFillColor(241, 245, 249);
$pdf->SetTextColor(15, 23, 42);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 9, utf8_decode('  INFORMACIÓN DE PAGO'), 'B', 1, 'L', true);
$pdf->Ln(2);

fila($pdf, 'Metodo de pago:', $reserva['metodo_pago'], false);

// Total en grande
$pdf->Ln(2);
$pdf->SetFillColor(217, 119, 6);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 12, utf8_decode('  TOTAL:   $' . number_format($reserva['precio'], 0, ',', '.')), 0, 1, 'L', true);
$pdf->Ln(6);

// ══════════════════════════════════════
// SECCIÓN: Peticiones especiales
// ══════════════════════════════════════
if (!empty($reserva['Descripcion'])) {
    $pdf->SetFillColor(241, 245, 249);
    $pdf->SetTextColor(15, 23, 42);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 9, utf8_decode('  PETICIONES ESPECIALES'), 'B', 1, 'L', true);
    $pdf->Ln(2);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(71, 85, 105);
    $pdf->SetFillColor(248, 250, 252);
    $pdf->MultiCell(0, 8, utf8_decode($reserva['Descripcion']), 0, 'L', true);
    $pdf->Ln(4);
}

// ══════════════════════════════════════
// MENSAJE FINAL
// ══════════════════════════════════════
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(100, 116, 139);
$pdf->MultiCell(0, 6, utf8_decode('Gracias por elegir HOMFORT. Presente este comprobante al momento del check-in. Para cualquier consulta comuniquese con nosotros.'), 0, 'C');

// ── Generar PDF en el navegador ──
$pdf->Output('I', 'Reserva_' . $reserva['id'] . '.pdf');
exit;
?>
