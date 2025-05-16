<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../lib/fpdf/fpdf.php';
require_once __DIR__ . '/../../businesslogic/InvoiceLogic.php';
require_once __DIR__ . '/../../businesslogic/UserLogic.php';

// CORS-Header
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Expose-Headers: Content-Disposition");

// Preflight-Anfrage abfangen
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Authentifizieren
$payload = authenticate();
$userId = $payload->id;
$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    http_response_code(400);
    echo json_encode(['message' => 'order_id missing']);
    exit;
}

// Bestellung holen
$order = InvoiceLogic::getOrderWithItemsAndUser((int)$orderId, (int)$userId);
if (!$order) {
    http_response_code(404);
    echo json_encode(['message' => 'Order was not found']);
    exit;
}

// Nutzer laden
$userLogic = new UserLogic();
$user = $userLogic->findUserById($userId);

// PDF generieren (aber noch nicht senden!)
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');

// Eigene Firmenanschrift
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, 'ManDaMel Digital Shop', 0, 1,'R');
$pdf->Cell(0, 5, 'Webstrasse 42', 0, 1,'R');
$pdf->Cell(0, 5, '1010 Wien', 0, 1,'R');
$pdf->Cell(0, 5, 'Austria', 0, 1,'R');
$pdf->Cell(0, 5, 'E-Mail: support@mandamel.com', 0, 1,'R');
$pdf->Cell(0, 5, 'Phone: +43 660 1234567', 0, 1,'R');
$pdf->Cell(0, 5, 'Website: www.weihmann.at', 0, 1,'R');
$pdf->Ln(10);


$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, 'Invoice Number: ' . $order['invoice_number'], 0, 0,'L');
$pdf->Cell(90, 10, 'Date: ' . date('d.m.Y', strtotime($order['created_at'])), 0, 1,'R');
$pdf->Ln(10);

// Adresse
$pdf->Cell(0, 10, 'Invoice to:', 0, 1);
$pdf->Cell(0, 10, $user['given_name'] . ' ' . $user['surname'], 0, 1);
$pdf->Cell(0, 10, $user['street'] . ' ' . $user['house_number'], 0, 1);
$pdf->Cell(0, 10, $user['postal_code'] . ' ' . $user['city'], 0, 1);
$pdf->Cell(0, 10, $user['country'], 0, 1);
$pdf->Ln(10);

// Produkte
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, 'Product', 1);
$pdf->Cell(40, 10, 'Price (Euro)', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
foreach ($order['items'] as $item) {
    $pdf->Cell(100, 10, $item['name'], 1);
    $pdf->Cell(40, 10, number_format($item['price'], 2, ',', '.'), 1);
    $pdf->Ln();
}

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, 'Total', 1);
$pdf->Cell(40, 10, number_format($order['total'], 2, ',', '.'), 1);
$pdf->Ln(20);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, 'Thank you for your purchase!', 0, 1, 'C');

// Jetzt den Inhalt als String generieren (kein direktes Output!)
$pdfContent = $pdf->Output('S'); // 'S' für String-Return

// Header für PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Invoice_' . $order['invoice_number'] . '.pdf"');
header('Content-Length: ' . strlen($pdfContent));

// Inhalt senden
echo $pdfContent;
exit;
