<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class SensorDisplay2View extends ProjectAbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
			?>
                <script src="<?php print(RequestHelper::$common_url_root); ?>/js/MQTT/mqtt.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/P5/p5.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/genesys.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/canvas.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/sketch_2.js"></script>                
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

                        // Subscribe to the 'VeresZLidar' topic
                        client.subscribe('VeresZLidar', function (err) {
                            if (!err) {
                                console.log('Subscribed to VeresZLidar');
                            }
                        });
                    });

                    // When a message is received
                    client.on('message', function (topic, message) {
                        // Message is Buffer, so convert it to string
                        const msg = JSON.parse(message.toString());
                        //console.log('Message received:', JSON.stringify(msg));

                        // Display the message in the webpage
                        const messagesDiv = document.getElementById('messages');
                        messagesDiv.innerHTML = `<p>Topic: ${topic}, Message: ${JSON.stringify(msg)}</p>`;

                        updateSensorData(msg.pitch, msg.roll, msg.yaw, msg.distance);
                    });

                    // Handle errors
                    client.on('error', function (err) {
                        console.error('Connection error: ', err);
                    });
                </script>

				<main id="main">
                </main>
                <div id="messages" style="font-size:8px;"></div>

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