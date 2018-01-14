<?php


//zainicjowanie połączenia z bazą
require "initRentBicycleDB.php";
//nasłuchuje danych jsona
$jsonInput = json_decode(file_get_contents('php://input'), true);


//utworzenie tablicy asocjacyjnej na odpowiedź jsona dla aplikacji
$responseJson["user"] = array();
//dane przesłane przez aplikacje do bieżącego pliku php w formacie json
$userName = $jsonInput["userName"];
$userPassword = $jsonInput["userPassword"];

//sprawdzamy czy zmienne są wypełnione danymi
if(!empty($userName) && !empty($userPassword)){

	//sprawdzamy czy użytkownik o podanej nazwie i haśle istnieje w systemie
	$sql_select_query = "select * from userinfo where userName like '$userName' and userPassword like md5('$userPassword');";
	//wykonujemy zapytanie
	$result_select_query = mysqli_query($con, $sql_select_query);
	//sprawdź czy zapytanie select wykonało się poprawnie
	if($result_select_query){
			//zwróć odpowiedni komunikat w json
		if(mysqli_num_rows($result_select_query) > 0){

				//deklarujemy nową tablicę
				$userDataJson = array();
				//ładujemy interesujące nas kolumny dla konkretnego użytkownika
				//wskazanego w zapytaniu "new_sql_select_query" do tablicy asocjacyjnej
				while ($row = mysqli_fetch_array($result_select_query)) {
				$userDataJson["userId"] = $row["userId"];
				$userDataJson["userName"] = $row["userName"];
				$userDataJson["userEmail"] = $row["userEmail"];
				$userDataJson["accountBalance"] = $row["accountBalance"];
			}
			
			//zwróć odpowiedni komunikat w json
			$responseJson["success"] = 1;
			$responseJson["message"] = "Użytkownik zalogowany poprawnie!";
			array_push($responseJson["user"], $userDataJson);
			echo json_encode($responseJson);

		}else{
			$responseJson["success"] = 0;
			$responseJson["message"] = "błędna nazwa użytkownika lub hasło";
			echo json_encode($responseJson);
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