<?php

    class MessageQueueSimulation3View extends ProjectAbstractView {

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
                    let pitch = -180;
                    let roll = -180;
                    let yaw = -180;

                    client.on('connect', function () {
                        console.log('Connected to the MQTT broker for simulation');
                    });

                    client.on('error', function (err) {
                        console.error('Error in simulation: ', err);
                    });

                    // Start Simulation Function
                    function startSimulation() {
                        if (!simulationInterval) {
                            simulationInterval = setInterval(simulateTelemetryData, 100); // Send data every 100ms

                            document.getElementById('startBtn').disabled = true;
                            document.getElementById('stopBtn').disabled = false;
                        }
                    }

                    // Simulate telemetry data
                    function simulateTelemetryData() {
                        // Create the telemetry message
                        const simulatedMessage = JSON.stringify({
                            orientation: {
                                pitch: pitch.toFixed(2),
                                roll: roll.toFixed(2),
                                yaw: yaw.toFixed(2),
                            },
                            position: {
                                x: "0.00",
                                y: "0.00",
                                z: "0.00",
                            },
                            distance: 600.00,
                            timestamp: new Date().toISOString(),
                        });

                        // Publish the telemetry message to the MQTT server
                        client.publish('VeresZLidar_3', simulatedMessage);
                        console.log('Simulated message sent:', simulatedMessage);
                        
                        // Increment the yaw first, then roll, then pitch
                        yaw += 10;
                        if (yaw > 180) {
                            yaw = -180;
                            roll += 10;
                        }

                        if (roll > 180) {
                            roll = -180;
                            pitch += 10;
                        }

                        if (pitch > 180) {
                            stopSimulation(); // Stop when pitch reaches 180
                        }
                    }

                    // Stop Simulation Function
                    function stopSimulation() {
                        if (simulationInterval) {
                            clearInterval(simulationInterval);
                            simulationInterval = null;

                            document.getElementById('startBtn').disabled = false;
                            document.getElementById('stopBtn').disabled = true;

                            // Reset pitch, roll, yaw values for next start
                            pitch = -180;
                            roll = -180;
                            yaw = -180;
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
