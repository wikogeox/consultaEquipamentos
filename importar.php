<?php
include 'config.php';
// Buscar os pavilhões na base de dados
$pavilhoes_result = $liga->query("SELECT nome_pavilhao FROM pavilhao");

// Buscar as salas na base de dados 
$salas_result = $liga->query("SELECT nome_sala FROM salas");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['relatorio'])) {
    $mensagem = "";

    // Criar pasta de uploads se não existir
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Upload do ficheiro HTML
    $relatorio_nome = basename($_FILES['relatorio']['name']);
    $relatorio_caminho = "uploads/" . $relatorio_nome;

    if (move_uploaded_file($_FILES['relatorio']['tmp_name'], $relatorio_caminho)) {
        $mensagem .= "Relatório carregado com sucesso!<br>";

        // Ler o conteúdo do HTML
        $html = file_get_contents($relatorio_caminho);

        // Criar um DOMDocument para processar o HTML
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Para evitar warnings
        $dom->loadHTML($html);
        libxml_clear_errors();

        // Obter todas as tabelas do documento
        $tables = $dom->getElementsByTagName('table');

        if ($tables->length > 0) {
            $dados = [];

            // Pegar a primeira tabela
            $rows = $tables->item(0)->getElementsByTagName('tr');
            foreach ($rows as $row) {
                $cols = $row->getElementsByTagName('td');
                if ($cols->length == 2) {
                    $key = trim($cols->item(0)->textContent);
                    $value = trim($cols->item(1)->textContent);
                    $dados[$key] = $value;
                }
            }

            // Mapear os dados extraídos para os campos da base de dados
            $nome_computador = $dados['Computer Name'] ?? 'Desconhecido';
            $nome_dominio = $dados['Domain Name'] ?? 'Desconhecido';
            $so = $dados['Operating System'] ?? 'Desconhecido';
            $modelo = $dados['Model'] ?? 'Desconhecido';
            $serial_number = $dados['Serial Number'] ?? 'Desconhecido';
            $processador = $dados['Processor Description'] ?? 'Desconhecido';
            $memoria_ram = $dados['Total Memory'] ?? 'Desconhecido';
            $discos = $dados['Total Hard Drive'] ?? 'Desconhecido';
            $bios = $dados['BIOS Version'] ?? 'Desconhecido';
            $license_key = $_POST['license_key'] ?? 'Desconhecido';

            // Obter o ID do pavilhão com base no nome selecionado
            $stmt = $liga->prepare("SELECT id FROM pavilhao WHERE nome_pavilhao = ?");
            $stmt->bind_param("s", $_POST['pavilhao']);
            $stmt->execute();
            $result = $stmt->get_result();
            $pavilhao_row = $result->fetch_assoc();
            $id_pavilhao = $pavilhao_row['id'] ?? null;

            // Obter o ID da sala com base no nome selecionado
            $stmt = $liga->prepare("SELECT id FROM salas WHERE nome_sala = ? AND id_pavilhao = ?");
            $stmt->bind_param("si", $_POST['sala'], $id_pavilhao);
            $stmt->execute();
            $result = $stmt->get_result();
            $sala_row = $result->fetch_assoc();
            $id_sala = $sala_row['id'] ?? null;

            // Verifica se encontrou os IDs corretamente
            if ($id_pavilhao === null || $id_sala === null) {
                die("Erro: Pavilhão ou Sala não encontrados na base de dados!");
            }

            $data_importacao = date('Y-m-d H:i:s');

            // Inserir na base de dados
            $stmt = $liga->prepare("INSERT INTO computadores (nome_computador, nome_dominio, SO, modelo, serial_number, processador, bios, memoria_ram, discos, license_key, id_pavilhao, id_sala, data_importacao) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssss", $nome_computador, $nome_dominio, $so, $modelo, $serial_number, $processador, $bios, $memoria_ram, $discos, $license_key, $id_pavilhao, $id_sala, $data_importacao);
            
            if ($stmt->execute()) {
                $mensagem .= "Dados do computador importados com sucesso!<br>";
            } else {
                $mensagem .= "Erro ao inserir os dados na base de dados.<br>";
            }
        } else {
            $mensagem .= "Nenhuma tabela encontrada no relatório!<br>";
        }
    } else {
        $mensagem .= "Erro ao carregar o relatório!<br>";
    }

    echo $mensagem;
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Relatórios</title>
    <link rel="stylesheet" href="importar.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav>
        <ul>
            <li><a href="importar.php" target="_blank">Importar</a></li>
            <li><a href="index.php" target="_blank">Consultar</a></li>
        </ul>
    </nav>
    <div class="container">
        <h2>Importar Relatórios</h2>
        <form id="importForm" enctype="multipart/form-data">
            <label for="pavilhao">Pavilhão:</label>
            <select name="pavilhao" id="pavilhao" required>
                <?php
                // Gerar opções dos pavilhões a partir da base de dados
                if ($pavilhoes_result->num_rows > 0) {
                    while ($row = $pavilhoes_result->fetch_assoc()) {
                        echo "<option value='" . $row['nome_pavilhao'] . "'>" . $row['nome_pavilhao'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum pavilhão disponível</option>";
                }
                ?>
            </select>

            <label for="sala">Sala:</label>
            <select name="sala" id="sala" required>
                <?php
                // Gerar opções das salas a partir da base de dados
                if ($salas_result->num_rows > 0) {
                    while ($row = $salas_result->fetch_assoc()) {
                        echo "<option value='" . $row['nome_sala'] . "'>" . $row['nome_sala'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhuma sala disponível</option>";
                }
                ?>
            </select>

            <label for="license_key">License key do SO:</label>
            <input type="text" name="license_key" id="license_key" placeholder="Insira o license key do SO" required>

            <label for="relatorio">Selecionar Relatório (HTML):</label>
            <input type="file" name="relatorio" id="relatorio" accept=".html" required>

            <label for="imagens">Imagens do Computador (máx. 5):</label>
            <input type="file" name="imagens[]" id="imagens" multiple accept="image/*">

            <button type="submit">Importar</button>
        </form>
        <div id="mensagem"></div>
    </div>

    <script>
        $(document).ready(function() {
            $("#importForm").submit(function(event) {
                event.preventDefault();
                
                // Validação dos campos obrigatórios
                var pavilhao = $("#pavilhao").val();
                var sala = $("#sala").val();
                var licenseKey = $("#license_key").val();
                var relatorio = $("#relatorio")[0].files.length;

                if (!pavilhao || !sala || !licenseKey || relatorio === 0) {
                    $("#mensagem").html("<p style='color: red;'>Todos os campos são obrigatórios!</p>");
                    return; // Impede o envio do formulário
                }
                
                var formData = new FormData(this);
                
                $.ajax({
                    url: "importar.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#mensagem").html("<p style='color: yellow;'>Importando...</p>");
                    },
                    success: function(response) {
                        $("#mensagem").html("<p style='color: green;'>" + response + "</p>");
                        // Recarga da página após 3 segundos
                        setTimeout(function() {
                            location.reload();
                        }, 3000); // 3000ms = 3 segundos
                    },
                    error: function() {
                        $("#mensagem").html("<p style='color:red;'>Erro ao importar!</p>");
                    }
                });
            });
        });
    </script>
</body>
</html>
