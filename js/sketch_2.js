let canvas;

let pitch = 0;
let roll = 0;
let yaw = 0;

let targetPitch = 0;
let targetRoll = 0;
let targetYaw = 0;

let camRadius = 1000; // Starting distance of the camera from the object
let camAngleX = 0;   // Horizontal angle around the object
let camAngleY = 0;   // Vertical angle around the object
let isDragging = false; // Track if the user is dragging the mouse
let previousMouseX = 0;
let previousMouseY = 0;

function setup() {
    canvas = createCanvas(windowWidth - 26, windowHeight - 250, WEBGL); // Create a full-window 3D canvas
    canvas.mousePressed(startDrag);  // Start drag on mouse press
    canvas.mouseReleased(stopDrag);  // Stop drag on mouse release
}

function draw() {
    background(200);

    // Calculate the camera position based on spherical coordinates
    let camX = camRadius * cos(camAngleY) * sin(camAngleX);
    let camY = camRadius * sin(camAngleY);
    let camZ = camRadius * cos(camAngleY) * cos(camAngleX);

    // Set the camera to orbit around the object
    camera(camX, camY, camZ, 0, 0, 0, 0, 1, 0);

    // Interpolate between the current and target orientation values
    //let smoothingFactor = 0.05;  // Adjust for smoother/slower transitions
    let smoothingFactor = 0.5;  // Adjust for smoother/slower transitions
    pitch = lerp(pitch, targetPitch, smoothingFactor);
    roll = lerp(roll, targetRoll, smoothingFactor);
    yaw = lerp(yaw, targetYaw, smoothingFactor);

    // Apply rotation to the object
    rotateX(pitch);
    rotateY(yaw);
    rotateZ(roll);

    rotateX(PI);  // Adjust orientation as needed

    // Draw a simple cone to represent the sensor orientation
    stroke(255, 255, 255);  // Set the line color
    fill(150, 0, 0);
    cone(50, 100, 8);

    // Draw a line extending from the cone to represent its direction
    stroke(255, 255, 255);  // Set the line color
    strokeWeight(5);        // Set line thickness
    line(0, 50, 0, 0, 200, 0);  // Line extending from the cone

    // Draw X, Y, Z axes
    drawAxes();

    // Handle dragging for camera rotation
    if (isDragging) {
        let deltaX = mouseX - previousMouseX;
        let deltaY = mouseY - previousMouseY;

        camAngleX += deltaX * 0.01; // Horizontal rotation control
        camAngleY -= deltaY * 0.01; // Vertical rotation control

        // Limit the vertical angle to avoid flipping the camera upside down
        camAngleY = constrain(camAngleY, -PI / 2, PI / 2);

        previousMouseX = mouseX;
        previousMouseY = mouseY;
    }
}

// Function to draw X, Y, Z axes
function drawAxes() {
    strokeWeight(2); // Set line thickness
    axisLength = 500;

    // X-axis in red
    stroke(255, 0, 0);
    line(-axisLength, 0, 0, axisLength, 0, 0);  // X-axis line (positive direction)

    // Y-axis in green
    stroke(0, 255, 0);
    line(0, -axisLength, 0, 0, axisLength, 0);  // Y-axis line (positive direction)

    // Z-axis in blue
    stroke(0, 0, 255);
    line(0, 0, -axisLength, 0, 0, axisLength);  // Z-axis line (positive direction)
}


// Handle the start of dragging
function startDrag() {
    isDragging = true;
    previousMouseX = mouseX;
    previousMouseY = mouseY;
}

// Handle the end of dragging
function stopDrag() {
    isDragging = false;
}

function mouseWheel(event) {
    // p5.js automatically passes the event object, and event.delta provides the wheel scroll value
    // Adjust the zoom factor as needed
    camRadius -= event.delta * 0.5; // Decrease the zoom sensitivity for smoother zooming
}



// Update pitch, roll, and yaw from the MQTT data
function updateSensorData(newPitch, newRoll, newYaw) {
    // Set target orientation to the new values
    targetPitch = newPitch;
    targetRoll = newRoll;
    targetYaw = newYaw;
}

function windowResized() {
    resizeCanvas(windowWidth - 26, windowHeight - 250);
}
