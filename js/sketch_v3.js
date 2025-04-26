/* ********************************************************
 * *** Variables setup ************************************
 * ********************************************************/
let CanvasClass = new Canvas;
let canvas;

let path = []; // Array to store the coordinates
let path_point_radius = 10;
let path_point_threshold = path_point_radius;

/* ********************************************************
 * *** Setup **********************************************
 * ********************************************************/
function setup() {
    canvas = createCanvas(windowWidth - 26, windowHeight - 250);
    background(200);
    strokeWeight(40);
    noFill();
    stroke(0);

    const clearButton = document.getElementById('clearButton');
    clearButton.addEventListener('click', clearCanvas);
}

/* ********************************************************
 * *** MAIN LOOPING FUNCTION ******************************
 * ********************************************************/
function draw() {
    //background(200);
    //frameRate(10);
    if (mouseIsPressed) {
        line(pmouseX, pmouseY, mouseX, mouseY);
    }
}


/* ********************************************************
 * *** Helper and UI functions ****************************
 * ********************************************************/
function clearCanvas() {
    clear();
    background(200);
    stroke(0);
}

function windowResized() {
    resizeCanvas(windowWidth - 26, windowHeight - 250);
}