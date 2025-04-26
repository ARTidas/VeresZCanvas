<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class DigitViewView extends ProjectAbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
			?>
                
				<h2>List</h2>

				<table>
					<thead>
						<tr>
							<?php
								for ($i = 0; $i <= 9; $i++) {
									print('<th>');
									print($i);
									print('</th>');
								}
							?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php
								for ($i = 0; $i <= 9; $i++) {
									print('<td>');
									foreach ($this->do->do_list as $do) {
										if ($i === $do->target_digit) {
											//print($do->id . ',');
											print('<img src="' . RequestHelper::$url_root . '/cdn/digits/digit_' . $do->id . '.png' . '" />');
										}
									}
									print('</td>');
								}
							?>
						</tr>
					</tbody>
				</table>
                
			<?php
		}

    }

?>