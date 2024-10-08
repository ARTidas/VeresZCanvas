float distance = 600.00;

void setup() {
  Serial.begin(115200);
  Serial.println("=============================================================================================");
  Serial.println("=== BEGIN GENERATING TEST DATA ==============================================================");
  Serial.println("=============================================================================================");

  for (float pitch = -180.0; pitch <= 180.0; pitch += 30.0) {
    for (float roll = -180.0; roll <= 180.0; roll += 30.0) {
      for (float yaw = -180.0; yaw <= 180.0; yaw += 30.0) {

        String telemetry = "{";
        telemetry += "\"orientation\": {";
        telemetry += "\"yaw\": \"" + String(yaw, 2) + "\",";
        telemetry += "\"pitch\": \"" + String(pitch, 2) + "\",";
        telemetry += "\"roll\": \"" + String(roll, 2) + "\"";
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
        
        Serial.print(telemetry.c_str());
        Serial.println(",");
      }
    }
  }

  Serial.println("=============================================================================================");
  Serial.println("=== END GENERATING TEST DATA ================================================================");
  Serial.println("=============================================================================================");
}

void loop() {
}
