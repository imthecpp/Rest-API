<?php


//zainicjowanie połączenia z bazą
require "initRentBicycleDB.php";
//nasłuchuje danych jsona
$jsonInput = json_decode(file_get_contents('php://input'), true);
header('Content-Type: application/json');
//utworzenie tablicy asocjacyjnej na odpowiedź jsona dla aplikacji
$responseJson["user"] = array();
//dane przesłane przez aplikacje do bieżącego pliku php w formacie json
$userName = $jsonInput["userName"];
$userEmail = $jsonInput["userEmail"];
$userPassword = $jsonInput["userPassword"];
$accountBalance = $jsonInput["accountBalance"];

//sprawdzamy czy zmienne są wypełnione danymi
if(!empty($userName)  && !empty($userEmail) && !empty($userPassword) && !empty($accountBalance)){


	//sprawdzamy czy użytkownik o podanej nazwie lub emailu istnieje w systemie
	$sql_select_query = "select * from userinfo where userName like '$userName' or userEmail like '$userEmail';";
	//wykonujemy zapytanie
	$result_select_query = mysqli_query($con, $sql_select_query);
	//sprawdź czy zapytanie select wykonało się poprawnie
	if($result_select_query){
		//jeśli tak to sprawdź czy zwróciło jakiś wynik (rekord)
		if(mysqli_num_rows($result_select_query) > 0){
			//zwróć odpowiedni komunikat w json
			$responseJson["success"] = 0;
			$responseJson["message"] = "użytkownik o podanej nazwie lub emailu już istnieje!";
			echo json_encode($responseJson);
			//w przeciwnym wypadku, dodajemy użytkownika o podanych danych
		}else{
			$sql_insert_new_user = "INSERT INTO userinfo (userName, userPassword, userEmail, accountBalance) VALUES ('$userName', md5('$userPassword'), '$userEmail', '$accountBalance')";
			//sprawdzamy czy zapytanie insert wykonało się poprawnie
			if(mysqli_query($con, $sql_insert_new_user))
			{
				//pobieramy wszystkie dane z bazy (tu powinien być jeszcze jeden if)
				$new_sql_select_query = "select * from userinfo where userName like '$userName' and userEmail like '$userEmail';";
				//wykonujemy zapytanie
				$sql_all_data = mysqli_query($con, $new_sql_select_query);
				//deklarujemy nową tablicę
				$userDataJson = array();
				//ładujemy interesujące nas kolumny dla konkretnego użytkownika
				//wskazanego w zapytaniu "new_sql_select_query" do tablicy asocjacyjnej
				while ($row = mysqli_fetch_array($sql_all_data)) {
				$userDataJson["userId"] = $row["userId"];
				$userDataJson["userName"] = $row["userName"];
				$userDataJson["userEmail"] = $row["userEmail"];
				$userDataJson["accountBalance"] = $row["accountBalance"];
			}
			
			//zwróć odpowiedni komunikat w json
			$responseJson["success"] = 1;
			$responseJson["message"] = "Użytkownik został dodany do bazy!";
			array_push($responseJson["user"], $userDataJson);
			echo json_encode($responseJson);
			//$myjson = json_encode($responseJson, JSON_UNESCAPED_UNICODE);
			//file_put_contents('myfile.json', $myjson);
			}else{
				//zwróć odpowiedni komunikat w json
				$responseJson["success"] = 0;
				$responseJson["message"] = "Ooops! nie udało się dodać użytkownika";
				echo json_encode($responseJson);
			}
			
		}
	}else{
		//zwróć odpowiedni komunikat w json
		$responseJson["success"] = 0;
		$responseJson["message"] = "Błąd zapytania";
		echo json_encode($responseJson);
	}


}else{
	//zwróć odpowiedni komunikat w json
	$responseJson["success"] = 0;
	$responseJson["message"] = "Brak wymaganego pola";
	echo json_encode($responseJson);
}



?>
