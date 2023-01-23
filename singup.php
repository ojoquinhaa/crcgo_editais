<?php
require 'conn.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$user = $_POST['user'];
$password = $_POST['password'];
if (empty($user) || empty($password)) {
$_SESSION['error'] = true;
} else {
$sql = "SELECT * FROM admin WHERE user = :user AND password = :password;";

$stmt = $conn->prepare($sql);

$stmt->bindParam("user", $user);
$stmt->bindParam("password", $password);

$stmt->execute();

$result = $stmt->fetchAll();
if (count($result) === 0) {
$_SESSION['error'] = true;
} else {
$_SESSION['logged'] = true;
header("Location: admin.php");
exit;
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
    <title>Licitações</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body style="font-family: 'Montserrat' sans-serif; min-height: 100%; height: 100%;">
    <div class="w-full h-full">
        <div class="container h-full m-auto">
            <div class="w-full h-full flex justify-center items-center">
                <div style="width: 300px;">
                    <div class="text-center w-full mb-3">
                        <img class="w-full" alt="logo" src="./img/crcgologo.png">
                    </div>
                    <div class="shadow-2xl w-full p-3" style="min-height: auto">
                        <form class="w-full h-full" action="singup.php" method="POST">
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800 leading-tight focus:outline-none focus:shadow-outline placeholder-gray-500 mb-3"
                                name="user" id="username" type="text" placeholder="Usuário" />
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-800 mb-3 leading-tight focus:outline-none focus:shadow-outline placeholder-gray-500 mb-3"
                                name="password" id="password" type="password" placeholder="Senha" />
                            <?php 
                            if ($_SESSION['error']) {
                            echo ("
                                <div class='text-center mb-3'>
                                    <span class='text-red-500 text-sm'>As credenciáis estão inválidas.</span>
                                </div>
                            ");

                                $_SESSION['error'] = false;
                            }
                            ?>
                            <input
                                class="bg-blue-500 hover:bg-blue-700 text-white w-full font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                type="submit" value="Login" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>