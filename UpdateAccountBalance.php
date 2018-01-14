<?php

//zainicjowanie połączenia z bazą
require "initRentBicycleDB.php";
//nasłuchuje danych jsona
$jsonInput = json_decode(file_get_contents('php://input'), true);
header('Content-Type: application/json');
//utworzenie tablicy asocjacyjnej na odpowiedź jsona dla aplikacji
$responseJson= array();
//dane przesłane przez aplikacje do bieżącego pliku php w formacie json

$userName = $jsonInput["userName"];
$accountBalance = $jsonInput["accountBalance"];

//sprawdzamy czy zmienne są wypełnione danymi
if(!empty($userName) && !empty($accountBalance)){
    
    $sql_update_query = "update userinfo set accountBalance = '$accountBalance' where userName = '$userName';";
    //wykonaj zapytanie
    $result_update_query = mysqli_query($con, $sql_update_query);
    if($result_update_query){
        
        $responseJson["success"] = 1;
        $responseJson["message"] = "konto doładowane!";
        //$responseJson["accountBalace"] = $accountBalance;
        echo json_encode($responseJson);
    }else{
        $responseJson["success"] = 0;
        $responseJson["message"] = "Ooops! nie udało się doładować konta";
        echo json_encode($responseJson);
    }

}else{
    $responseJson["success"] = 0;
    $responseJson["message"] = "Niewystarczająca ilość pól";
    echo json_encode($responseJson);
}
?>