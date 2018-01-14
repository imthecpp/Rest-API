<?php


//zainicjowanie połączenia z bazą
require "initRentBicycleDB.php";
//nasłuchuje danych jsona
$jsonInput = json_decode(file_get_contents('php://input'), true);
header('Content-Type: application/json');
//utworzenie tablicy asocjacyjnej na odpowiedź jsona dla aplikacji
$responseJson["user"] = array();


$userName = $jsonInput["userName"];


if(!empty($userName)){
    
    //sprawdzamy czy użytkownik o podanej nazwie lub emailu istnieje w systemie
	$sql_select_query = "select * from userinfo where userName = '$userName';";
	//wykonujemy zapytanie
	$result_select_query = mysqli_query($con, $sql_select_query);
    
    if($result_select_query){
        
        while ($row = mysqli_fetch_array($result_select_query)) {
				$userDataJson["userName"] = $row["userName"];
				$userDataJson["accountBalance"] = $row["accountBalance"];
        }

        $responseJson["message"] = "imie i stan konta pobrane poprawnie";
        $responseJson["success"] = 1;
        array_push($responseJson["user"], $userDataJson);
        echo json_encode($responseJson);
    }else{
        $responseJson["success"] = 0;
        $responseJson["message"] = "Ooops! nie udało się pobrać imienia i stanu konta";
        echo json_encode($responseJson);
    }
}else{
    $responseJson["success"] = 0;
    $responseJson["message"] = "Brak wymaganego pola";
    echo json_encode($responseJson);
    
}

?>