<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class MessageQueueViewView extends ProjectAbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
            ?>
                <!-- <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>timestamp</th>
                            <th>pitch</th>
                            <th>roll</th>
                            <th>yaw</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($this->do->do_list as $do) {
                                ?>
                                    <tr>
                                        <td><?php print($do->id); ?></td>
                                        <td><?php print($do->timestamp); ?></td>
                                        <td><?php print($do->pitch); ?></td>
                                        <td><?php print($do->roll); ?></td>
                                        <td><?php print($do->yaw); ?></td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table> -->

                <div id="jsonData"></div>

                <script>
                    $(document).ready(function() {
                        // URL to fetch the JSON data
                        var apiUrl = '<?php print(RequestHelper::$url_root); ?>/message_queue/get';

                        // Make an AJAX request to fetch the JSON
                        $.ajax({
                            url: apiUrl,
                            method: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                // Parse and display the JSON data
                                var html = '<ul>';
                                response.forEach(function(item) {
                                    html += '<li>ID: ' + item.id + ', Pitch: ' + item.pitch + ', Roll: ' + item.roll + ', Yaw: ' + item.yaw + ', Timestamp: ' + item.timestamp + '</li>';
                                });
                                html += '</ul>';
                                $('#jsonData').html(html); // Display the data in the div
                            },
                            error: function(xhr, status, error) {
                                // Handle any errors
                                console.error('Error fetching data: ' + error);
                            }
                        });
                    });
                </script>
            <?php
		}

    }

?>