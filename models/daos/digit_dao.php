<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class DigitDao extends AbstractDao {

		/* ********************************************************
		 * ********************************************************
		 * ********************************************************/
		public function create(array $record_data) {
			$query_string = "/* __CLASS__ __FUNCTION__ __FILE__ __LINE__ */
				INSERT INTO
					veresz_canvas.digits
				SET
                    user_id                 = :user_id,
					target_digit            = :target_digit,
					predicted_digit			= :predicted_digit,
					confidence				= :confidence,
                    submitted_at            = NOW(),
					is_active 				= 1,
					created_at				= NOW(),
					updated_at 				= NOW()
			";

			try {
				$database_connection = ($this->database_connection_bo)->getConnection();

				$database_connection
					->prepare($query_string)
					->execute($record_data)
				;

				return(
					$database_connection->lastInsertId()
				);
			}
			catch(Exception $exception) {
				LogHelper::addError('ERROR: ' . $exception->getMessage());

				return false;
			}
		}


		/* ********************************************************
		 * ********************************************************
		 * ********************************************************/
		public function getListForUser(array $record_data) {
			$query_string = "/* __CLASS__ __FUNCTION__ __FILE__ __LINE__ */
				SELECT
					DIGITS.id 				AS id,
					DIGITS.user_id 			AS user_id,
					DIGITS.target_digit 	AS target_digit,
					DIGITS.predicted_digit 	AS predicted_digit,
					DIGITS.confidence		AS confidence,
					DIGITS.submitted_at 	AS submitted_at,
					DIGITS.is_active 		AS is_active,
					DIGITS.created_at 		AS created_at,
					DIGITS.updated_at 		AS updated_at
				FROM
					veresz_canvas.digits DIGITS
				WHERE
					DIGITS.user_id = :user_id
				;";

			try {
				$handler = ($this->database_connection_bo)->getConnection();
				$statement = $handler->prepare($query_string);
				$statement->execute($record_data);
				
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			}
			catch(Exception $exception) {
				LogHelper::addError('Error: ' . $exception->getMessage());

				return false;
			}
		}

		
	}
    
?>
