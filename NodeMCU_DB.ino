#include "DHT.h"
#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>

#define DHTPIN D1

#define DHTTYPE DHT11

DHT dht(DHTPIN,DHTTYPE);


float humedad;
float temperatura;


const char* ssid = "SSID";
const char* password = "password";
String server = "domain";//Servidor XAMPP


WiFiClient client;

HTTPClient http;

ESP8266WebServer serverNodeMCU(80);

void setup()
{
 Serial.begin(115200);
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
  
  serverNodeMCU.on("/", handle_OnConnect);
  serverNodeMCU.onNotFound(handle_NotFound);

  serverNodeMCU.begin();

  Serial.println("Servidor listo");
  
  Serial.println("Conectando a XAMPP...");
 }
void loop()
{ 
  humedad = dht.readHumidity();
  temperatura = dht.readTemperature(); 
  sendingData();
  serverNodeMCU.handleClient();
  delay(1000);
 }

void sendingData() {
  String url = "http://" + server + "/phpmyadmin/dht11.php?humidity=" + String(humedad) + "&temperature=" + String(temperatura);
  http.begin(client, url);
  int httpCode = http.GET();
  Serial.println(url);
  http.end();
  delay(10000);
}

void handle_OnConnect() {
  serverNodeMCU.send(200, "text/html", SendHTML(temperatura, humedad)); 
}

void handle_NotFound(){
  serverNodeMCU.send(404, "text/plain", "Not found");
}

String SendHTML(float temperatura, float humedad){
  String ptr = "<!DOCTYPE html> <html>\n";
  ptr +="<head><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, user-scalable=no\">\n";
  ptr +="<title>Estacion de temperatura NodeMCU</title>\n";
  ptr +="<style>html { font-family: Helvetica; display: inline-block; margin: 0px auto; text-align: center;}\n";
  ptr +="body{margin-top: 50px; background-color: black;} h1 {color: #FFFFFF;margin: 50px auto 30px;}\n";
  ptr +="p {font-size: 24px;color: #FFFFFF;margin-bottom: 10px;}\n";
  ptr +="</style>\n";
  ptr +="</head>\n";
  ptr +="<body>\n";
  ptr +="<div id=\"webpage\">\n";
  ptr +="<h1>Estacion de temperatura NodeMCU</h1>\n";
  
  ptr +="<p>Temperatura: ";
  ptr +=(float)temperatura;
  ptr +="°C</p>";
  ptr +="<p>Humedad: ";
  ptr +=(float)humedad;
  ptr +="%</p>";
  
  ptr +="</div>\n";
  ptr +="</body>\n";
  ptr +="</html>\n";
  return ptr;
}
