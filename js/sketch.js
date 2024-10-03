let canvas = new Canvas();
let lidar;

class Lidar
{
    constructor(x, y, z, roll, pitch, yaw)
    {
        this.position = createVector(x, y, z);
        this.roll = roll;
        this.pitch = pitch;
        this.yaw = yaw;
    }

    show()
    {
        push();
        translate(this.position.x, this.position.y, this.position.z);
        rotateX(this.roll);
        rotateY(this.pitch);
        rotateZ(this.yaw);
        stroke(255);
        cone(10, 20, 4);
        stroke(255);
        strokeWeight(2);
        line(0, 0, 0, 0, 0, 100);
        pop();
    }

    update(roll, pitch, yaw)
    {
        this.roll = radians(roll);
        this.pitch = radians(pitch);
        this.yaw = radians(yaw);
    }
}

function setup()
{
    canvas.object = createCanvas(canvas.getWidth(), canvas.getHeight(), WEBGL);
    lidar = new Lidar(0, 0, 0, 0, 0, 0);

    updateLidarData(sensorData);

    setInterval(() => {
        updateLidarData(sensorData);
    }, 1000);
}

function draw()
{
    background(0);
    orbitControl();
    rotateX(-PI / 4);
    rotateY(-PI / 4);
    drawAxes();
    lidar.show();
}

function drawAxes()
{
    strokeWeight(2);
    stroke(255, 0, 0); /* X pozitiv */
    line(0, 0, 0, 500, 0, 0);
    stroke(255, 0, 0);
    line(0, 0, 0, -500, 0, 0);
    stroke(0, 255, 0);
    line(0, 0, 0, 0, 500, 0);
    stroke(0, 255, 0);
    line(0, 0, 0, 0, -500, 0);
    stroke(0, 0, 255);
    line(0, 0, 0, 0, 0, 500);
    stroke(0, 0, 255);
    line(0, 0, 0, 0, 0, -500);
}

function updateLidarData(sensorData)
{
    const latestData = sensorData.reduce((max, current) => {
        return (current.id > max.id) ? current : max;
    });

    lidar.update(latestData.roll, latestData.pitch, latestData.yaw);

    console.log("Lidar updated successfully.");
    console.log(latestData.roll, latestData.pitch, latestData.yaw);
}