#include <Wire.h>
#include <MPU6050_6Axis_MotionApps20.h>
#include <WiFi.h>
#include <PubSubClient.h>

MPU6050 mpu;

// Wi-Fi credentials
const char* ssid = "DLK";
const char* password = "1RKq<@+MWpdaCWT";

// MQTT broker information
const char* mqttServer = "pti.unithe.hu";
const int mqttPort = 11883;
const char* mqttTopic = "VeresZLidar_3";

// MQTT authentication
const char* mqttUsername = "VeresZLidar";
const char* mqttPassword = "bolcseszmernok1";

WiFiClient wifiClient;
PubSubClient mqttClient(wifiClient);

// MPU control/status vars
bool dmpReady = false;
uint8_t mpuIntStatus;
uint8_t devStatus;
uint16_t packetSize;
uint16_t fifoCount;
uint8_t fifoBuffer[64];

// Orientation/motion vars
Quaternion q;
VectorFloat gravity;
float ypr[3];

float distance = 600.00;

// Define the interval for publishing
const unsigned long publishInterval = 1000; // 1 second
unsigned long lastPublishTime = 0;

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
    Serial.println("Enabling DMP...");
    mpu.setDMPEnabled(true);
    mpuIntStatus = mpu.getIntStatus();
    dmpReady = true;
    packetSize = mpu.dmpGetFIFOPacketSize();
  } else {
    Serial.print("DMP Initialization failed (code ");
    Serial.print(devStatus);
    Serial.println(")");
  }

  // Connect to Wi-Fi
  connectToWiFi();

  // Set MQTT server
  mqttClient.setServer(mqttServer, mqttPort);
}

void loop() {
  if (!dmpReady) return;

  // Ensure Wi-Fi is connected
  if (WiFi.status() != WL_CONNECTED) {
    connectToWiFi();
  }

  // Ensure MQTT is connected
  if (!mqttClient.connected()) {
    reconnectMQTT();
  }

  // Get current FIFO count
  fifoCount = mpu.getFIFOCount();

  // Check for overflow
  if (fifoCount == 1024) {
    mpu.resetFIFO();
    Serial.println("FIFO overflow!");
  } else if (fifoCount >= packetSize) {
    // Read a packet from FIFO
    mpu.getFIFOBytes(fifoBuffer, packetSize);

    // Get orientation data
    mpu.dmpGetQuaternion(&q, fifoBuffer);
    mpu.dmpGetGravity(&gravity, &q);
    mpu.dmpGetYawPitchRoll(ypr, &q, &gravity);

    // Convert from radians to degrees
    ypr[0] *= 180.0 / M_PI;  // yaw
    ypr[1] *= 180.0 / M_PI;  // pitch
    ypr[2] *= 180.0 / M_PI;  // roll
    /*ypr[0] *= 180.0 / M_PI + 180;  // yaw
    ypr[1] *= 180.0 / M_PI + 180;  // pitch
    ypr[2] *= 180.0 / M_PI + 180;  // roll*/
    /*ypr[0] *= 180.0 / M_PI;  // yaw
    ypr[1] *= 180.0 / M_PI;  // pitch
    ypr[2] *= 180.0 / M_PI;  // roll*/

    // Publish data at intervals
    unsigned long currentTime = millis();
    if (currentTime - lastPublishTime >= publishInterval) {
      lastPublishTime = currentTime;

      String telemetry = "{";
      telemetry += "\"orientation\": {";
      telemetry += "\"yaw\": \"" + String(ypr[0], 2) + "\",";
      telemetry += "\"pitch\": \"" + String(-ypr[2], 2) + "\","; //Negative to align chip scribings
      telemetry += "\"roll\": \"" + String(ypr[1], 2) + "\"";
      telemetry += "},";
      telemetry += "\"position\": {";
      telemetry += "\"x\": \"0.00\",";
      telemetry += "\"y\": \"0.00\",";
      telemetry += "\"z\": \"0.00\"";
      telemetry += "},";
      telemetry += "\"distance\": " + String(distance, 2) + ",";
      //telemetry += "\"timestamp\": \"" + getTimeStamp() + "\"";
      telemetry += "\"timestamp\": \"" + String(millis()) + "\"";
      telemetry += "}";
      /*String telemetry = String(
        "{\"orientation\":{"
        "\"yaw\":\"") + String(ypr[0], 2) + "\"" +
        ",\"pitch\":\"" + String(ypr[1], 2) + "\"" +
        ",\"roll\":\"" + String(ypr[2], 2) + "\"" +
        "},\"position\":{" +
        "\"x\":0.00,\"y\":0.00,\"z\":0.00},"
        "\"distance\": \"" + String(distance, 2) + "\" ,"
        "\"timestamp\":" + \"" + getTimeStamp() + "\"" + 
        "}";*/

      mqttClient.publish(mqttTopic, telemetry.c_str());
      Serial.println(telemetry);
    }
  }

  // Maintain the MQTT connection
  mqttClient.loop();
}

void connectToWiFi() {
  Serial.print("Connecting to WiFi...");
  WiFi.begin(ssid, password);

  unsigned long startAttemptTime = millis();
  while (WiFi.status() != WL_CONNECTED && millis() - startAttemptTime < 10000) {
    delay(1000);
    Serial.print(".");
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println(" connected!");
    Serial.print("IP address: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println(" failed to connect, retrying in 5 seconds...");
    delay(5000);
  }
}

void reconnectMQTT() {
  while (!mqttClient.connected()) {
    Serial.print("Connecting to MQTT...");
    if (mqttClient.connect("WemosClient", mqttUsername, mqttPassword)) {
      Serial.println("connected");
    } else {
      Serial.print("failed, rc=");
      Serial.println(mqttClient.state());
      delay(2000);
    }
  }
}
