<?php
error_reporting(E_ALL);
ini_set("display_errors", "1");
require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

function getDB()
{
    $dbhost = "localhost";
    $dbuser = "embraceuser";
    $dbpass = "mVTXrFJAQhCmpbU7";
    $dbname = "yembrace";
 
    $mysql_conn_string = "mysql:host=$dbhost;dbname=$dbname";
    $dbConnection = new PDO($mysql_conn_string, $dbuser, $dbpass); 
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConnection;
}
 
function getMongoDB()
{
    $connection=new Mongo();
	$db = $connection->yembrace;

    return $db;
}
 
$app->get('/', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API";
}); 

include_once("admin_users.php"); 
include_once("address_users.php"); 
include_once("beacons_company.php");
include_once("beacons_master.php"); 
include_once("city.php"); 
include_once("company.php"); 
include_once("company_shop.php");
include_once("country.php");
include_once("productbrand.php");
include_once("productcategory.php");
include_once("colors.php");
include_once("manufacturer.php");
include_once("productpaymentmethod.php");
include_once("productsize.php");
include_once("productstatus.php");
include_once("products.php");
include_once("shop_staffs.php");
include_once("staffroles.php");
include_once("states.php");
include_once("users.php");
include_once("mail.php");
include_once("categorytoplevel.php");
include_once("tagstable.php");
include_once("offertagmapping.php");
include_once("appinfo.php");
include_once("producttagmapping.php");
include_once("shoptagmapping.php");
include_once("admin_role.php");
include_once("appinfo.php");
include_once("active_users.php");
include_once("formstable.php");
include_once("formresponse.php");
include_once("formfieldtable.php");
include_once("formfielddatatype.php");
include_once("rule.php");
include_once("contentType.php");
include_once("triggerType.php");
include_once("broadcast.php");
include_once("offers.php");
include_once("notificationtable.php");
include_once("reviews.php");

function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);
    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
    //echo $response;
}


$app->run();

function mt_rand_str ($l, $c = 'abcdefghijklmnopqrstuvwxyz1234567890') {
    for ($s = '', $cl = strlen($c)-1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i);
    return $s;
}
?>
