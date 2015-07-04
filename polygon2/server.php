<?php
/*
 * Author: Xu Ding
 * website: www.startutorial.com
 *          www.the-di-lab.com
 */
class Polygon
{
    static $_dbHost     = 'localhost'; 
    static $_dbName     = 'polygon';   
    static $_dbUserName = 'root';  
    static $_dbUserPwd  = '';
     
    // get coordinates
    static public function getCoords()
    {
        return self::get();
    }
     
    // save coordinates
    static public function saveCoords($rawData)
    {
        self::save($rawData);
    }
     
    // save lat/lng to database
    static public function save ($data)
    {
        $con = mysql_connect(self::$_dbHost, self::$_dbUserName, self::$_dbUserPwd);
         
        // connect to database
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }
         
        mysql_select_db(self::$_dbName, $con);
         
        // delete old data
        mysql_query("DELETE FROM points");
         
        // insert data
		mysql_query("INSERT INTO points (data) VALUES ('$data')");
 
require "/Services/Twilio.php";
 
// set your AccountSid and AuthToken from www.twilio.com/user/account
$sid = "ACde2645edd4c00ed6fd38a673eb7e501e";
$token = "536c172366a0f0998572460911a4f2ed";
 
$http = new Services_Twilio_TinyHttp(
    'https://api.twilio.com',
    array('curlopts' => array(
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 2,
    )));
	
$client = new Services_Twilio($sid, $token, "2010-04-01", $http);

$message = $client->account->messages->create(array(
    "From" => "+1226-894-0217",
    "To" => "+1519-729-5955",
    "Body" => $data,
));
 
// Display a confirmation message on the screen
//echo "Sent message {$message->sid}";
/* $txt ="(1.525546034678125, 103.86886596679688)(1.4212104387885494, 103.80294799804688)(1.3553118222790563, 103.94027709960938)(1.4843615162701822, 104.01168823242188)"
//$array = preg_split( "/ ((|,) /", $txt );
parse_str($txt, $array);
echo $array[0];
echo $array[1];
echo $array[2];
echo $array[3]; */


  // close connection
        mysql_close($con);
    }  
     
    // get lat/lng from database
    static private function get()
    {  
        $con = mysql_connect(self::$_dbHost, self::$_dbUserName, self::$_dbUserPwd);
         
        // connect to database
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }
         
        mysql_select_db(self::$_dbName, $con);
         
        $result = mysql_query("SELECT * FROM points");
                 
        $data   = false;
         
        while($row = mysql_fetch_array($result,MYSQL_ASSOC))
        {
            $data = $row['data'];
        }
         
        // close connection
        mysql_close($con);     
         
        return $data;
    }
     
}
