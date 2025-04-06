<?php
//Function that queries the chatGPT API for a given prompt
function query_chatGPT($prompt) {
	// Set your OpenAI API key
	$api_key = 'set your key here';

	// Set the endpoint URL
	$endpoint = 'https://api.openai.com/v1/chat/completions';


	// Set the parameters
	$params = new \stdClass();
	$params->model = "gpt-4o";
	$params->messages = array();
	$params->messages[0]= new \stdClass();
	$params->messages[0]->role = "user";
	$params->messages[0]->content = $prompt;
	$params = json_encode($params);

	// Set headers
	$headers = [
		'Content-Type: application/json',
		'Authorization: Bearer ' . $api_key,
	];

	// Initialize cURL session (in order to communicate with an http service)
	$ch = curl_init();

	// Set cURL options
	curl_setopt($ch, CURLOPT_URL, $endpoint);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Execute cURL request
	$response = curl_exec($ch);


	// Check for errors
	if(curl_errno($ch)) {
		//Print the error
		$theError = curl_error($ch);
		echo 'Error: ' . $theError;
		
		// Close cURL session
		curl_close($ch);
		
		//print_r($response);
		
		//Return the error in communication
		return array("ErrorExists" => true, "Error" => $theError);
	} else {
		// Return the response
		//echo $response;
		$response_Array = json_decode($response);
		
		//If there is a response but it containts an error in the response, return the error
		if (isset($response_Array->error)) {
			echo "error\r\n".$prompt;
			echo $prompt;
			echo $response;
			exit();
		}


		
		//If there is NO error in the response, return the response
		return array("ErrorExists" => false, "Reply" => $response_Array->choices[0]->message->content);
	}


}
//Tests
//$prompt = 'Please give me an answer on the following question: What is the difference between a compiler and an interpreter';
//echo query_chatGPT($prompt);

//Load the dataset from the file
$dataset = file_get_contents("Questions-Answers-Hints.txt");
//Convert the loaded content of the file from json encoding to PHP array / object
$dataset = json_decode($dataset);

/*
//Tests
var_dump(isset($dataset[0]->gptnohint));
$dataset[0]->gptnohint = 'aaa';
var_dump(isset($dataset[0]->gptnohint));
print_r($dataset[0]); exit();
*/

//Function to call. For each of the dataset's entries return the reply without use of hint
function noHint4o($dataset) {
	//for each question in the dataset
	foreach ($dataset as $questionNo => $aQuestion) {	
		//just print / echo details so we know the execution of the code
		echo "Doing question ".($questionNo + 1)." of ".count($dataset)." ...";
		
		//We'll use the existing dataset to store the reply from chatGPT. If it's already been replied, go to next -> do not use chatGPT credits
		if (isset($aQuestion->gpt4onohint) AND is_null($aQuestion->gpt4onohint) === false) {
			echo " already done\r\n";
			continue;
		}

		//Formulate the prompt, notice the use of variables, e.g. $aQuestion->C based on the scenario of the function (see difference in next equivalent functions)
		$prompt = 'Please reply to the question \''.($aQuestion->C).'\'';
		//echo $prompt."\r\n";
		
		//Ask chatGPT, using the function query_chatGPT for abovementioned prompt
		$reply = query_chatGPT($prompt);
		
		//If there is no error in the reply from chatGPT
		if ($reply["ErrorExists"] === false){
			//Store the reply in the existing dataset
			$dataset[$questionNo]->gpt4onohint = $reply["Reply"];
			echo " done\r\n";
		}
		else //Else terminate the script			
			exit($reply["Error"]);
		
		//Save the just updated dataset variable to the existing dataset file 
		file_put_contents("Questions-Answers-Hints.txt", json_encode($dataset));
		
		//Stop execution for 20 seconds, cause chatGPT's API allows for 3 queries per minute
		sleep(20);
	}
}


function withHint4o($dataset) {
	foreach ($dataset as $questionNo => $aQuestion) {	
		echo "Doing question ".($questionNo + 1)." of ".count($dataset)." ...";
		
		if (isset($aQuestion->gpt4owithhint) AND is_null($aQuestion->gpt4owithhint) === false) {
			echo " already done\r\n";
			continue;
		}
		
		$prompt = 'Please reply to the question \''.($aQuestion->C).'\' given the hint \''.($aQuestion->E).'\'';
		//echo $prompt."\r\n";
		$reply = query_chatGPT($prompt);
		
		if ($reply["ErrorExists"] === false){
			$dataset[$questionNo]->gpt4owithhint = $reply["Reply"];
			echo " done\r\n";
		}
		else
			exit($reply["Error"]);
		
		file_put_contents("Questions-Answers-Hints.txt", json_encode($dataset));
		sleep(20);
	}
}

function evalReplyNoHint4o($dataset) {
	foreach ($dataset as $questionNo => $aQuestion) {	
		echo "Doing question ".($questionNo + 1)." of ".count($dataset)." ...";
		
		if (isset($aQuestion->gptevalReplyNoHint4o) AND is_null($aQuestion->gptevalReplyNoHint4o) === false) {
			echo " already done\r\n";
			continue;
		}
		
		$prompt = 'For the question \''.($aQuestion->C).'\', how would you judge your reply \''.($aQuestion->gptnohint).'\' in comparison to the following reply that is taken from an academic textbook \''.($aQuestion->D).'\'? Please provide both a textual comparison of the 2 answers as well as a numeric ranging from 0% (wherein the 2 replies are contradictory) and 100% (wherein the 2 replies are essentially the same)';
		
		//echo $prompt."\r\n";
		$reply = query_chatGPT($prompt);
		
		if ($reply["ErrorExists"] === false){
			$dataset[$questionNo]->gptevalReplyNoHint4o = $reply["Reply"];
			echo " done\r\n";
		}
		else
			exit($reply["Error"]);
		
		file_put_contents("Questions-Answers-Hints.txt", json_encode($dataset));
		sleep(20);
	}
}

function evalReplyWithHint4o($dataset) {
	foreach ($dataset as $questionNo => $aQuestion) {	
		echo "Doing question ".($questionNo + 1)." of ".count($dataset)." ...";
		
		if (isset($aQuestion->gptevalReplyWithHint4o) AND is_null($aQuestion->gptevalReplyWithHint4o) === false) {
			echo " already done\r\n";
			continue;
		}
		
		$prompt = 'For the question \''.($aQuestion->C).'\', how would you judge your reply \''.($aQuestion->gptwithhint).'\' in comparison to the following reply that is taken from an academic textbook \''.($aQuestion->D).'\'? Please provide both a textual comparison of the 2 answers as well as a numeric ranging from 0% (wherein the 2 replies are contradictory) and 100% (wherein the 2 replies are essentially the same)';
		
		//echo $prompt."\r\n";
		$reply = query_chatGPT($prompt);
		
		if ($reply["ErrorExists"] === false){
			$dataset[$questionNo]->gptevalReplyWithHint4o = $reply["Reply"];
			echo " done\r\n";
		}
		else
			exit($reply["Error"]);
		
		file_put_contents("Questions-Answers-Hints.txt", json_encode($dataset));
		sleep(20);
	}
}

//Call the functions (one per execution) so that the code runs
//withHint($dataset);
//evalReplyNoHint4o($dataset);
//evalReplyWithHint4o($dataset);

?>