let CanvasClass = new Canvas;
let canvas;

let pitch = 0;
let roll = 0;
let yaw = 0;

let targetPitch = 0;
let targetRoll = 0;
let targetYaw = 0;

let camRadius = 2500; // Starting distance of the camera from the object
let camAngleX = 0;   // Horizontal angle around the object
let camAngleY = 0;   // Vertical angle around the object
let isDragging = false; // Track if the user is dragging the mouse
let previousMouseX = 0;
let previousMouseY = 0;

let initialPitch = 0;  // Set an initial pitch angle (in radians)
let initialYaw = 0;    // Set an initial yaw angle (e.g., 90 degrees rotation)
let initialRoll = 0;   // Set an initial roll angle (in radians)

let axisLength = 2500; // Length of the axes
let currentDistance = 1500; // Default distance value

let font; // To store the font

let path = []; // Array to store the coordinates

// Preload the font before the sketch starts
function preload() {
    font = loadFont('https://pti.unithe.hu:8443/common/cdn/design/fonts/roboto_mono/RobotoMono-Regular.ttf');
}

function setup() {
    canvas = createCanvas(windowWidth - 26, windowHeight - 250, WEBGL); // Create a full-window 3D canvas
    canvas.mousePressed(startDrag);  // Start drag on mouse press
    canvas.mouseReleased(stopDrag);  // Stop drag on mouse release

    // Set the initial camera angles
    camAngleX = PI + (PI / 4); // Rotate 45 degrees horizontally
    camAngleY = (PI / 4) - (PI / 180 * 30); // Tilt the camera 45 degrees upwards

    textFont(font); // Set the loaded font

    // Get the button from the HTML and add an event listener
    let saveButton = document.getElementById('save-btn');
    saveButton.addEventListener('click', CanvasClass.saveImage);
}

function draw() {
    background(200);

    // Calculate the camera position based on spherical coordinates
    let camX = camRadius * cos(camAngleY) * sin(camAngleX);
    let camY = camRadius * sin(camAngleY);
    let camZ = camRadius * cos(camAngleY) * cos(camAngleX);

    // Set the camera to orbit around the object
    camera(camX, camY, camZ, 0, 0, 0, 0, -1, 0);

    // Draw X, Y, Z axes first (independent of object orientation)
    drawAxes();  // This ensures the axes remain stationary

    // Interpolate between the current and target orientation values
    let smoothingFactor = 0.5;  // Adjust for smoother/slower transitions
    pitch = lerp(pitch, targetPitch, smoothingFactor);
    roll = lerp(roll, targetRoll, smoothingFactor);
    yaw = lerp(yaw, targetYaw, smoothingFactor);

    drawPointer();

    // Draw spheres at each of the points in the path array
    drawPath();

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

// Draw the pointer and calculate the end coordinates of the line
function drawPointer() {
    push();  // Save the current transformation state

    // Apply initial orientation (fixed rotation)
    rotateX(initialPitch);
    rotateY(initialYaw);
    rotateZ(initialRoll);

    // Apply sensor-based rotation (dynamic orientation)
    rotateX(pitch);
    rotateY(yaw);
    rotateZ(roll);

    // Draw the cone representing the pointer
    stroke(0, 0, 0);  // Set the line color
    strokeWeight(5);        // Set line thickness
    fill(150, 0, 0);
    cone(50, 100, 8); // Draw the cone

    // Draw the line extending from the top of the cone
    line(0, 0, 0, 0, currentDistance, 0);  // Line extending from the cone

    pop();  // Restore the previous transformation state (ensuring axes aren't affected)
}

function drawAxes() {
    strokeWeight(10); // Set line thickness

    // X-axis in red
    stroke(255, 0, 0);
    line(-axisLength, 0, 0, axisLength, 0, 0);  // X-axis line (positive direction)
    drawConeAtEnd(axisLength, 0, 0);             // Draw cone at positive X

    // Y-axis in green
    stroke(0, 255, 0);
    line(0, -axisLength, 0, 0, axisLength, 0);  // Y-axis line (positive direction)
    drawConeAtEnd(0, axisLength, 0);              // Draw cone at positive Y

    // Z-axis in blue
    stroke(0, 0, 255);
    line(0, 0, -axisLength, 0, 0, axisLength);  // Z-axis line (positive direction)
    drawConeAtEnd(0, 0, axisLength);              // Draw cone at positive Z
}

function drawConeAtEnd(x, y, z) {
    push(); // Save the current transformation state
    translate(x, y, z); // Move to the cone position
    if (x != 0) rotateZ(-HALF_PI);
    if (y != 0) rotateY(-HALF_PI);
    if (z != 0) rotateX(HALF_PI);
    fill(0, 0, 0); // Cone color
    cone(20, 60, 8); // Draw cone (radius, height, detail)
    pop(); // Restore the previous transformation state
}

// Draw spheres along the path of the line's endpoint
function drawPath() {
    for (let i = 0; i < path.length; i++) {
        let pointOrientation = path[i];
        push();

        // Apply initial orientation (fixed rotation)
        rotateX(initialPitch);
        rotateY(initialYaw);
        rotateZ(initialRoll);
        
        // Apply sensor-based rotation (dynamic orientation)
        rotateX(pointOrientation.Pitch);
        rotateY(pointOrientation.Yaw);
        rotateZ(pointOrientation.Roll);

        translate(0, pointOrientation.Distance, 0);

        stroke(150, 0, 0);
        sphere(10); // Small sphere to represent the point

        pop();
    }
}

function startDrag() {
    isDragging = true;
    previousMouseX = mouseX;
    previousMouseY = mouseY;
}

function stopDrag() {
    isDragging = false;
}

function mouseWheel(event) {
    camRadius += event.delta * 0.5; // Decrease the zoom sensitivity for smoother zooming
}

// Update the sensor data and add the transformed point to the path
function updateSensorData(newPitch, newRoll, newYaw, newDistance) {
    // Update target orientation
    targetPitch = newPitch;
    targetRoll = newRoll;
    targetYaw = newYaw;

    // Update the distance value
    currentDistance = newDistance;

    let pointOrientation = {'Pitch': newPitch, 'Roll': newRoll, 'Yaw': newYaw, 'Distance': newDistance};
    path.push(pointOrientation);
}

function windowResized() {
    resizeCanvas(windowWidth - 26, windowHeight - 250);
}
