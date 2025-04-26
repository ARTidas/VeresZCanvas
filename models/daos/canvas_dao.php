<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class CanvasDao extends AbstractDao {

		/* ********************************************************
		 * ********************************************************
		 * ********************************************************/
		public function getList() {
			$query_string = "/* __CLASS__ __FUNCTION__ __FILE__ __LINE__ */
				SELECT
					SENSOR_DATA.id AS id,
					SENSOR_DATA.pitch AS pitch,
					SENSOR_DATA.roll AS roll,
					SENSOR_DATA.yaw AS yaw,
					SENSOR_DATA.timestamp AS timestamp
				FROM
					message_queue.sensor_data SENSOR_DATA
				ORDER BY
					SENSOR_DATA.timestamp ASC
				;
            ;";

			try {
				$handler = ($this->database_connection_bo)->getConnection();
				$statement = $handler->prepare($query_string);
				$statement->execute();
				
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			}
			catch(Exception $exception) {
				LogHelper::addError('Error: ' . $exception->getMessage());

				return false;
			}
		}

		
	}
?>
