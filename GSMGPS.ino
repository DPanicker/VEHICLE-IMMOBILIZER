#include <AltSoftSerial.h> //Include all relevant libraries - see above
#include <TinyGPS++.h> 
#include <GSM.h>
const uint8_t polySides = 4; // 4 sides in your polygon
#define PINNUMBER "" // PIN Number for the SIM - leave blank unless your SIM has a pin, this is inserted between ""
//                          A,   B,   C,   D
float polyX[polySides] ; // X
float polyY[polySides] ; // Y


TinyGPSPlus gps; // The TinyGPS++ object for interfacing with the GPS
AltSoftSerial ss; // The serial connection object to the GPS device
char yourPassword[] = "Location";

static char password [255];
GSM gsmAccess; // Initialise the library instances
GSM_SMS sms;

char senderNumber[20]; // Array to hold the number a SMS is retreived from

void setup() 
{
  ss.begin(9600); // begin the GPS serial connection
  pinMode(13, OUTPUT);
  Serial.begin(115200); // begin Serial communication with the computer at 9600 baud rate

  Serial.println("Initialising..."); 
  
  boolean notConnected = true; // connection state

  while(notConnected) // until it connects
  {
    if(gsmAccess.begin(PINNUMBER)==GSM_READY) // if it succeeds connecting
      notConnected = false; // connected
    else
    {
      Serial.println("Not connected"); // print to the computer
      delay(1000); //delay
    }
  }
  Serial.println("GSM initialized");      // print to the computer
  Serial.println("Waiting for messages");
}

void loop() 
{
  int count_new = 0;
  while (ss.available() > 0) //while there is stuff in the buffer
    if (gps.encode(ss.read())) //if it can successfully decode it, do it. Else try again when more charachters are in the buffer
    if (sms.available()) // if a text has been recieved
  {
    Serial.println("Message received from:"); // print to the computer
    sms.remoteNumber(senderNumber, 20); // assign the sender number to the "senderNumber" variable 
    Serial.println(senderNumber); // print the sender number to the compute 
    char c;  
    while(c=sms.read())
    {
     password[count_new] = c; 
     count_new++;
    }
    Serial.println(password); // print the contents of the sms
    Serial.println("\nEND OF MESSAGE"); // print to the computer
    sms.flush(); // delete message from modem buffer
    Serial.println("MESSAGE DELETED"); // print to the computer
    if(!(strcmp(password,yourPassword)))
    {
      Serial.println("\nPASSWORD VALID"); // print to the computer
      sms.beginSMS(senderNumber); // begin an sms to the sender number
      sms.print("https://maps.google.com/maps?q=");
      sms.print(gps.location.lat(), 14); // append the lat to the sms
      sms.print(","); // append a comma
      sms.print(gps.location.lng(), 6); // append the lon to the sms
      sms.endSMS(); //send the sms
      Serial.print(gps.location.lat(), 14);
      memset (password,NULL,sizeof(password));
    }
    else
   {
     const char *delim  = ",";   //a comma is the delimiter
     char *firstItem;
     char *secondItem;
     char *thirdItem;
     char *fourthItem;
     char *fivethItem;
     char *sixthItem;
     char *seventhItem;
     char *eighthItem;
     firstItem = strtok(password,delim); 
     secondItem = strtok(NULL,delim);
     thirdItem = strtok(NULL,delim);
     fourthItem = strtok(NULL,delim);
     fivethItem = strtok(NULL,delim);
     sixthItem = strtok(NULL,delim);
     seventhItem = strtok(NULL,delim);
     eighthItem = strtok(NULL,delim);
     polyX[0] = atof(firstItem);
     polyY[0] = atof(secondItem);
     polyX[1] = atof(thirdItem);
     polyY[1] = atof(fourthItem);
     polyX[2] = atof(fivethItem);
     polyY[2] = atof(sixthItem);
     polyX[3] = atof(seventhItem);
     polyY[3] = atof(eighthItem);
     long double X = (gps.location.lng()) ;
     long double Y = (gps.location.lat()) ;
     float t = 78.123456 ;
     Serial.println(t);
     Serial.println( pointInPolygon(43.39, -80.32));
     if(pointInPolygon(43.39, -80.32)){
       Serial.println("Within the AREA");
       digitalWrite(13, LOW);
     }
     else
     {
       Serial.println("WARNING : OUTSIDE THE AREA\n RETURN TO BASE");
       digitalWrite(13, HIGH);
     }
     memset (password,NULL,sizeof(password));
   }
  }
  delay(1000); // delay
}

bool pointInPolygon( float x, float y )
{
  int i, j = polySides - 1;
  bool oddNodes = false;
  for ( i = 0; i < polySides; i++ )
  {
    if ( (polyY[i] < y && polyY[j] >= y || polyY[j] < y && polyY[i] >= y) &&  (polyX[i] <= x || polyX[j] <= x) )
    {
      oddNodes ^= ( polyX[i] + (y - polyY[i]) / (polyY[j] - polyY[i]) * (polyX[j] - polyX[i]) < x );
    }

    j = i;
  }
  return oddNodes;
}
