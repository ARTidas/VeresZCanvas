<?php

    /* ********************************************************
	 * ********************************************************
	 * ********************************************************/
    class DigitBo extends AbstractBo {

        /* ********************************************************
		 * ********************************************************
		 * ********************************************************/
		public function create(AbstractDo $do) {
            $this->validateDoForCreate($do);

            if (!$this->isDoValidForCreate($do)) {
                return false;
            }

            $last_insert_id = $this->dao->create([
                ':user_id'            => $do->user_id,
                ':target_digit'       => $do->target_digit,
                ':predicted_digit'    => $do->predicted_digit,
                ':confidence'         => $do->confidence
            ]);

            if ($last_insert_id) {
                LogHelper::addConfirmation('Created record with id: #' . $last_insert_id);
            }
            else {
                LogHelper::addWarning('Failed to create record!');
            }

			return $last_insert_id;
		}

        /* ********************************************************
		 * ********************************************************
		 * ********************************************************/
		public function validateDoForCreate(AbstractDo $do) {
			foreach ($do->getAttributes() as $key => $value) {
                // Patch for the "empty(0) = true"
                if ($key !== 'target_digit') {
                    if (self::isAttributeRequiredForCreate($key)) {
                        if (empty($value)) {
                            LogHelper::addWarning('Please fill out the following attribute: ' . $key);
                        }
                    }
                }
            }
		}

        /* ********************************************************
		 * ********************************************************
		 * ********************************************************/
		public function isDoValidForCreate(AbstractDo $do) {
			foreach ($do->getAttributes() as $key => $value) {
                // Patch for the "empty(0) = true"
                if ($key !== 'target_digit') {
                    if (self::isAttributeRequiredForCreate($key)) {
                        if (empty($value)) {
                            
                            return false;
                        }
                    }
                }
            }

            return true;
		}

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public static function isAttributeRequiredForCreate($input) {
            if (
				$input === 'target_digit' ||
				$input === 'predicted_digit' ||
				$input === 'confidence'
			) {
				return true;
			}

			return false;
        }

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function getListForUser($user_id) {
            $do_list = [];
			
			$records = $this->dao->getListForUser([':user_id' => $user_id]);

			if (empty($records)) {
				LogHelper::addWarning('There are no records of: ' . 'StudentMapPin');
			}
			else {
				foreach ($records as $record) {
					$do_list[] = $this->do_factory->get($this->actor_name, $record);
				}
			}
			
			return $do_list;
        }


    }

?>