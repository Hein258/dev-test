<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dev Test 2</title>
    <link rel="stylesheet" href="assets/styles.css"/>
</head>
<body>

<?php

require_once('includes/config.php');

if(isset($_POST['user_insert'])){

    genCSV($_POST['records_count']);
};

if(isset($_POST['csv_import'])){

    importCSV($_FILES);
};


?>

<div class="container">

    <div class="form-contain">

        <h1>Dev Test 2</h1>

        <h3>Generate CSV</h3>

        <?php 
            if(isset($_SESSION['message'])){
                echo '
                    <div class="'.$_SESSION['message']['type'].'-message message">'.$_SESSION['message']['text'].'</div>
                ';
            }
        ?>

        <form method="POST">

            <label for="records_count">NO. of records to generate</label>
            <input id="records_count" name="records_count" type="number" max="1000000"/>

            <div class="btn-contain">
                <input class="btn btn-submit" type="submit" value="Submit" name="user_insert"/>
            </div>

        </form>

        <hr/>

        <h3>CSV Import</h3>

        <?php 
            if(isset($_SESSION['csv_message'])){
                echo '
                    <div class="'.$_SESSION['csv_message']['type'].'-message message">'.$_SESSION['csv_message']['text'].'</div>
                ';
            }
        ?>

        <form method="POST" enctype="multipart/form-data">

            <label for="csv_file">CSV File</label>
            <input id="csv_file" name="csv_file" type="file" required/>

            <div class="btn-contain">
                <input class="btn btn-submit" type="submit" value="Submit" name="csv_import"/>
            </div>

        </form>

    </div>

</div>

    
</body>
</html>