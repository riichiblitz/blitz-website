<?php

header('Access-Control-Allow-Origin: *');

require_once 'flight/Flight.php';

$dbhost = getenv('OPENSHIFT_MYSQL_DB_HOST');
$dbport = getenv('OPENSHIFT_MYSQL_DB_PORT');
$dbusername = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
$dbpassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
$db_name = getenv('OPENSHIFT_GEAR_NAME');

Flight::register('db', 'PDO', array("mysql:host=$dbhost;port=$dbport;dbname=$db_name", $dbusername, $dbpassword), function($db) {
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$db->query ("set character_set_client='utf8'"); 
        $db->query ("set character_set_results='utf8'");
        $db->query ("set collation_connection='utf8_general_ci'"); 
});

function quote($input) {
	return $input ? "'$input'" : "NULL";
}

Flight::route('GET /api/info', function() {
        Flight::json(['status' => 'ok', 'data' => getallheaders()]);
});

Flight::route('GET /api/language', function() {
        Flight::json(['status' => 'ok', 'data' => explode(',', getallheaders()['Accept-Language'])]);
});

Flight::route('GET /api/status', function() {
        $conn = Flight::db();
        $data = $conn->query("SELECT status FROM params WHERE id=0");
        
		if (!$data) {
			Flight::json(['status' => 'error', 'error' => 'query_failed']);
		} elseif ($data->rowCount() == 0) {
			Flight::json(['status' => 'error', 'error' => 'not_found']);
		} else {
			Flight::json(['status' => 'ok','data' => $data->fetch()]);
		}
});

Flight::route('POST /api/status', function() {
		$params = json_decode(file_get_contents("php://input"), true);
		$status = quote($params['status']);
		
		$data = Flight::db()->query("UPDATE params SET status=$status WHERE id=0");
		
		if (!$data) {
			Flight::json(['status' => 'error', 'error' => 'query_failed']);
		} else {
			Flight::json(['status' => 'ok', 'data' => ['status' => $status]]);
		}
});

Flight::route('GET /api/players', function() {
        $conn = Flight::db();
        $data = $conn->query("SELECT name FROM players WHERE anonymous=0");
        
		if (!$data) {
			Flight::json(['status' => 'error', 'error' => 'query_failed']);
		} else {
			$count = $conn->query("SELECT COUNT(*) as count FROM players");
			if (!$count) {
			    Flight::json(['status' => 'error', 'error' => 'query_failed']);
			} else {
				$names = array_map(function($item) { return $item->name; }, $data->fetchAll());
			    Flight::json(['status' => 'ok','data' => ['count' => $count->fetch()->count, 'names' => $names]]);
			}
		}
});

Flight::route('POST /api/apply', function() {
        $conn = Flight::db();
		
        $params = json_decode(file_get_contents("php://input"), true);
		
		$name = quote($params['name']);
		$contacts = quote($params['contacts']);
		$notify = $params['notify'];
		$anonymous = $params['anonymous'];
		
		$data = $conn->query("INSERT INTO players(name, contacts, notify, anonymous) VALUES($name, $contacts, $notify, $anonymous)");
		
		if (!$data) {
			Flight::json(['status' => 'error', 'error' => 'query_failed']);
		} else {
			Flight::json(['status' => 'ok', 'data' => intval($conn->lastInsertId())]);
		}
});


Flight::start();
?>
