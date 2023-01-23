<?php
session_start();
require 'conn.php';
                
$sql = "SELECT * FROM procurements;";
$query = $conn->query($sql);

$result = $query->fetchAll();

if (empty($_SESSION['editals'])) {
    $_SESSION['editals'] = $query->fetchAll();
}

if (count($_POST) !== 0) {
    $category = $_POST['category'];
    $period = "%" . $_POST['date'] . "%";
    $status = $_POST['status'];

    $sql = "SELECT * FROM procurements WHERE category = :category AND status = :status AND created_at LIKE :period;";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(":category", $category);
    $stmt->bindParam(":period", $period);
    $stmt->bindParam(":status", $status);

    $stmt->execute();

    $result = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Licitações</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body style="font-family: 'Montserrat' sans-serif">
    <div class="w-full">
        <div class="container m-auto flex justify-center items-center">
            <img src="./img/crcgologo.png" alt="logo" style="width: 300px" />
        </div>
        <div class="w-full flex justify-center items-center text-white"
            style="background-image: url(./img/fundocrcgo.webp); height: 40vh">
            <div>
                <h1 class="text-5xl">LICITAÇÕES</h1>
                <p class="text-base mt-3">CONFIRA AS LICITAÇÕES E EDITAIS</p>
            </div>
        </div>
        <div class="container m-auto">
            <h1 class="text-center text-3xl mt-7 mb-7 text-blue-700">
                <strong>LICITAÇÕES / EDITAIS</strong>
            </h1>
            <hr />
            <div class="container m-auto">
                <form class="ml-auto mr-auto mt-7 mb-7" name="search" method="POST" action="" style="width: 80%">
                    <label class="mt-3 mb-3 text-lg">Modalidade</label>
                    <select
                        class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                        name="category" required>
                        <option value="" selected>Tipo de modalidade</option>
                        <option value="Dispensa">Dispensa</option>
                        <option value="Inexigibilidade">Inexigibilidade</option>
                        <option value="Pregão">Pregão Eletrônico</option>
                        <option value="Diálogo Competitivo">Diálogo Competitivo</option>
                    </select>

                    <label class="mt-3 mb-3 text-lg">Período</label>
                    <select
                        class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                        name="date" required>
                        <option selected value="">Selecione o ano</option>
                        <?php
                        foreach($result as $row) {
                            $date = date_create($row[7]);
                            $year = date_format($date, "Y");
                            echo ("<option value='$year'>$year</option>");
                        }
                        ?>
                    </select>

                    <label class="mt-3 mb-3 text-lg">Status</label>
                    <select
                        class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                        name="status" required>
                        <option selected="selected" value="">Escolha o status da licitação</option>
                        <option value="Em Andamento">Em Andamento</option>
                        <option value="Concluído">Concluído</option>
                        <option value="Cancelado">Cancelado</option>
                        <option value="Anulado">Anulado</option>
                        <option value="Suspenso">Suspenso</option>
                        <option value="Fracassado">Fracassado</option>
                    </select>

                    <input type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full mt-4 cursor-pointer"
                        value="Procurar" />
                </form>
                <hr />
                <div class="container m-auto" style="overflow: auto; max-height: 500px; overflow: auto">
                    <table class="table-auto w-full mt-7 text-slate-700" style="min-width: 500px" ;>
                        <thead style="border-bottom: solid #000 2px" class="text-blue-500">
                            <tr>
                                <th style="border-left: 1px solid lightslategrey;">Numero de licitação</th>
                                <th
                                    style="border-left: 1px solid lightslategrey; min-width: 150px; max-width: 150px; max-height: 100px; overflow: auto">
                                    Objeto</th>
                                <th style="border-left: 1px solid lightslategrey;">Modalidade</th>
                                <th style="border-left: 1px solid lightslategrey;">Data</th>
                                <th style="border-left: 1px solid lightslategrey;">Status</th>
                                <th style="border-left: 1px solid lightslategrey;">Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (count($result) !== 0) {
                              foreach($result as $row) {
                                    $path = "/edital" . $row[6];

                                    $lic_date = date_create($row[5]);
                                    $format_lic_date = date_format($lic_date, "d/m/Y");
                                echo ("
                                  <tr style='border-bottom: solid #94a3b8 2px;'>
                                    <th style='border-left: 1px solid lightslategrey;'>$row[1]</th>
                                    <th style='border-left: 1px solid lightslategrey; min-width: 50px; max-width: 50px;'>$row[2]</th>
                                    <th style='border-left: 1px solid lightslategrey;'>$row[3]</th>
                                    <th style='border-left: 1px solid lightslategrey;'>$format_lic_date</th>
                                    <th style='border-left: 1px solid lightslategrey;'>$row[4]</th>
                                    <th style='border-left: 1px solid lightslategrey;'>
                                        <a href='$path' download style='text-align: center'><i style='width: 100px' class='fa fa-download'></i></a>
                                    </th>
                                </tr>
                                    
                                ");
                              }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php 
                    if (count($result) === 0) {
                      echo ("
                      <div class='bg-red-500 p-5 text-white w-full text-center container' style='min-width: 500px'>
                        <span class='text-xl'>Não foram encontradas nenhuma licitações</span>
                      </div>
                      ");
                    }
                    ?>
                </div>
            </div>
        </div>
        <footer style="
          background-color: #014c93;
          margin-top: 15vh;
          margin-bottom: 0vh;
          padding-bottom: 0;
          position: relative;
          bottom: -2vh;
        " class="text-center text-white">
            <p style="padding-bottom: 1rem; padding-top: 1rem">
                Conselho Regional de Contabilidade do Estado de Goiás - 2022 - Todos
                os direitos reservados
            </p>
        </footer>
    </div>
</body>

</html>