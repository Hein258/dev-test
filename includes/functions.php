<?php 

function genCSV(int $recordsCount){

    $names = [
        "Aaran",
        "Amelia",
        "Blossom",
        "Rodney",
        "Divine",
        "Kadie",
        "Elsie-Mae",
        "Monique",
        "Kathy",
        "Lyla-Rose",
        "Sion",
        "Jordi",
        "Marian",
        "Arif",
        "Holli",
        "Marion",
        "Shayan",
        "Ricky",
        "Teegan",
        "Chloe"
    ];

    $surnames = [
        "Orr",
        "Werner",
        "Mcdougall",
        "Douglas",
        "Correa",
        "Ford",
        "Santos",
        "Byrne",
        "Kouma",
        "Sherman",
        "Bloggs",
        "Hines",
        "Holt",
        "Phan",
        "Gibbs",
        "Mccaffrey",
        "Hodgson",
        "Rosas",
        "Hendrix",
        "Aldred"
    ];

    $usersArr = [];
    $userValidate = [];

    $id = 1;
    $records = 0;

    try {

        if(!is_dir('output')) {
            mkdir('output', 0777);
        }

        $fp = fopen('output/output.csv', 'w');
        fputcsv($fp, ['id', 'Name', 'Surname', 'Initials', 'Age', 'Date Of Birth']);

        for ($i=0; $i < $recordsCount; $i++) {

            $insert = false;

            $newUser = genCsvUser($names, $surnames, $id);

            while(checkArr($userValidate, $newUser) == false) {

                $newUser = genCsvUser($names, $surnames, $id);
                
            }

            $insert = true;

            $newUser['token'] = json_encode($newUser);

            if($insert && !empty($newUser)){
                
                $usersArr[] = $newUser;
                fputcsv($fp, $newUser);

                $id++;
                $records++;
                
            }
        }

        fclose($fp);

        $_SESSION['message'] = array(
            'text' => $records.' Record has been created. <a href="output/output.csv">Download Record</a>',
            'type' => 'success'
        );

        unset($_POST);

    } 
    catch (\Throwable $e) {
        $_SESSION['message'] = array(
            'text' => 'An error has occurred while generating the CSV. Please try agin',
            'type' => 'error'
        );
    }

}

function checkArr($userValidate, $newUser){

    //$userSting = implode($newUser);
    $userSting = json_encode($newUser);

    if(!in_array($userSting, $userValidate)){
        $userValidate[] = $userSting;
        return true;
    }
    else{
        return false;
    }

}

function genCsvUser($names, $surnames, $id){

    $nameSelect = $names[mt_rand(0, count($names) - 1)];
    $initial = substr($nameSelect, 0, 1);

    $dateGen = mt_rand(1, time());

    $bday = new DateTime(); // Your date of birth
    $bday->setTimestamp($dateGen);
    $today = new Datetime();

    $diff = $today->diff($bday);

    return [
        'id' => $id,
        'name' => $nameSelect,
        'surname' => $surnames[mt_rand(0, count($surnames) - 1)],
        'initial' => $initial,
        'age' => $diff->y,
        'date_of_birth' => $bday->format('d/m/Y'),
    ];
}

