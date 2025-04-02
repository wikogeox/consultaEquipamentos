<?php
// Conexão à base de dados
$liga = mysqli_connect('localhost', 'root', 'root');
if (!$liga) {
    echo "<h2>ERROR!!! Falha na ligação ao Servidor!</h2>";
    exit;
}
mysqli_select_db($liga, 'inventarioti');
?>