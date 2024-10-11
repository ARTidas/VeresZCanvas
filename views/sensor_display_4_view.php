<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class SensorDisplay4View extends ProjectAbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
			?>
                <main id="main"></main>
                <button id="save-btn">Save Image</button>
                <div id="messages" style="font-size:8px;"></div>

                <script src="<?php print(RequestHelper::$common_url_root); ?>/js/MQTT/mqtt.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/P5/p5.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/genesys.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/canvas.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/sketch_3.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/test/test_path_sphere.js"></script>
                <!-- TEST -->
                <!-- <script>
                    $(document).ready(function() {
                        // Define the pitch, roll, and yaw values
                        const pitchValues = [];
                        const rollValues = [];
                        const yawValues = [];

                        for (let pitch = -180.00; pitch <= 180.00; pitch += 10.00) {
                            pitchValues.push(pitch);
                        }
                        for (let roll = -180.00; roll <= 180.00; roll += 10.00) {
                            rollValues.push(roll);
                        }
                        for (let yaw = -180.00; yaw <= 180.00; yaw += 10.00) {
                            yawValues.push(yaw);
                        }

                        let index = 0; // Current index for the values
                        const totalCombinations = pitchValues.length * rollValues.length * yawValues.length;

                        // Function to update sensor data with the current combination
                        function callUpdateSensorData() {
                            if (index < totalCombinations) {
                                // Calculate current pitch, roll, and yaw based on the index
                                const pitch = pitchValues[Math.floor(index / (rollValues.length * yawValues.length))];
                                const roll = rollValues[Math.floor((index % (rollValues.length * yawValues.length)) / yawValues.length)];
                                const yaw = yawValues[index % yawValues.length];

                                updateSensorData(pitch, roll, yaw, 0.00, 0.00, 0.00, 600.00);
                                index++;
                            } else {
                                clearInterval(intervalId); // Stop the interval after all combinations
                            }
                        }

                        // Call the function 10 times every second
                        const intervalId = setInterval(callUpdateSensorData, 100); // 100 milliseconds = 10 times per second
                    });
                </script> -->

                <script>
                    const broker = 'wss://pti.unithe.hu:19001';
                    const options = {
                        username: 'VeresZLidar',
                        password: 'bolcseszmernok1'
                    };

                    // Create a client connection
                    const client = mqtt.connect(broker, options);

                    // When the client connects
                    client.on('connect', function () {
                        console.log('Connected to MQTT broker via WebSocket');

                        // Subscribe to the 'VeresZLidar_3' topic
                        client.subscribe('VeresZLidar_3', function (err) {
                            if (!err) {
                                console.log('Subscribed to VeresZLidar_3');
                            }
                        });
                    });

                    // When a message is received
                    client.on('message', function (topic, message) {
                        // Message is Buffer, so convert it to string
                        const msg = JSON.parse(message.toString());
                        //console.log('Message received:', JSON.stringify(msg));

                        // Display the message in the webpage
                        /*const messagesDiv = document.getElementById('messages');
                        messagesDiv.innerHTML = `<p>Topic: ${topic}, Message: ${JSON.stringify(msg)}</p>`;*/
                        //console.log(msg);

                        updateSensorData(
                            parseFloat(msg.orientation.pitch),
                            parseFloat(msg.orientation.roll),
                            parseFloat(msg.orientation.yaw),
                            parseFloat(msg.position.x),
                            parseFloat(msg.position.y),
                            parseFloat(msg.position.z),
                            parseFloat(msg.distance)
                        );
                    });

                    // Handle errors
                    client.on('error', function (err) {
                        console.error('Connection error: ', err);
                    });
                </script>

                <br clear="all" />
			<?php
		}


        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayFooter() {
        }

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayLogs() {
		}

    }

?>