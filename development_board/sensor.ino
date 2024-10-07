#include <Wire.h>
#include <MPU6050_tockn.h>
#include <WiFi.h>
#include <PubSubClient.h>

MPU6050 mpu6050(Wire);

// Wi-Fi credentials
const char* ssid = "DLK";          // Your Wi-Fi SSID
const char* password = "1RKq<@+MWpdaCWT";  // Your Wi-Fi password

// MQTT broker information
const char* mqttServer = "pti.unithe.hu"; // IP address of your Mosquitto broker
const int mqttPort = 11883;                  // Default MQTT port
const char* mqttTopic = "VeresZLidar";      // Topic to publish data

WiFiClient wifiClient;
PubSubClient mqttClient(wifiClient);

float pitch, roll, yaw;

void setup() {
  Serial.begin(115200);
  Wire.begin();

  // Initialize MPU6050
  mpu6050.begin();
  mpu6050.calcGyroOffsets(true);

  // Connect to Wi-Fi
  connectToWiFi();

  // Set MQTT server
  mqttClient.setServer(mqttServer, mqttPort);
}

void loop() {
  // Update MPU6050 readings
  mpu6050.update();

  // Calculate pitch, roll, and yaw
  pitch = mpu6050.getAccX();  // Replace with your actual calculation
  roll = mpu6050.getAccY();   // Replace with your actual calculation
  yaw = mpu6050.getGyroZ();   // Replace with your actual calculation

  // Create a JSON object to hold sensor data
  String jsonData = String(
    "{\"orientation\":{"
    "\"pitch\":") + pitch + 
    ",\"roll\":" + roll + 
    ",\"yaw\":" + yaw + 
    "},\"position\":{"
    "\"x\":0.00,\"y\":0.00,\"z\":0.00},"
    "\"distance\":600.00,"
    "\"timestamp\":" + millis() + 
    "}";

  // Publish data to MQTT
  if (mqttClient.connected()) {
    mqttClient.publish(mqttTopic, jsonData.c_str());
  } else {
    reconnectMQTT();
  }

  Serial.println(jsonData);  // Print to Serial Monitor for debugging
  delay(1000);  // Delay between readings
}

void connectToWiFi() {
  Serial.print("Connecting to WiFi...");
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  
  Serial.println(" connected!");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
}

void reconnectMQTT() {
  while (!mqttClient.connected()) {
    Serial.print("Connecting to MQTT...");
    if (mqttClient.connect("WemosClient")) {
      Serial.println("connected");
    } else {
      Serial.print("failed, rc=");
      Serial.print(mqttClient.state());
      delay(2000);
    }
  }
}