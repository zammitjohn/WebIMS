<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/registry.php';
include_once '../objects/users.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare registry item object
$item = new Registry($db);
 
// inventoryId of registry item to list
$item->inventoryId = isset($_GET['inventoryId']) ? $_GET['inventoryId'] : die();

// AUTH check
$user = new Users($db); // prepare users object
if (isset($_COOKIE['UserSession'])){ // Cookie authentication 
	$user->sessionId = htmlspecialchars(json_decode(base64_decode($_COOKIE['UserSession'])) -> {'SessionId'}); 
}
if (isset($_SERVER['HTTP_AUTH_KEY'])){ // Header authentication
	$user->sessionId = $_SERVER['HTTP_AUTH_KEY'];
}
if (!$user->validAction()){
    header("HTTP/1.1 401 Unauthorized");
    die();
}

// query registry item
$stmt = $item->read();

if ($stmt != false){
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // registry item array
        $output_arr=array();
        $output_arr["Registry"]=array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $registry_item=array(
                "id" => $id,
                "inventoryId" => $inventoryId,
                "serialNumber" => $serialNumber,
                "datePurchased" => $datePurchased,
                "state" => $state
            );
            array_push($output_arr["Registry"], $registry_item);
        }
    
        echo json_encode($output_arr["Registry"]);
    }
    else{
        echo json_encode(array());
    }
}