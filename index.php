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

function isForbidden($params) {
  if (!isset($params['payload']) || $params['payload'] !== 'matte_is_the_greatest') {
    Flight::json(['status' => 'error', 'error' => 'forbidden']);
    return true;
  }
  return false;
}

Flight::route('POST /api/status', function() {
		$params = json_decode(file_get_contents("php://input"), true);

    if (isForbidden($params)) return;

		$status = quote($params['data']);
    $data = Flight::db()->query("UPDATE params SET status=$status WHERE id=0");

		if (!$data) {
			Flight::json(['status' => 'error', 'error' => 'query_failed']);
		} else {
			Flight::json(['status' => 'ok', 'data' => ['status' => $status]]);
		}
});

Flight::route('GET /api/round', function() {
    $conn = Flight::db();
    $data = $conn->query("SELECT round FROM params WHERE id=0");

		if (!$data) {
			Flight::json(['status' => 'error', 'error' => 'query_failed']);
		} elseif ($data->rowCount() == 0) {
			Flight::json(['status' => 'error', 'error' => 'not_found']);
		} else {
			Flight::json(['status' => 'ok','data' => $data->fetch()->round]);
		}
});

Flight::route('POST /api/round', function() {
		$params = json_decode(file_get_contents("php://input"), true);

    if (isForbidden($params)) return;

		$round = $params['data'];
    $data = Flight::db()->query("UPDATE params SET round=$round WHERE id=0");

		if (!$data) {
			Flight::json(['status' => 'error', 'error' => 'query_failed']);
		} else {
			Flight::json(['status' => 'ok']);
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

Flight::route('POST /api/players', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

        $conn = Flight::db();
        $data = $conn->query("SELECT name FROM players");

		if (!$data) {
			Flight::json(['status' => 'error', 'error' => 'query_failed']);
		} else {
      $names = array_map(function($item) { return $item->name; }, $data->fetchAll());
      Flight::json(['status' => 'ok','data' => $names]);
		}
});

Flight::route('GET /api/results', function() {
        $conn = Flight::db();
        $data = $conn->query("SELECT player, state, score, place FROM results");

		if (!$data) {
			Flight::json(['status' => 'error', 'error' => 'query_failed']);
		} else {
      $results = array_map(function($item) { return [$item->player, $item->state, $item->place, $item->score]; }, $data->fetchAll());
      Flight::json(['status' => 'ok','data' => $results]);
		}
});

Flight::route('POST /api/result', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $round = $params['round'];
  $board = $params['board'];
  $player = quote($params['player']);
  $score = $params['score'];
  $place = $params['place'];

  $conn = Flight::db();
  $sql = "UPDATE results SET score=$score,place=$place WHERE round=$round AND board=$board AND player=$player";
	$data = $conn->query($sql);

	if (!$data) {
		Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
	} else {
    Flight::json(['status' => 'ok']);
	}
});

Flight::route('POST /api/results', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $results = $params['data'];

  $conn = Flight::db();

  $data = $conn->query("SELECT round FROM params WHERE id=0");
  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $round = $data->fetch()->round;
    $sql = "";
    for ($i = 0; $i < count($results); $i++) {
      $name = $results[$i]['name'];
      $score = $results[$i]['score'];
      $place = $results[$i]['place'];
      $sql = $sql."UPDATE results SET state='idle',score=$score,place=$place WHERE round=$round AND player='$name';";
    }

    $data = $conn->query($sql);
    if (!$data) {
      Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
    } else {
      Flight::json(['status' => 'ok']);
    }
  }


	if (!$data) {
		Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
	} else {
    Flight::json(['status' => 'ok']);
	}
});

Flight::route('POST /api/state', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $round = $params['round'];
  $board = $params['board'];
  $player = quote($params['player']);
  $state = quote($params['state']);

  $conn = Flight::db();
  $sql = "UPDATE results SET state=$state WHERE round=$round AND board=$board AND player=$player";
	$data = $conn->query($sql);

	if (!$data) {
		Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
	} else {
    Flight::json(['status' => 'ok']);
	}
});

Flight::route('POST /api/states', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $lists = $params['data'];
  $idle = $lists[0];
  $playing = $lists[1];
  $players = array();
  $caseQuery = "case";

  for ($i = 0; $i < count($idle); $i++) {
    $value = $idle[$i];
    $caseQuery = $caseQuery." when player = '$value' then 'idle'";
    $players[] = "'$value'";
  }
  for ($i = 0; $i < count($playing); $i++) {
    $value = $idle[$i];
    $caseQuery = $caseQuery." when player = '$value' then 'idle'";
    $players[] = "'$value'";
  }

  $caseQuery = $caseQuery." else 'no' end";

  $conn = Flight::db();
  $sql = "UPDATE results SET state=($caseQuery) WHERE 1";
	$data = $conn->query($sql);

	if (!$data) {
		Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
	} else {
    Flight::json(['status' => 'ok']);
	}
});

Flight::route('POST /api/seatings', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $seatings = $params['data'];
  $size = count($seatings) / 4;
  $values = array();
  for ($i = 1; $i <= 4; $i++) { // rounds
    for ($j = 1; $j <= $size; $j++) { // tables
      for ($k = 1; $k <= 4; $k++) { // players
        $name = $seatings[$j - 1 + ($i - 1) * $size][$k - 1];
        $values[] = "($i, $j, '$name')";
      }
    }
  }

  $conn = Flight::db();
  $sql = "DELETE FROM results WHERE 1; INSERT INTO results(round, board, player) VALUES ".implode(',', $values);
  $data = $conn->query($sql);

	if (!$data) {
		Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
	} else {
    Flight::json(['status' => 'ok']);
	}
});

Flight::route('GET /api/confirmations', function() {
        $conn = Flight::db();
        $data = $conn->query("SELECT name, confirmation, idle FROM confirms");

		if (!$data) {
			Flight::json(['status' => 'error', 'error' => 'query_failed']);
		} else {
      $results = array_map(function($item) {
         return [$item->name, $item->confirmation, $item->idle];
       }, $data->fetchAll());
      Flight::json(['status' => 'ok','data' => $results]);
		}
});

Flight::route('POST /api/confirmations', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $players = $params['data'];
  $size = count($players);
  $values = array();
  for ($i = 0; $i < $size; $i++) {
    $name = $players[$i];
    $values[] = "('$name')";
  }

  $conn = Flight::db();
  $sql = "DELETE FROM confirms WHERE 1; INSERT INTO confirms(name) VALUES ".implode(',', $values);
  $data = $conn->query($sql);

	if (!$data) {
		Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
	} else {
    Flight::json(['status' => 'ok']);
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
