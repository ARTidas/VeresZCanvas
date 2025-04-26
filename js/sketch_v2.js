/* ********************************************************
 * *** Variables setup ************************************
 * ********************************************************/
let CanvasClass = new Canvas;
let canvas;
//let saliencyCanvas; // New canvas for saliency map

let model;

let path = []; // Array to store the coordinates
let path_point_radius = 10;
let path_point_threshold = path_point_radius;

let targetDigit = Math.floor(Math.random() * 10); // Random digit to draw
let background_color = 0;
let stroke_color = 255;



/* ********************************************************
 * *** Preload ********************************************
 * ********************************************************/
async function preload() {
    model = await tf.loadLayersModel('https://pti.unithe.hu:8443/common/js/TensorFlow/models/handwritten-digits-models.json');
    console.log("Model loaded successfully!");
}

/* ********************************************************
 * *** Setup **********************************************
 * ********************************************************/
function setup() {
    canvas = createCanvas(windowHeight - 250, windowHeight - 250);
    background(background_color);
    strokeWeight(40);
    noFill();
    stroke(stroke_color);

    // Create a separate canvas for saliency map (smaller size)
    /*saliencyCanvas = createGraphics(28, 28); // Adjust size as needed
    saliencyCanvas.pixelDensity(1); // Ensure it matches our input resolution
    saliencyCanvas.show();*/

    displayTask();
}

/* ********************************************************
 * *** MAIN LOOPING FUNCTION ******************************
 * ********************************************************/
function draw() {
    //background(200);
    //frameRate(30);
    //frameRate(15);
    //frameRate(20);
    frameRate(25);
    if (mouseIsPressed) {
        line(pmouseX, pmouseY, mouseX, mouseY);
    }

    if (model) {
        // Get prediction and saliency map
        const [prediction, confidence, saliencyMap] = predictDigit();

        // Update UI
        document.getElementById('prediction').textContent = prediction.indexOf(Math.max(...prediction));
        document.getElementById('confidence').textContent = `${Math.round(confidence * 100)}%`;

        // Draw saliency map and overlay
        //drawSaliencyMap(saliencyMap);
        //drawSaliencyOverlay(saliencyMap);

        // Show saliency canvas next to main canvas
        //image(saliencyCanvas, width + 20, 20, 100, 100);
    }
}





/* ********************************************************
 * *** Helper and UI functions ****************************
 * ********************************************************/
function displayTask() {
    targetDigit = Math.floor(Math.random() * 10);
    //targetDigit = 0;
    const circles = "●".repeat(targetDigit); // Repeat the black circle symbol
    document.getElementById("numberDisplay").innerText = (circles || "○"); // Show empty circle if 0
}


function getCanvasImage() {
    //let img = get({ willReadFrequently: true });
    let img = get();
    img.resize(28, 28);
    img.loadPixels();

    let input = [];
    for (let i = 0; i < img.pixels.length; i += 4) {
        let avg = (img.pixels[i] + img.pixels[i + 1] + img.pixels[i + 2]) / 3;
        input.push(avg / 255.0);
    }

    return tf.tensor(input, [1, 28, 28, 1]);
}
function getCanvasBase64Image() {
    let img = get();
    img.resize(28, 28);
    return img.canvas.toDataURL('image/png'); // Convert to base64 PNG format
}




function predictDigit() {
    if (!model) {
        alert("Model not loaded yet. Please wait.");
        return;
    }

    let img = get();
    img.resize(28, 28);
    img.loadPixels();
    let input = [];
    for (let i = 0; i < 784; i++) {
        input[i] = img.pixels[i * 4] / 255.0;
    }
    let xs = tf.tensor2d(input, [1, 784]);
    let reshapedXs = xs.reshape([1, 28, 28, 1]);

    // Make prediction
    const [prediction, confidence] = tf.tidy(() => {
        const pred = model.predict(reshapedXs).dataSync();
        return [Array.from(pred), Math.max(...pred)];
    });

    // Get predicted index correctly
    const predictedDigit = prediction.indexOf(Math.max(...prediction));

    // Compute saliency map
    const saliencyMap = tf.tidy(() => {
        const gradient = tf.grad(x => model.predict(x).sum())(reshapedXs);
        const absGradient = tf.abs(gradient);
        const maxAbsGradient = absGradient.max(3);  // Corrected axis usage
        return maxAbsGradient.squeeze();  // Removes single dimensions
    });
    //console.log("Saliency Map:", saliencyMap.arraySync());

    return [prediction, confidence, saliencyMap];
}


