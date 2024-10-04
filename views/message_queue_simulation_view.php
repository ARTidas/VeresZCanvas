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
                                const simulatedMessage = JSON.stringify({
                                    pitch: (Math.random() * 360 - 180).toFixed(2),
                                    roll: (Math.random() * 360 - 180).toFixed(2),
                                    yaw: (Math.random() * 360 - 180).toFixed(2),
                                    timestamp: new Date().toISOString()
                                });

                                client.publish('VeresZLidar', simulatedMessage);
                                console.log('Simulated message sent:', simulatedMessage);
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