<?php

	/* ********************************************************
	 * ********************************************************
	 * ********************************************************/
	class MessageQueueSimulation3View extends ProjectAbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
            ?>
                <script src="<?php print(RequestHelper::$common_url_root); ?>/js/MQTT/mqtt.js"></script>

                
                <button id="startBtn">Start Simulation</button>
                <button id="stopBtn" disabled>Stop Simulation</button>
                <div id="messages"></div>
                
                <script>
                    const broker = 'wss://pti.unithe.hu:19001';
                    const options = {
                        username: 'VeresZLidar',
                        password: 'bolcseszmernok1'
                    };

                    const client = mqtt.connect(broker, options);
                    let simulationInterval = null;

                    // Variables to hold current orientation
                    let orientationPitch = 0;
                    let orientationRoll = 0;
                    let orientationYaw = 0;
                    let positionX = 0;
                    let positionY = 0;
                    let positionZ = 0;

                    client.on('connect', function () {
                        console.log('Connected for simulation');
                    });

                    client.on('error', function (err) {
                        console.error('Error in simulation: ', err);
                    });

                    // Start Simulation Function
                    function startSimulation() {
                        if (!simulationInterval) {
                            simulationInterval = setInterval(() => {

                                let max = 3;
                                let min = -3;
                                const pitchChange = Math.random() * (max - min) + min;
                                const rollChange = Math.random() * (max - min) + min;
                                const yawChange = Math.random() * (max - min) + min;

                                // Update current values
                                orientationPitch += pitchChange * (Math.PI / 180);
                                orientationRoll += rollChange * (Math.PI / 180);
                                orientationYaw += yawChange * (Math.PI / 180);
                                positionX += pitchChange;
                                positionY += rollChange;
                                positionZ += yawChange;

                                const simulatedMessage = JSON.stringify({
                                    orientation: {
                                        pitch: orientationPitch.toFixed(2),
                                        roll: orientationRoll.toFixed(2),
                                        yaw: orientationYaw.toFixed(2),
                                    },
                                    position: {
                                        x: positionX.toFixed(2),
                                        y: positionY.toFixed(2),
                                        z: positionZ.toFixed(2),
                                    },
                                    distance: 1500.00,
                                    timestamp: new Date().toISOString()
                                });

                                client.publish('VeresZLidar_V3', simulatedMessage);
                                //console.log('Simulated message sent:', simulatedMessage);
                            }, 1000);

                            document.getElementById('startBtn').disabled = true;
                            document.getElementById('stopBtn').disabled = false;
                        }
                    }

                    // Stop Simulation Function
                    function stopSimulation() {
                        if (simulationInterval) {
                            clearInterval(simulationInterval);
                            simulationInterval = null;

                            document.getElementById('startBtn').disabled = false;
                            document.getElementById('stopBtn').disabled = true;
                        }
                    }

                    // Add event listeners to buttons
                    document.getElementById('startBtn').addEventListener('click', startSimulation);
                    document.getElementById('stopBtn').addEventListener('click', stopSimulation);
                </script>

            <?php
		}

    }

?>