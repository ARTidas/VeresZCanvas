<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class MessageQueueMqttView extends ProjectAbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
            ?>
                <script src="<?php print(RequestHelper::$common_url_root); ?>/js/MQTT/mqtt.js"></script>

                <div id="messages"></div>
                
                <script>
                    // Replace this with the WebSocket endpoint of your MQTT broker
                    const broker = 'wss://pti.unithe.hu:19001';
                    //const broker = 'ws://pti.unithe.hu:11883';
                    //const broker = 'wss://pti.unithe.hu:11883';
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
                        const msg = message.toString();
                        console.log('Message received:', msg);

                        // Display the message in the webpage
                        const messagesDiv = document.getElementById('messages');
                        messagesDiv.innerHTML += `<p>Topic: ${topic}, Message: ${msg}</p>`;
                    });

                    // Handle errors
                    client.on('error', function (err) {
                        console.error('Connection error: ', err);
                    });
                </script>

            <?php
		}

    }

?>