let pitch = 0;
let roll = 0;
let yaw = 0;

function setup() {
    createCanvas(400, 400, WEBGL); // Create a 3D canvas
}

function draw() {
    background(200);

    // Apply rotation based on pitch, roll, and yaw
    rotateX(pitch);
    rotateY(yaw);
    rotateZ(roll);

    //rotateX(HALF_PI);
    rotateX(PI);
    //rotateY(PI);
    // Draw a simple box to represent the sensor orientation
    fill(150, 0, 0);
    cone(50, 100, 8);

    // Draw a line extending from the cone to represent its direction
    stroke(255, 255, 255); // Set the line color
    strokeWeight(5); // Set line thickness
    line(0, 50, 0, 0, 200, 0); // Line extending upwards from the cone
}

// Update pitch, roll, and yaw from the MQTT data
function updateSensorData(newPitch, newRoll, newYaw) {
    pitch = newPitch;
    roll = newRoll;
    yaw = newYaw;
}
