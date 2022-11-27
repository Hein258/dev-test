<?php 
//------ Define my database connection
define("HOST", "localhost"); 	// The host you want to connect to.
define("USER", "root"); 		// The database username.
define("PASSWORD", "");         // The database password. 
//define("DATABASE", "dev_test"); // The database name.

set_time_limit(1200);
ini_set('memory_limit', '-1');

$mysqli = new mysqli(HOST, USER, PASSWORD);

if($mysqli->connect_error){

    echo "Failed to connect to MySQL: ".$mysqli->connect_error;
    exit();

}
else{
    
    try {

        $createDB = $mysqli->query('CREATE DATABASE IF NOT EXISTS dev_test1') or (throw new Exception($mysqli->error));

        $db_selected = $mysqli->select_db('dev_test1') or (throw new Exception($mysqli->error));

    } 
    catch (\Throwable $e) {
        die($e->getMessage());
    }

    require_once(__DIR__.'/functions.php');
}

?>