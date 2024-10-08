#include <Wire.h>
#include <MPU6050_tockn.h>

MPU6050 mpu6050(Wire);

void setup() {
  Serial.begin(115200);
  Wire.begin();
  
  // Initialize MPU6050
  mpu6050.begin();
  mpu6050.calcGyroOffsets(true);  // Calibrate gyroscope and accelerometer

  Serial.println("MPU6050 initialized and calibrated.");
}

void loop() {
  // Update MPU6050 readings
  mpu6050.update();

  // Read raw accelerometer values
  float accX = mpu6050.getAccX();
  float accY = mpu6050.getAccY();
  float accZ = mpu6050.getAccZ();

  // Read raw gyroscope values
  float gyroX = mpu6050.getGyroX();
  float gyroY = mpu6050.getGyroY();
  float gyroZ = mpu6050.getGyroZ();

  // Print accelerometer values to Serial
  /*Serial.print("Accelerometer: ");
  Serial.print("X = "); Serial.print(accX);
  Serial.print(" | Y = "); Serial.print(accY);
  Serial.print(" | Z = "); Serial.println(accZ);*/

  // Print gyroscope values to Serial
  printf("X: %10.5f | %10.5f \n", gyroX, accX);

  // Delay for readability
  delay(100);  // 1 second delay between readings
}
