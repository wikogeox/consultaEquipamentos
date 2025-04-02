<?php
require_once __DIR__ . '/vendor/autoload.php';
include 'config.php';

if (!isset($_GET['id'])) {
    die("ID inválido");
}

$id = (int)$_GET['id'];
$query = "SELECT 
            c.id, c.nome_computador, c.SO, c.modelo, c.serial_number, 
            c.processador, c.bios, c.memoria_ram, c.discos, c.license_key, 
            p.nome_pavilhao AS edificio, s.nome_sala AS sala, c.data_importacao
          FROM computadores c
          LEFT JOIN salas s ON c.id_sala = s.id
          LEFT JOIN pavilhao p ON c.id_pavilhao = p.id
          WHERE c.id = $id";

$result = $liga->query($query);
if ($result->num_rows == 0) {
    die("Computador não encontrado");
}

$row = $result->fetch_assoc();

// Criar PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Gestão');
$pdf->SetTitle('Detalhes do Computador');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Conteúdo do PDF
$html = '
    <h2>Detalhes do Computador</h2>
    <table border="1" cellpadding="5">
        <tr><td><b>Nome:</b></td><td>' . $row['nome_computador'] . '</td></tr>
        <tr><td><b>Sistema Operativo:</b></td><td>' . $row['SO'] . '</td></tr>
        <tr><td><b>Modelo:</b></td><td>' . $row['modelo'] . '</td></tr>
        <tr><td><b>Serial Number:</b></td><td>' . $row['serial_number'] . '</td></tr>
        <tr><td><b>Processador:</b></td><td>' . $row['processador'] . '</td></tr>
        <tr><td><b>BIOS:</b></td><td>' . $row['bios'] . '</td></tr>
        <tr><td><b>Memória RAM:</b></td><td>' . $row['memoria_ram'] . '</td></tr>
        <tr><td><b>Discos:</b></td><td>' . $row['discos'] . '</td></tr>
        <tr><td><b>License Key:</b></td><td>' . $row['license_key'] . '</td></tr>
        <tr><td><b>Edifício:</b></td><td>' . $row['edificio'] . '</td></tr>
        <tr><td><b>Sala:</b></td><td>' . $row['sala'] . '</td></tr>
        <tr><td><b>Data de Importação:</b></td><td>' . $row['data_importacao'] . '</td></tr>
    </table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Saída do PDF
$pdf->Output('computador_' . $id . '.pdf', 'D');
?>