function clearCanvas() {
    clear();
    background(background_color);
    stroke(stroke_color);
}


// TODO: Make this function work.
/*function windowResized() {
    resizeCanvas(windowWidth - 26, windowHeight - 250);
}*/



function checkAnswer() {
    const [prediction, confidence, saliencyMap] = predictDigit();
    const predictedDigit = prediction.indexOf(Math.max(...prediction));
    //console.log(saliencyMap);
    console.log("Predicted:", predictedDigit, "Expected:", targetDigit);

    console.log(targetDigit + ', ' + predictedDigit + ', ' + confidence);
    sendToServer(
        targetDigit,
        predictedDigit,
        confidence
    )

    if (predictedDigit === targetDigit) {
        console.log('Bingo...');
        // TODO: Do some feedback...
    }
    else {
        console.log('Sorry, but no win.');
        // TODO: Do some feedback...
    }

    displayTask();
    clearCanvas();
}




function drawSaliencyMap(saliencyMap) {
    saliencyCanvas.background(255, 255, 255); // Set background
    saliencyCanvas.loadPixels();
    let saliencyData = saliencyMap.dataSync();
    //console.log(saliencyData);
    //console.log(saliencyData.slice(0, 10)); // Log some values
    let maxVal = Math.max(...saliencyData); // Find max for normalization

    for (let i = 0; i < 784; i++) {
        let pixelValue = saliencyData[i] / maxVal * 255; // Normalize and scale
        let x = i % 28;
        let y = Math.floor(i / 28);

        // Corrected index calculation for 28x28 canvas
        let index = (x + y * 28) * 4; 
        saliencyCanvas.pixels[index] = pixelValue;     // Red
        saliencyCanvas.pixels[index + 1] = 0;          // Green
        saliencyCanvas.pixels[index + 2] = 0;          // Blue
        saliencyCanvas.pixels[index + 3] = 255;        // Alpha (transparency)
    }

    saliencyCanvas.updatePixels();
}
function drawSaliencyOverlay(saliencyMap) {
    let overlay = createImage(28, 28);
    overlay.loadPixels();
    let saliencyData = saliencyMap.dataSync();
    let maxVal = Math.max(...saliencyData); // Find max for normalization

    for (let i = 0; i < 784; i++) {
        let pixelValue = saliencyData[i] / maxVal * 255; // Normalize and scale
        let x = i % 28;
        let y = Math.floor(i / 28);

        // Corrected index calculation for 28x28 canvas
        let index = (x + y * 28) * 4;

        overlay.pixels[index] = pixelValue;     // Red
        overlay.pixels[index + 1] = 0;          // Green
        overlay.pixels[index + 2] = 0;          // Blue
        overlay.pixels[index + 3] = 100;        // Alpha (transparency)
    }

    overlay.updatePixels();
    blendMode(ADD);
    image(overlay, 0, 0, width, height);
    blendMode(NORMAL);
}




function sendToServer(targetDigit, predictedDigit, confidence) {
    fetch("https://pti.unithe.hu:8443/veresz_canvas/api_digit/create", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            image_data:         getCanvasBase64Image(),
            target_digit:       targetDigit,
            predicted_digit:    predictedDigit,
            confidence:         confidence
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server Response:", data);
    })
    .catch(error => {
        console.error("Error sending data:", error);
    });
}