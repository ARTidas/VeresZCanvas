#include <Wire.h>
#include <MPU6050_6Axis_MotionApps20.h>

MPU6050 mpu;

// MPU control/status vars
bool dmpReady = false;  // set true if DMP init was successful
uint8_t mpuIntStatus;   // holds actual interrupt status byte from MPU
uint8_t devStatus;      // return status after each device operation (0 = success, !0 = error)
uint16_t packetSize;    // expected DMP packet size (default is 42 bytes)
uint16_t fifoCount;     // count of all bytes currently in FIFO
uint8_t fifoBuffer[64]; // FIFO storage buffer

// Orientation/motion vars
Quaternion q;           // [w, x, y, z]         quaternion container
VectorFloat gravity;     // [x, y, z]            gravity vector
float ypr[3];           // [yaw, pitch, roll]   yaw/pitch/roll container and gravity vector

void setup() {
    Serial.begin(115200);
    Wire.begin();

    // Initialize MPU6050
    Serial.println("Initializing MPU6050...");
    mpu.initialize();

    // Verify connection
    Serial.println("Testing connection...");
    Serial.println(mpu.testConnection() ? "MPU6050 connection successful" : "MPU6050 connection failed");

    // Initialize the DMP
    Serial.println("Initializing DMP...");
    devStatus = mpu.dmpInitialize();

    // Check if DMP init was successful
    if (devStatus == 0) {
        // Turn on the DMP, now that it's ready
        Serial.println("Enabling DMP...");
        mpu.setDMPEnabled(true);

        // Enable interrupt detection
        mpuIntStatus = mpu.getIntStatus();

        // Set the DMP ready flag
        dmpReady = true;

        // Get expected DMP packet size for later comparison
        packetSize = mpu.dmpGetFIFOPacketSize();
    } else {
        // DMP initialization failed
        Serial.print("DMP Initialization failed (code ");
        Serial.print(devStatus);
        Serial.println(")");
    }
}

void loop() {
    if (!dmpReady) return;  // Don't continue if DMP is not ready

    // Get current FIFO count
    fifoCount = mpu.getFIFOCount();

    // Check for overflow (this shouldn't happen unless the DMP stops processing)
    if (fifoCount == 1024) {
        // Reset FIFO to prevent overflow
        mpu.resetFIFO();
        Serial.println("FIFO overflow!");
    }

    // If we have the correct number of bytes, read and process data
    else if (fifoCount >= packetSize) {
        // Read a packet from FIFO
        mpu.getFIFOBytes(fifoBuffer, packetSize);

        // Get the orientation data (yaw, pitch, roll)
        mpu.dmpGetQuaternion(&q, fifoBuffer);
        mpu.dmpGetGravity(&gravity, &q);
        mpu.dmpGetYawPitchRoll(ypr, &q, &gravity);

        // Convert from radians to degrees
        ypr[0] *= 180.0 / M_PI;  // yaw
        ypr[1] *= 180.0 / M_PI;  // pitch
        ypr[2] *= 180.0 / M_PI;  // roll

        // Output values to Serial Monitor
        Serial.print("Yaw: ");
        Serial.print(ypr[0]);
        Serial.print(" | Pitch: ");
        Serial.print(ypr[1]);
        Serial.print(" | Roll: ");
        Serial.println(ypr[2]);
    }
}
