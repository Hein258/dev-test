<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dev Test 1</title>
    <link rel="stylesheet" href="assets/styles.css"/>
</head>
<body>

<?php

require_once('includes/config.php');

$dateValidation = '^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{4})$';

if(isset($_POST['user_insert'])){
    createUser($_POST);
}

?>

<div class="container">

    <div class="form-contain">

        <h1>Dev Test 1</h1>

        <?php 
            if(isset($_SESSION['message'])){
                echo '
                    <div class="'.$_SESSION['message']['type'].'-message message">'.$_SESSION['message']['text'].'</div>
                ';
            }
        ?>

        <form method="POST">

            <label for="first">First Name</label>
            <input id="first" name="first_name" placeholder="First Name" type="text" value="<?= isset($_POST['first_name']) ? $_POST['first_name'] : '' ?>" required/>

            <label for="surname">Surname</label>
            <input id="surname" name="surname" placeholder="Surname" type="text" value="<?= isset($_POST['surname']) ? $_POST['surname'] : '' ?>" required/>

            <label for="id_no">ID Number</label>
            <input id="id_no" name="id_no" placeholder="ID Number" type="text" value="<?= isset($_POST['id_no']) ? $_POST['id_no'] : '' ?>" pattern="^(\d{13})$" title="13 digit ID Number" maxlength="13" minlength="13" required/>

            <label for="dob">Date of birth</label>
            <input id="dob" class="mb-0" name="dob" placeholder="dd/mm/yyyy" type="tel" pattern="<?= $dateValidation ?>" value="<?= isset($_POST['dob']) ? $_POST['dob'] : '' ?>" title="dd/mm/yyyy" required>
            <small class="mb">Please ensure to use the following date format <strong>(dd/mm/yyyy)</strong></small>

            <div class="btn-contain">
                <input class="btn btn-submit" type="submit" value="Submit" name="user_insert"/>
                <button  class="btn btn-cancel" onClick="window.location.reload();">Cancel</button>
            </div>

        </form>
    </div>

</div>
    
</body>
</html>