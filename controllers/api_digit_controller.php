<?php

	header('Content-Type: application/json; charset=utf-8');

	$bo = $bo_factory->get(ActorHelper::DIGIT);

	/* ********************************************************
	 * *** Lets control exectution by actor action... *********
	 * ********************************************************/
	switch (RequestHelper::$actor_action) {
		case '':
			LogHelper::addError('No actor action detected...');
			break;
		case 'create':
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$data = json_decode(file_get_contents('php://input'), true);

				if (!isset(
					$data['image_data'],
					$data['target_digit'],
					$data['predicted_digit'],
					$data['confidence']
				)) {
					echo json_encode(["status" => "error", "message" => "Invalid data received."]);
					exit;
				}
				else {
					$response 				= [];
					$do 					= new DigitDo;
					$do->user_id      		= $_SESSION['user_id'];
					$do->target_digit   	= $data['target_digit'];
					$do->predicted_digit	= $data['predicted_digit'];
					$do->confidence 		= $data['confidence'];
					$last_insert_id 		= $bo->create($do);

					if ($last_insert_id) {
						// Save the image
						$image_path = RequestHelper::$file_root . '/cdn/digits/digit_' . $last_insert_id . '.png';
						if (preg_match('/^data:image\/png;base64,/', $data['image_data'])) {
							$image_base64 = preg_replace('/^data:image\/png;base64,/', '', $data['image_data']);
							$image_base64 = base64_decode($image_base64);
							$image_path = RequestHelper::$file_root . '/cdn/digits/digit_' . $last_insert_id . '.png';
							
							if ($image_base64 !== false) {
								file_put_contents($image_path, $image_base64);
							} else {
								echo json_encode(["status" => "error", "message" => "Invalid base64 encoding."]);
								exit;
							}
						}
						else {
							LogHelper::addError('Cannot process the image!');
						}
					}
					else {
						LogHelper::addError('Cannot create database record!');
					}
					

					$response['errors'] 		= LogHelper::getErrors();
					$response['confirmations'] 	= LogHelper::getConfirmations();
					$response['warnings'] 		= LogHelper::getWarnings();
					$response['messages'] 		= LogHelper::getMessages();

					echo json_encode($response);
				}
			}
			else {
				echo json_encode(["status" => "error", "message" => "No data POSTed."]);
			}
			
			break;
		default:
			LogHelper::addError('Unhandled action...');
			break;
	}

?>
