#include <WiFi.h> // Use WiFiS3 library for Arduino Uno R4 WiFi
#include <PubSubClient.h> // MQTT library

const char* ssid = "DLK";
const char* password = "1RKq<@+MWpdaCWT";
const char* mqttServer = "pti.unithe.hu";
const int mqttPort = 11883;
const char* mqttUser = "VeresZLidar";
const char* mqttPassword = "bolcseszmernok1";

WiFiClient wifiClient;
PubSubClient client(wifiClient);

// MQTT topic to publish to
const char* topic = "VeresZLidar_3";

// Variables for pitch, roll, and yaw
float pitch = -180;
float roll = -180;
float yaw = -180;
int distance = 600;

void setup() {
  Serial.begin(115200);

  // Connect to Wi-Fi
  connectToWiFi();

  // Set up the MQTT server
  client.setServer(mqttServer, mqttPort);

  // Connect to MQTT
  connectToMQTT();
}

void loop() {
  if (!client.connected()) {
    connectToMQTT();
  }
  client.loop();

  // Iterate through pitch, roll, and yaw from -180 to 180
  for (pitch = -180; pitch <= 180; pitch += 10) {
    for (roll = -180; roll <= 180; roll += 10) {
      for (yaw = -180; yaw <= 180; yaw += 10) {
        
        // Create the telemetry JSON string
        String telemetry = createTelemetryData(pitch, roll, yaw, distance);
        
        // Publish the telemetry data to the MQTT server
        client.publish(topic, telemetry.c_str());
        
        // Print to the serial monitor for debugging
        Serial.println(telemetry);

        delay(500); // Delay between telemetry data publishing
      }
    }
  }
}

void connectToWiFi() {
  Serial.print("Connecting to Wi-Fi");
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  Serial.println("\nConnected to Wi-Fi");
}

void connectToMQTT() {
  Serial.print("Connecting to MQTT");
  while (!client.connected()) {
    if (client.connect("ArduinoClient", mqttUser, mqttPassword)) {
      Serial.println("\nConnected to MQTT");
    } else {
      Serial.print(".");
      delay(2000);
    }
  }
}

String createTelemetryData(float pitch, float roll, float yaw, int distance) {
  // Create a JSON object for telemetry data
  String telemetry = "{";
  telemetry += "\"orientation\": {";
  telemetry += "\"pitch\": \"" + String(pitch, 2) + "\",";
  telemetry += "\"roll\": \"" + String(roll, 2) + "\",";
  telemetry += "\"yaw\": \"" + String(yaw, 2) + "\"";
  telemetry += "},";
  telemetry += "\"position\": {";
  telemetry += "\"x\": \"0.00\",";
  telemetry += "\"y\": \"0.00\",";
  telemetry += "\"z\": \"0.00\"";
  telemetry += "},";
  telemetry += "\"distance\": " + String(distance) + ",";
  telemetry += "\"timestamp\": \"" + getTimeStamp() + "\"";
  telemetry += "}";
  return telemetry;
}

String getTimeStamp() {
  // Replace with real timestamp generation or use a static one
  return "2024-10-06T21:01:32.433Z";
}
