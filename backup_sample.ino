/*
 * This ESP32 code is created by esp32io.com
 *
 * This ESP32 code is released in the public domain
 *
 * For more detail (instruction and wiring diagram), visit https://esp32io.com/tutorials/esp32-rfid-nfc
 */

#include <SPI.h>
#include <MFRC522.h>
#include <HTTPClient.h>
#include <WiFi.h>


#define SS_PIN  5  // ESP32 pin GPIO5 
#define RST_PIN 21 // ESP32 pin GPIO21

#define SS_PIN_2  14  // ESP32 pin for the second RFID reader (e.g., GPIO18)
#define RST_PIN_2 27  // ESP32 pin for the second RFID reader (e.g., GPIO19)

#define SS_PIN_3  26  // ESP32 pin for the third RFID reader (e.g., GPIO32)
#define RST_PIN_3 25  // ESP32 pin for the third RFID reader (e.g., GPIO33)

#define SS_PIN_4  33  // ESP32 pin for the third RFID reader (e.g., GPIO32)
#define RST_PIN_4 32  // ESP32 pin for the third RFID reader (e.g., GPIO33)

MFRC522 rfid(SS_PIN, RST_PIN);
MFRC522 rfid2(SS_PIN_2, RST_PIN_2); // Instance for the second reader
MFRC522 rfid3(SS_PIN_3, RST_PIN_3); // Instance for the third reader
MFRC522 rfid4(SS_PIN_4, RST_PIN_4); // Instance for the third reader


const char* ssid = "Redmi 10C";
const char* password = "1099shanhao";
const char* serverAddress = "http://192.168.188.235/FYPwebsystem/rfid_receiver.php";

// Keep this API Key value to be compatible with the PHP code provided in the project page. 
// If you change the apiKeyValue value, the PHP file /post-esp-data.php also needs to have the same key 
String apiKeyValue = "tPmAT5Ab3j7F9";

// Function prototypes
int findTagIndex(String cardData);
int findEmptyTagIndex();


// Data structure to maintain tag states
struct TagState {
  String tagData;
  String readerID;
  bool inStockroom;
};

TagState tagStates[10]; // Adjust the array size based on your requirements


void setup() {
    Serial.begin(115200);
    delay(1000);

    // Connect to Wi-Fi
    connectWiFi();

    SPI.begin(); // init SPI bus
    rfid.PCD_Init(); // Initialize the MFRC522 reader
    rfid2.PCD_Init(); // Initialize the second MFRC522 reader
    rfid3.PCD_Init(); // Initialize the third MFRC522 reader
    rfid4.PCD_Init(); 

    // Initialize tag states
    for (int i = 0; i < 10; i++) {
      tagStates[i].tagData = "";
      tagStates[i].readerID = "";
      tagStates[i].inStockroom = false;
    }
}

void loop() {
  
  if(WiFi.status() != WL_CONNECTED) {
    connectWiFi();
  }

    // Check for a new tag on the first reader
    // For the readers (insert/update stockroom)
    if (rfid.PICC_IsNewCardPresent() && rfid.PICC_ReadCardSerial()) {
        String cardData = getScannedData(rfid.uid);
        handleTagMovement(cardData, "Stockroom A", "in"); // Send "in" action for reader number 1
        delay(1000); // Delay for 1 seconds
    }

    if (rfid2.PICC_IsNewCardPresent() && rfid2.PICC_ReadCardSerial()) {
        String cardData = getScannedData(rfid2.uid);
        handleTagMovement(cardData, "Stockroom B", "in");  // ||
        delay(1000);
    }

    // For the third reader (checkout)
    if (rfid3.PICC_IsNewCardPresent() && rfid3.PICC_ReadCardSerial()) {
        String cardData = getScannedData(rfid3.uid);
        handleTagMovement(cardData, "Stockroom C", "in");  // ||
        delay(1000);
    }

    // For the fourth reader (checkout)
    if (rfid4.PICC_IsNewCardPresent() && rfid4.PICC_ReadCardSerial()) {
        String cardData = getScannedData(rfid4.uid);
        handleTagMovement(cardData, "Checkout", "checkout");  // ||
        delay(1000);
    }

}

String getScannedData(MFRC522::Uid uid) {
    String cardData = "";
    for (byte i = 0; i < uid.size; i++) {
        cardData += String(uid.uidByte[i], HEX);
    }
    return cardData;
}


void connectWiFi() {
  WiFi.mode(WIFI_OFF);
  delay(1000);
  //This line hides the viewing of ESP as wifi hotspot
  WiFi.mode(WIFI_STA);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi");
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print("..");
  }
    
  Serial.print("connected to : "); Serial.println(ssid);
  Serial.print("IP address: "); Serial.println(WiFi.localIP());
}


//scan and send data & reader no to php
// Regular action for other readers (insert/update stockroom)
void sendDataToServer(String cardData, String readerID, String action) {
  HTTPClient http;
  http.begin(serverAddress);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  String postData = "api_key=" + apiKeyValue + "&card_data=" + cardData + "&reader=" + readerID + "&action=" + action + "";

  int httpResponseCode = http.POST(postData);

  if (httpResponseCode > 0) {
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);
    String response = http.getString();
    Serial.println(response);
  } else {
    Serial.print("Error sending POST request, HTTP error code: ");
    Serial.println(httpResponseCode);
  }

  http.end();
}


//function for handle tacking tag movement within each stockroom
void handleTagMovement(String cardData, String readerID, String action) {
    int index = findTagIndex(cardData);
    if (index != -1) {
        // The tag is known
        if (tagStates[index].readerID == readerID) {
            if (action == "checkout") {
                // Handle the "checkout" action
                sendDataToServer(cardData, readerID, action);
            } else {
                // For other readers, allow both "in" and "out" movements
                if (tagStates[index].inStockroom) {
                    tagStates[index].inStockroom = false;
                    sendDataToServer(cardData, readerID, "out");
                } else {
                    tagStates[index].inStockroom = true;
                    sendDataToServer(cardData, readerID, "in");
                }
            }
        } else {
            // Tag is scanned by a different reader
            if (!tagStates[index].inStockroom) {
                tagStates[index].readerID = readerID;
            }
        }
    } else {
        // The tag is not known
        int emptyIndex = findEmptyTagIndex();
        if (emptyIndex != -1) {
            tagStates[emptyIndex].tagData = cardData;
            tagStates[emptyIndex].readerID = readerID;
            tagStates[emptyIndex].inStockroom = !action.equals("checkout"); // For "checkout" reader, set inStockroom to false
            sendDataToServer(cardData, readerID, action);
        }
    }
}


int findTagIndex(String cardData) {
  for (int i = 0; i < 10; i++) {
    if (tagStates[i].tagData == cardData) {
      return i;
    }
  }
  return -1; // Tag not found
}

int findEmptyTagIndex() {
  for (int i = 0; i < 10; i++) {
    if (tagStates[i].tagData == "") {
      return i;
    }
  }
  return -1; // No empty slot
}