#include <LiquidCrystal.h>
#include "DHT.h"
#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>

const int RS = D2, EN = D3, d4 = D5, d5 = D6, d6 = D7, d7 = D8;

LiquidCrystal lcd(RS, EN, d4, d5, d6, d7);

#define DHTPIN D1

#define DHTTYPE DHT11

DHT dht(DHTPIN,DHTTYPE);

float humedad;
float temperatura;

int pinMQ135 = A0;

float ppmCo2;
double alcohol;

const char* ssid = "SSID";
const char* password = "password";
String server = "server";//Servidor XAMPP


WiFiClient client;

HTTPClient http;

void setup()
{
 Serial.begin(115200);
  lcd.begin(16,2);
  lcd.setCursor(0,0);
  lcd.print("Reto IoT");
  delay(10);
  dht.begin();
  Serial.println();
  Serial.println();
  Serial.println("Conectado a red: ");
  Serial.print(ssid);
 
  WiFi.begin(ssid, password);
 
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi listo");
 
  // Start the server
//  server.begin();
  Serial.println("Servidor: ");
  Serial.println(WiFi.localIP());
  Serial.println("MAC Adress: ");
  Serial.println(WiFi.softAPmacAddress());
  delay(1000);
  
  Serial.println("Conectando a XAMPP...");
 }
void loop()
{   
  humedad = dht.readHumidity();
  temperatura = dht.readTemperature();
  float ppmCo2 = analogRead(pinMQ135);
  float voltaje = ppmCo2 * (5.0 / 1023.0);
  float Rs = 1000 * ((5 - voltaje) / voltaje);
  double alcohol = 0.4091 * pow(Rs / 5463, -1.497);
  sendingData(ppmCo2, alcohol);
  lcd.begin(16,2);
  lcd.setCursor(0,0);
  lcd.print("Temp:" + String(int(temperatura)) + " Hum:" + String(int(humedad)));
  lcd.setCursor(0,1);
  lcd.print("CO2:" + String(int(ppmCo2)) + " A:" + String(alcohol));
  delay(1000);
 }

void sendingData(float ppmCo2, double alcohol) {
  String url = "http://" + server + "/retoiot/datos.php?Temperatura="
  + String(temperatura) + "&Humedad=" + String(humedad) + "&CO2=" +
  String(ppmCo2) + "&Alcohol=" + String(alcohol);
  http.begin(client, url);
  int httpCode = http.GET();
  Serial.println(url);
  Serial.println(httpCode);
  http.end();
  delay(10000);
}