function createUser($postData){

    global $mysqli;

    try {

        $checkTable = $mysqli->query("SHOW TABLES LIKE 'users'") or ( throw new Exception("Error Processing Request: ".$mysqli->error));

        if($checkTable->num_rows == 0){

            $mysqli->query("
                CREATE TABLE `users` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `first_name` text NOT NULL,
                    `surname` text NOT NULL,
                    `id_no` varchar(13) NOT NULL,
                    `dob` date NOT NULL,
                    PRIMARY KEY (`id`)
                )
            ");

        }

        $idNumber = $postData['id_no'];

        if(is_numeric($idNumber) && strlen($idNumber) == 13){

            $idDate = substr($postData['id_no'], 0, 6);
            
            $idCheck = substr($idDate, 0, 1);
            $idYearEnd = substr($idDate, 0, 2);
            $idMonth = substr($idDate, 2, 2);
            $idDay = substr($idDate, 4, 2);

            if($idCheck <= 2){
                $idYear = 20;
                $idYear .= $idYearEnd;
            }
            else{
                $idYear = 19;
                $idYear .= $idYearEnd;
            }

            $date = explode('/', $postData['dob']);
            
            $idDOB = new DateTime($idYear.'/'.$idMonth.'/'.$idDay);
            $formDOB = new DateTime($date[2].'-'.$date[1].'-'.$date[0]);

            if($idDOB == $formDOB){

                $firstName = $mysqli->real_escape_string($postData['first_name']);
                $surname = $mysqli->real_escape_string($postData['surname']);
                $idNo = $mysqli->real_escape_string(str_replace(' ', '', $idNumber));
                $dob = $mysqli->real_escape_string($formDOB->format('Y/m/d'));

                $checkRecord = $mysqli->query('SELECT id_no from users WHERE id_no = "'.$idNo.'" ') or ( throw new Exception("Error Processing Request: ".$mysqli->error));

                if($checkRecord->num_rows == 0){

                    $insert = $mysqli->query('INSERT INTO users (first_name, surname, id_no, dob) VALUES ("'.$firstName.'", "'.$surname.'","'.$idNo.'","'.$dob.'")') or ( throw new Exception("Error Processing Request: ".$mysqli->error));

                    if($insert){

                        $_SESSION['message'] = array(
                            'text' => 'Record has been created. No duplicate ID was found.',
                            'type' => 'success'
                        );

                        unset($_POST);
                    }
                    else{
                        throw new Exception('An error occurred while creating the record. Please try again');
                    }
    
                }
                else{
                    throw new Exception('Record with this ID already exists');
                }
                
            }
            else{

                throw new Exception('ID number and Date of Birth does not match');

            }

        }
        else{

           throw new Exception('Invalid ID number supplied');
        }

    }
    catch (\Throwable $e) {
        $_SESSION['message'] = array(
            'text' => $e->getMessage(),
            'type' => 'error'
        );

    }

}

function importCSV($csvSelect){

    global $mysqli;

    $mysqli->begin_transaction();

    try {

        $checkTable = $mysqli->query("SHOW TABLES LIKE 'csv_import'") or ( throw new Exception("Error Processing Request: ".$mysqli->error));

        if($checkTable->num_rows == 0){

            $mysqli->query("
                CREATE TABLE `csv_import` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` text NOT NULL,
                    `surname` text NOT NULL,
                    `initials` text NOT NULL,
                    `age` varchar(3) NOT NULL,
                    `dob` date NOT NULL,
                    PRIMARY KEY (`id`)
                )
            ");

        }

        if(isset($csvSelect)){
    
            $file = $csvSelect['csv_file'];
            $filename = $file["tmp_name"];

            if($file["size"] > 0){

                $file = fopen($filename, "r");

                $row = 1;
                $records = 0;

                $query = '';

                $insertArr = [];

                while (($getData = fgetcsv($file, 0, ",")) !== FALSE){

                    if($row != 1) {

                        $firstName = $mysqli->real_escape_string($getData[1]);
                        $surname = $mysqli->real_escape_string($getData[2]);
                        $initials = $mysqli->real_escape_string($getData[3]);
                        $age = $mysqli->real_escape_string($getData[4]);

                        $dateSeperator = str_replace('/', '-', $getData[5]);
                        $dobDate = new DateTime($dateSeperator);
                        $dob = $mysqli->real_escape_string($dobDate->format('Y/m/d'));
                        
                        $insertArr[] = "('".$firstName."','".$surname."','".$initials."','".$age."','".$dob."')";

                        $records++;
                    }

                    $row++;
                    
                }

                foreach (array_chunk($insertArr, 10000) as $insertChunk) {
                    $mysqli->query("INSERT INTO csv_import (name, surname, initials, age, dob) VALUES ".implode(',', $insertChunk)) or ( throw new Exception("Error Processing Request: ".$mysqli->error));
                }

                // $mysqli->query("
                //     INSERT INTO csv_import (first_name, surname, initials, age, dob) VALUES ".implode(',', $insertArr)
                // ) or ( throw new Exception("Error Processing Request: ".$mysqli->error));

                fclose($file);

                $_SESSION['csv_message'] = array(
                    'text' => $records.' Records has been imported',
                    'type' => 'success'
                );

            }
            else{
                throw new Exception('Selected file seems to be empty');
            }

        }
        else{
            throw new Exception('No file has been selected');
        }

        $mysqli->commit();

    }
    catch (\Throwable $e) {

        $mysqli->rollback();

        $_SESSION['csv_message'] = array(
            'text' => $e->getMessage(),
            'type' => 'error'
        );

    }

}

?>