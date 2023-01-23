<?php  
/** Conexão com o banco de dados MYSQL */
DEFINE("HOST", "DB_HOST"); // Host mysql
DEFINE("USER", "DB_USER"); // Usuario mysql
DEFINE("PASS", "DB_PASS"); // Senha mysql
DEFINE("DB", "DB_NAME"); // Nome do banco de dados mysql
try {
    // Tentando estabelecer conexão com banco de dados
    $mysqlConnectionString = "mysql:host=" . HOST . ";dbname=" . DB;
    $conn = new PDO($mysqlConnectionString, USER, PASS); 
} catch(PDOException $e) {
    // Retornando erro
    echo($e);
}
?>