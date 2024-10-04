<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class MessageQueueSimulationView extends ProjectAbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
            ?>
                <script src="<?php print(RequestHelper::$common_url_root); ?>/js/MQTT/mqtt.js"></script>

                <div id="messages"></div>
                
                <script>
                    const broker = 'wss://pti.unithe.hu:19001';
                    const options = {
                        username: 'VeresZLidar',
                        password: 'bolcseszmernok1'
                    };

                    const client = mqtt.connect(broker, options);

                    client.on('connect', function () {
                        console.log('Connected for simulation');
                        
                        // Simulate sending data
                        setInterval(() => {
                            const simulatedMessage = JSON.stringify({
                                pitch: (Math.random() * 360 - 180).toFixed(2),
                                roll: (Math.random() * 360 - 180).toFixed(2),
                                yaw: (Math.random() * 360 - 180).toFixed(2),
                                timestamp: new Date().toISOString()
                            });

                            // Publish to the 'VeresZLidar' topic
                            client.publish('VeresZLidar', simulatedMessage);
                            console.log('Simulated message sent:', simulatedMessage);
                        }, 1000);
                    });

                    client.on('error', function (err) {
                        console.error('Error in simulation: ', err);
                    });
                </script>

            <?php
		}

    }

?>