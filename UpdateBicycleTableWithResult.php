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
$qrCode = $jsonInput["qrCode"];
$isRent = $jsonInput["isRent"];


if(!empty($userName) && !empty($qrCode) && !empty($isRent)){

	//sprawdzamy czy kod qr istnieje w naszej bazie
	$sql_select_query = "select qrCode from bicycleinfo where qrCode = '$qrCode';";

	$result_select_query = mysqli_query($con, $sql_select_query);
	//czy znaleziony jakiś rekord w tabeli qrCode który zawiera nasz kod qr?
	if(mysqli_num_rows($result_select_query) > 0){

		//jeśli tak to zauktualizuj rekord o podane dane
		 $sql_update_query = "update bicycleinfo set isRent = '$isRent', userNameFK = '$userName' where qrCode = '$qrCode';";

	 	//wykonaj zapytanie
    	$result_update_query = mysqli_query($con, $sql_update_query);
    	//echo $result_update_query;
		//$result_update_query
    if($result_update_query){
		$new_sql_select_query = "select lockerCode from bicycleinfo where qrCode = '$qrCode';";


    			//wykonujemy zapytanie
				$sql_all_data = mysqli_query($con, $new_sql_select_query);
			
				//ładujemy interesujące nas kolumny dla konkretnego użytkownika
				//wskazanego w zapytaniu "new_sql_select_query" do tablicy asocjacyjnej
				while ($row = mysqli_fetch_array($sql_all_data)) {
				$responseJson["lockerCode"] = $row["lockerCode"];
				}
				
				//zwróć odpowiedni komunikat w json
				$responseJson["success"] = 1;
				$responseJson["message"] = "Kod pobrany poprawnie";
				echo json_encode($responseJson);

    }else{

				$responseJson["success"] = 0;
				$responseJson["message"] = "Ooops! nie udało się wypożyczyć!";
				echo json_encode($responseJson);



    }



	}else{

		$responseJson["lockerCode"] = "Błędny kod QR!";
		$responseJson["success"] = 0;
		$responseJson["message"] = "Ooops! masz bledny kod roweru!";
		echo json_encode($responseJson);
	}


	

	}else{
				$responseJson["success"] = 0;
				$responseJson["message"] = "Brak wymaganego pola";
				echo json_encode($responseJson);


	}

?>