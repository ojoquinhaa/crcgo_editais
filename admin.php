<?php
session_start();
require "conn.php";
if (!isset($_SESSION['logged']) || empty($_SESSION['logged'])) {
    header("Location: singup.php");
    exit;
}

$sql = "SELECT * FROM procurements;";
$query = $conn->query($sql);

$result = $query->fetchAll();

if (count($_POST) !== 0) {
    $process_number = $_POST['process_number'];
    $object = $_POST['object'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $date = $_POST['date'];
    $file = $_FILES['path'];

    if (empty($process_number) || empty($object) || empty($category) || empty($status) || empty($date) || empty($file)) {
        $_SESSION['error'] = "Os valores estão inválidos.";
    } else {
        $fileType = substr($file['name'], strpos($file['name'], ".") + 1);

        $file['name'] = uniqid() . "." . $fileType;
        $path = "/licitacoes/" . $file['name'];

        if ($file['size'] > 5000000) {
            $_SESSION['error'] = "O arquivo é pesado de mais.";
        } else {
            
            if (move_uploaded_file($file['tmp_name'], getcwd() . $path)) {
                $sql = "INSERT INTO procurements (process_number, object, category, status, date, path) VALUES ( :process_number , :object , :category , :status , :date , :path );";
                $stmt = $conn->prepare($sql);

                $stmt->bindParam(":process_number", $process_number);
                $stmt->bindParam(":object", $object);
                $stmt->bindParam(":category", $category);
                $stmt->bindParam(":status", $status);
                $stmt->bindParam(":date", $date);
                $stmt->bindParam(":path", $path);

                $result = $stmt->execute();

                print_r($result);
            } else {
                $_SESSION['error'] = "Falha ao fazer o upload do arquivo, tente novamente mais tarde!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br" style="min-height: 100%; height: 100%;">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Licitações - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body style="font-family: 'Montserrat' sans-serif; min-height: 100%; height: 100%;">
    <div class="w-full flex justify-center items-center h-full">
        <div class="container shadow-2xl m-auto" style="min-height: 90vh;">
            <div style="height: 10vh" class="flex justify-between items-center">
                <img style="width: 100px;" class="m-2" src="./img/crcgologo.png">
                <a href="/editais/logoff.php" class="m-2 text-blue-500">Logoff</a>
            </div>
            <div class="w-full" style="height: 80vh; max-height: 100%; overflow: auto">
                <?php
                if (!empty($_SESSION['error'])) {
                    echo (
                    "<div class='bg-red-500 text-white text-base p-2 text-center mb-2'>
                        <span>" .$_SESSION['error'] . "</span>
                        <span style='float: right; cursor: pointer' onclick='this.parentNode.remove()'>X</span>
                    </div>"
                    );
                    $_SESSION['error'] = false;
                }
                ?>
                <form class="m-auto" enctype="multipart/form-data" method="POST" action="admin.php"
                    style="width: 500px;">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-last-name">
                        Número da licitação
                    </label>
                    <input
                        class="appearance-none block mb-3 w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        name="process_number" type="text" placeholder="Insira o número da licitação">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-last-name">
                        Objeto
                    </label>
                    <textarea
                        class="appearance-none block h-full mb-3 w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        style="max-height: 200px; overflow: auto; resize: none" rows="3"
                        placeholder="Insira o objeto da licitação" name="object"></textarea>
                    <div class="mb-3 flex justify-between">
                        <div style="width: 200px">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                for="grid-last-name">
                                Modalidade
                            </label>
                            <select name="category"
                                class="form-select appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                <option value="" selected>Escolha a modalidade da licitação</option>
                                <option value="Dispensa">Dispensa</option>
                                <option value="Inexigibilidade">Inexigibilidade</option>
                                <option value="Pregão">Pregão Eletrônico</option>
                                <option value="Diálogo Competitivo">Diálogo Competitivo</option>
                            </select>
                        </div>
                        <div style="width: 200px">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                for="grid-last-name">
                                Status
                            </label>
                            <select name="status"
                                class="form-select appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                <option selected="selected" value="">Escolha o status da licitação</option>
                                <option value="Em Andamento">Em Andamento</option>
                                <option value="Concluído">Concluído</option>
                                <option value="Cancelado">Cancelado</option>
                                <option value="Anulado">Anulado</option>
                                <option value="Suspenso">Suspenso</option>
                                <option value="Fracassado">Fracassado</option>
                            </select>
                        </div>
                    </div>
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-last-name">
                        Data da licitação
                    </label>
                    <input name="date" type="date"
                        class="appearance-none block mb-3 w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                        for="grid-last-name">
                        Arquivo
                    </label>
                    <input name="path" type="file"
                        class="appearance-none block mb-3 w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <input type="submit" name="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white w-full font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        value="Criar" name="create">
                </form>
            </div>
        </div>
    </div>
</body>

</html>