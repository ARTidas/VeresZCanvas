<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class SensorDisplay3View extends ProjectAbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
			?>
                <script src="<?php print(RequestHelper::$common_url_root); ?>/js/MQTT/mqtt.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/P5/p5.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/genesys.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/canvas.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/sketch_3.js"></script>
                <script type="text/javascript" src="<?php print(RequestHelper::$url_root); ?>/js/test/test_path_sphere.js"></script>
                <script>
                    // Debugging sequence for the half sphere bug
                    let path_test = [];
                    /*path_test.push({"orientation": {"yaw": "-171.88","pitch": "-177.56","roll": "-179.88"},"position": {"x": "0.00","y": "0.00","z": "0.00"},"distance": 600.00,"timestamp": "25472"});
                    path_test.push({"orientation": {"yaw": "171.88","pitch": "177.56","roll": "179.88"},"position": {"x": "0.00","y": "0.00","z": "0.00"},"distance": 600.00,"timestamp": "25472"});
                    path_test.push({"orientation": {"yaw": 171.88,"pitch": 177.56,"roll": 179.88},"position": {"x": "0.00","y": "0.00","z": "0.00"},"distance": 600.00,"timestamp": "25472"});
                    path_test.push({"orientation": {"yaw": -171.88,"pitch": -177.56,"roll": -179.88},"position": {"x": "0.00","y": "0.00","z": "0.00"},"distance": 600.00,"timestamp": "25472"});*/

                    //This draw a full sphere on the canvas.
                    /*for (let pitch = -180; pitch <= 180; pitch += 10) {
                        for (let roll = -180; roll <= 180; roll += 10) {
                            for (let yaw = -180; yaw <= 180; yaw += 10) {
                                path_test.push(
                                    {"orientation": {"yaw": yaw,"pitch": pitch,"roll": roll},"position": {"x": "0.00","y": "0.00","z": "0.00"},"distance": 600.00,"timestamp": "25472"}
                                );
                            }
                        }
                    }*/

                    /*for (let i = 0; i < test_path.length; i++) {
                        let msg = JSON.parse(path_test[i].toString());
                        //let msg = path_test[i];

                        updateSensorData(
                            msg.orientation.pitch,
                            msg.orientation.roll,
                            msg.orientation.yaw,
                            msg.position.x,
                            msg.position.y,
                            msg.position.z,
                            msg.distance
                        );
                    }*/


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
                        const messagesDiv = document.getElementById('messages');
                        messagesDiv.innerHTML = `<p>Topic: ${topic}, Message: ${JSON.stringify(msg)}</p>`;
                        //console.log(msg);

                        updateSensorData(
                            msg.orientation.pitch,
                            msg.orientation.roll,
                            msg.orientation.yaw,
                            msg.position.x,
                            msg.position.y,
                            msg.position.z,
                            msg.distance
                        );
                    });

                    // Handle errors
                    client.on('error', function (err) {
                        console.error('Connection error: ', err);
                    });
                </script>

				<main id="main"></main>
                <button id="save-btn">Save Image</button>
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