<?php
include 'config.php';

// Definir o número de registros por página
$registros_por_pagina = 5;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $registros_por_pagina;

// Filtro de pesquisa
$search = isset($_GET['search']) ? $liga->real_escape_string($_GET['search']) : '';
$where_clause = "";
if ($search) {
    $where_clause = "WHERE c.nome_computador LIKE '%$search%' OR s.nome_sala LIKE '%$search%'";
}

// Consulta para buscar os computadores com paginação
$query = "SELECT 
            c.id, 
            c.nome_computador, 
            c.SO, 
            c.modelo, 
            c.serial_number, 
            c.processador, 
            c.bios, 
            c.memoria_ram, 
            c.discos, 
            c.license_key, 
            p.nome_pavilhao AS edificio, 
            s.nome_sala AS sala, 
            c.data_importacao
          FROM computadores c
          LEFT JOIN salas s ON c.id_sala = s.id
          LEFT JOIN pavilhao p ON c.id_pavilhao = p.id
          $where_clause
          ORDER BY c.data_importacao DESC
          LIMIT $offset, $registros_por_pagina";

$result = $liga->query($query);

// Contar o total de registros para paginação
$query_count = "SELECT COUNT(*) as total FROM computadores c LEFT JOIN salas s ON c.id_sala = s.id $where_clause";
$result_count = $liga->query($query_count);
$total_registros = $result_count->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Equipamentos</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="importar.php" target="_blank">Importar</a></li>
            <li><a href="index.php" target="_blank">Consultar</a></li>
        </ul>
    </nav>

    <h1>Consulta de Computadores e Equipamentos</h1>

    <div class="filtros">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Pesquisar por nome ou sala" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Pesquisar</button>
        </form>
    </div>

    <table class="tabela-computadores">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome Computador</th>
                <th>Sistema Operativo</th>
                <th>Modelo</th>
                <th>Serial Number</th>
                <th>Processador</th>
                <th>BIOS</th>
                <th>Memória RAM</th>
                <th>Discos</th>
                <th>License Key</th>
                <th>Edifício</th>
                <th>Sala</th>
                <th>Data de Importação</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="computador-linha" data-id="<?= $row['id'] ?>">
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['nome_computador']) ?></td>
                    <td><?= htmlspecialchars($row['SO']) ?></td>
                    <td><?= htmlspecialchars($row['modelo']) ?></td>
                    <td><?= htmlspecialchars($row['serial_number']) ?></td>
                    <td><?= htmlspecialchars($row['processador']) ?></td>
                    <td><?= htmlspecialchars($row['bios']) ?></td>
                    <td><?= htmlspecialchars($row['memoria_ram']) ?></td>
                    <td><?= htmlspecialchars($row['discos']) ?></td>
                    <td><?= htmlspecialchars($row['license_key']) ?></td>
                    <td><?= htmlspecialchars($row['edificio']) ?></td>
                    <td><?= htmlspecialchars($row['sala']) ?></td>
                    <td><?= htmlspecialchars($row['data_importacao']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="paginacao">
        <p>Página <?= $pagina_atual ?> de <?= $total_paginas ?></p>

        <?php if ($pagina_atual > 1): ?>
            <a href="?pagina=1&search=<?= urlencode($search) ?>" class="botao">Primeira</a>
            <a href="?pagina=<?= $pagina_atual - 1 ?>&search=<?= urlencode($search) ?>" class="botao">Anterior</a>
        <?php endif; ?>

        <?php if ($pagina_atual < $total_paginas): ?>
            <a href="?pagina=<?= $pagina_atual + 1 ?>&search=<?= urlencode($search) ?>" class="botao">Próxima</a>
            <a href="?pagina=<?= $total_paginas ?>&search=<?= urlencode($search) ?>" class="botao">Última</a>
        <?php endif; ?>
    </div>

    <div id="modal" class="modal">
    <div class="modal-content">
        <p>Deseja exportar este computador para PDF?</p>
        <button id="confirmar-exportacao">Sim</button>
        <button id="cancelar-exportacao">Não</button>
    </div>
</div>

<script>
    let computadorId = null;

    document.querySelectorAll('.computador-linha').forEach(row => {
        row.addEventListener('click', function() {
            computadorId = this.dataset.id;
            document.getElementById('modal').style.display = 'block';
        });
    });

    document.getElementById('cancelar-exportacao').addEventListener('click', function() {
        document.getElementById('modal').style.display = 'none';
    });

    document.getElementById('confirmar-exportacao').addEventListener('click', function() {
        window.location.href = 'exportar_pdf.php?id=' + computadorId;
        document.getElementById('modal').style.display = 'none';
    });
</script>

</body>
</html>
