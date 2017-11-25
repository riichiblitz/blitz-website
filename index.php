<?php

if (!function_exists('getallheaders')) {
  function getallheaders() {
    $headers = [];
    foreach ($_SERVER as $name => $value) {
       if (substr($name, 0, 5) == 'HTTP_') {
         $realName = substr($name, 5);
         $realName = str_replace('_', ' ', $realName);
         $realName = ucwords(strtolower($realName));
         $realName = str_replace(' ', '-', $realName);
         $headers[$realName] = $value;
       }
    }
    return $headers;
  }
}

header('Access-Control-Allow-Origin: *');

require_once 'flight/Flight.php';

$prefix = getenv('PREFIX');//"iormc";

$dbhost = getenv('MYSQL_HOST');
$dbport = getenv('MYSQL_PORT');//3306;
//if (!dbhost) {
//  $dbhost = getenv(strtoupper(getenv("YSQL_SERVICE_NAME"))."_SERVICE_HOST");
//  $dbport = getenv(strtoupper(getenv("OPENSHIFT_MYSQL_SERVICE_NAME"))."_SERVICE_PORT");
//}

$dbusername = getenv('MYSQL_USERNAME');
$dbpassword = getenv('MYSQL_PASSWORD');
$db_name = getenv('MYSQL_DATABASE');
$secret_token = getenv('SECRET_TOKEN');

$player_per_table = 4;
$max_rounds = 4;

$reset_sql = "
DROP TABLE IF EXISTS `$prefix-params` ;
DROP TABLE IF EXISTS `$prefix-forceseats` ;
DROP TABLE IF EXISTS `$prefix-new_replays` ;
DROP TABLE IF EXISTS `$prefix-registrations` ;
DROP TABLE IF EXISTS `$prefix-replays` ;
DROP TABLE IF EXISTS `$prefix-reports` ;
DROP TABLE IF EXISTS `$prefix-results` ;
DROP TABLE IF EXISTS `$prefix-wish` ;
CREATE TABLE IF NOT EXISTS `$prefix-params` ( `id` int(11) NOT NULL AUTO_INCREMENT, `status` varchar(15) NOT NULL, `round` int(1) NOT NULL, `time` bigint(13) NOT NULL, `lobby` varchar(10) NOT NULL, `delay` int(10) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
DELETE FROM `$prefix-params` WHERE `id`=1 ;
INSERT INTO `$prefix-params` (`id`, `status`, `round`, `time`, `lobby`, `delay`) VALUES (1, 'announce', 0, 0, '—', 600000);
CREATE TABLE IF NOT EXISTS `$prefix-forceseats` ( `id` int(11) NOT NULL AUTO_INCREMENT, `names` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `$prefix-new_replays` ( `id` int(11) NOT NULL AUTO_INCREMENT, `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, `cheat` int(1) NOT NULL DEFAULT '0', `done` int(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;
CREATE TABLE IF NOT EXISTS `$prefix-registrations` ( `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(20) NOT NULL, `contacts` varchar(2000) DEFAULT NULL, `comment` varchar(200) DEFAULT NULL, `anonymous` int(1) NOT NULL, `notify` int(1) NOT NULL, `news` int(1) NOT NULL, `lang` varchar(200) NOT NULL, `discordName` varchar(2000) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL, `discriminator` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL, `offline` varchar(2000) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL, `confirmed` int(1) NOT NULL DEFAULT '0', `disqual` int(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=78 ;
CREATE TABLE IF NOT EXISTS `$prefix-replays` ( `id` int(11) NOT NULL AUTO_INCREMENT, `round` int(1) NOT NULL, `board` int(2) NOT NULL, `url` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `round` (`round`,`board`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;
CREATE TABLE IF NOT EXISTS `$prefix-reports` ( `id` int(11) NOT NULL AUTO_INCREMENT, `who` varchar(20) NOT NULL, `message` varchar(1000) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;
CREATE TABLE IF NOT EXISTS `$prefix-results` ( `id` int(11) NOT NULL AUTO_INCREMENT, `round` int(1) NOT NULL, `board` int(2) NOT NULL, `player_id` int(3) NOT NULL, `start_points` int(5) DEFAULT NULL, `end_points` int(6) DEFAULT NULL, `place` int(1) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=201 ;
CREATE TABLE IF NOT EXISTS `$prefix-wish` ( `id` int(11) NOT NULL AUTO_INCREMENT, `who` int(11) NOT NULL, `withWhom` int(11) NOT NULL, `done` int(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";

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
  global $prefix;
  Flight::json(['status' => 'ok', 'prefix' => $prefix, 'data' => getallheaders()]);
});

Flight::route('GET /api/language', function() {
  Flight::json(['status' => 'ok', 'data' => explode(',', getallheaders()['Accept-Language'])]);
});

Flight::route('GET /api/status', function() {
  global $prefix;
  $conn = Flight::db();
  $sql = "SELECT status, round, time, lobby, delay FROM `$prefix-params` WHERE id=1";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } elseif ($data->rowCount() == 0) {
    Flight::json(['status' => 'error', 'error' => 'not_found']);
  } else {
    Flight::json(['status' => 'ok','data' => $data->fetch()]);
  }
});

function isForbidden2($params) {
  global $secret_token;
  if (!isset($params['payload']) || $params['payload'] !== $secret_token) {
    return true;
  }
  return false;
}

function isForbidden($params) {
  global $secret_token;
  if (!isset($params['payload']) || $params['payload'] !== $secret_token) {
    Flight::json(['status' => 'error', 'error' => 'forbidden']);
    return true;
  }
  return false;
}


function saveReport($who, $message) {
  global $prefix;
  $conn = Flight::db();
  $who = quote($who);
  $message = $conn->quote($message);
  $conn->query("INSERT INTO `$prefix-reports`(who, message) VALUES($who, $message)");
}

function append($arrayFrom, $index, $arrayTo) {
  if (isset($arrayFrom[$index])) {
    $value = $arrayFrom[$index];
    $arrayTo[] = "$index=$value";
  }
  return $arrayTo;
}

function appendQuoted($arrayFrom, $index, $arrayTo) {
  if (isset($arrayFrom[$index])) {
    $arrayTo[] = "$index=".quote($arrayFrom[$index]);
  }
  return $arrayTo;
}

Flight::route('POST /api/status', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);

  if (isForbidden($params)) return;

  $updates = [];
  $updates = appendQuoted($params, 'status', $updates);
  $updates = appendQuoted($params, 'lobby', $updates);
  $updates = append($params, 'time', $updates);
  $updates = append($params, 'round', $updates);
  $updates = append($params, 'delay', $updates);
  $set = implode($updates, ',');
  $sql = "UPDATE `$prefix-params` SET $set WHERE id=1";
  $data = Flight::db()->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('GET /api/registrations', function() {
  global $prefix;
  $conn = Flight::db();
  $sql = "SELECT name, discordName, discriminator FROM `$prefix-registrations` WHERE anonymous=0";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $count = $conn->query("SELECT COUNT(*) as count FROM `$prefix-registrations`");
    if (!$count) {
      Flight::json(['status' => 'error', 'error' => 'query_failed']);
    } else {
      $names = array_map(function($item) { return [$item->name, ($item->discordName != null && $item->discriminator != null ? 1 : 0)]; }, $data->fetchAll());
      Flight::json(['status' => 'ok','data' => ['count' => $count->fetch()->count, 'names' => $names]]);
    }
  }
});

Flight::route('GET /api/confirmed', function() {
  global $prefix;
  $conn = Flight::db();
  $sql = "SELECT name FROM `$prefix-registrations` WHERE confirmed=1 AND disqual=0";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $names = array_map(function($item) { return $item->name; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $names]);
  }
});

Flight::route('POST /api/confirmed', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $conn = Flight::db();
  $sql = "SELECT id, name FROM `$prefix-registrations` WHERE confirmed=1 AND disqual=0";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $names = array_map(function($item) { return ['id' => $item->$id, 'name' => $item->name]; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $names]);
  }
});

Flight::route('GET /api/unconfirmed', function() {
  global $prefix;
  $conn = Flight::db();
  $sql = "SELECT name FROM `$prefix-registrations` WHERE confirmed=0 AND disqual=0";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $names = array_map(function($item) { return $item->name; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $names]);
  }
});


Flight::route('POST /api/unconfirmed', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $conn = Flight::db();
  $sql = "SELECT id, name FROM `$prefix-registrations` WHERE confirmed=0 AND disqual=0";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $names = array_map(function($item) { return ['id' => $item->$id, 'name' => $item->name]; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $names]);
  }
});


Flight::route('POST /api/wish', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $who = $params['who'];
  $withWhom = $params['withWhom'];
  $conn = Flight::db();
  $sql = "INSERT INTO `$prefix-wish`(who, withWhom) VALUES ($who, $withWhom)";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/remove_wishes', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $conn = Flight::db();
  $sql = "UPDATE `$prefix-wish` SET done=1";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/initial_state', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $conn = Flight::db();
  $playersData = $conn->query("SELECT `$prefix-registrations`.id as id, name, discordName, discriminator, offline, SUM(place) as placeSum FROM `$prefix-registrations` LEFT JOIN `$prefix-results` ON `$prefix-registrations`.id=`$prefix-results`.player_id WHERE confirmed=1 GROUP BY id");
  $gamesData = $conn->query("SELECT id, round, board, player_id FROM `$prefix-results`");
  $wishData = $conn->query("SELECT id, who, withWhom, done FROM `$prefix-wish`");
  $status = $conn->query("SELECT status, round, time, lobby, delay FROM `$prefix-params` WHERE id=1");
  $force = $conn->query("SELECT names FROM `$prefix-forceseats`");

  if (!$playersData || !$gamesData || !$wishData || !$status || !$force) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $forceSeats = [];
    foreach ($force->fetchAll() as $item) {
      $forceSeats[] = $item->names;
    }
    Flight::json(['status' => 'ok','data' => ['status' => $status->fetch(), 'players' => $playersData->fetchAll(), 'games' => $gamesData->fetchAll(), 'wish' => $wishData->fetchAll(), 'force' => $forceSeats]]);
  }
});

Flight::route('POST /api/unstarted', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $conn = Flight::db();
  $data = $conn->query("SELECT id, round, board, player_id FROM `$prefix-results` WHERE start_points=NULL");

  if (!$playersData) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok','data' => $data->fetchAll()]);
  }
});

Flight::route('POST /api/players', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $conn = Flight::db();
  $data = $conn->query("SELECT id, name FROM `$prefix-registrations`");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok','data' => $data->fetchAll()]);
  }
});

Flight::route('POST /api/autodisqual', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $conn = Flight::db();
  $data = $conn->query("UPDATE `$prefix-registrations` SET disqual=1 WHERE confirmed=0");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/autodisqualpending', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $conn = Flight::db();
  $round = $params['data'];
  $data = $conn->query("UPDATE `$prefix-registrations` SET disqual=1 WHERE confirmed=0; UPDATE `$prefix-results` SET player_id=0 WHERE round=$round AND player_id IN (SELECT id FROM `$prefix-registrations` WHERE disqual=1);");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/autounconfirm', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $conn = Flight::db();
  $data = $conn->query("UPDATE `$prefix-registrations` SET confirmed=0");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('GET /api/results', function() {
  global $prefix;
  $conn = Flight::db();
  $sql = "SELECT round, board, `$prefix-registrations`.name, start_points, end_points FROM `$prefix-results` LEFT JOIN `$prefix-registrations` ON `$prefix-registrations`.id=`$prefix-results`.player_id ORDER BY `$prefix-results`.id ASC";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $results = array_map(function($item) { return [$item->round, $item->board, $item->name, $item->start_points, $item->end_points]; }, $data->fetchAll());

    $sql = "SELECT round, board, url FROM `$prefix-replays`";
    $data = $conn->query($sql);

    if (!$data) {
	  saveReport("SERVER", $sql);
      Flight::json(['status' => 'error', 'error' => 'query_failed']);
    } else {
      $replays = [];
      foreach ($data->fetchAll() as $item) {
        $replays[$item->round][$item->board] = $item->url;
      }
      //array_map(function($item) { return [$item->round, $item->board, $item->url]; }, $data->fetchAll());
      Flight::json(['status' => 'ok','data' => ['results' => $results, 'replays' => $replays]]);
    }
    //Flight::json(['status' => 'ok','data' => $results]);
  }
});

Flight::route('GET /api/results/@round:[0-9]+', function($round) {
  global $prefix;
  $conn = Flight::db();
  $sql = "SELECT `$prefix-registrations`.name, start_points, end_points FROM `$prefix-results` LEFT JOIN `$prefix-registrations` ON `$prefix-registrations`.id=`$prefix-results`.player_id WHERE round=$round ORDER BY `$prefix-results`.id ASC";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $results = array_map(function($item) { return [$item->name, $item->start_points, $item->end_points]; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $results]);
  }
});

Flight::route('POST /api/confirm', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $data = $params['data']; // array of names
  $size = count($data);
  if ($size) {

    $values = array();
    for ($i = 0; $i < $size; $i++) { // game entities
      $name = quote($data[$i]);
      $values[] = "name=$name";
    }

    $conn = Flight::db();
    $sql = "UPDATE `$prefix-registrations` SET confirmed=1 WHERE ".implode(' OR ', $values).";";
    $data = $conn->query($sql);

    if (!$data) {
	saveReport("SERVER", $sql);
      Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
    } else {
      Flight::json(['status' => 'ok']);
    }
  } else {
      Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/unconfirm', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $data = $params['data']; // array of names
  $size = count($data);
  $values = array();
  for ($i = 0; $i < $size; $i++) { // game entities
    $name = quote($data[$i]);
    $values[] = "name=$name";
  }

  $conn = Flight::db();
  $sql = "UPDATE `$prefix-registrations` SET confirmed=0 WHERE ".implode(' OR ', $values);
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/start', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $data = $params['data']; // array of game entities
  $size = count($data);
  $values = array();
  for ($i = 0; $i < $size; $i++) { // game entities
    $entity = $data[$i];
    $round = $entity['round'];
    $board = $entity['board'];
    $playerId = $entity['player_id'];
    $startPoints = isset($entity['start_points']) ? $entity['start_points'] : 'NULL';
    $values[] = "($round, $board, $playerId, $startPoints)";
  }

  $conn = Flight::db();
  $sql = "UPDATE `$prefix-registrations` SET disqual=1 WHERE confirmed=0; INSERT INTO `$prefix-results`(round, board, player_id, start_points) VALUES ".implode(',', $values).";";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
  } else {
    Flight::json(['status' => 'ok', 'sql' => $sql]);
  }
});

Flight::route('POST /api/start_last', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $data = $params['data']; // array of game entities
  $size = count($data);
  $queryPart = array();
  $confirmPart = array();
  for ($i = 0; $i < $size; $i++) { // game entities
    $entity = $data[$i];
    $round = $entity['round'];
    $board = $entity['board'];
    $playerId = $entity['player_id'];
    $startPoints = isset($entity['start_points']) ? $entity['start_points'] : 'NULL';
    $queryPart[] = "UPDATE `$prefix-results` SET start_points=$startPoints WHERE round=$round AND player_id=$playerId";
    $confirmPart[] = "id=$playerId";
  }

  $conn = Flight::db();
  $sql = "UPDATE `$prefix-registrations` SET confirmed=1 WHERE ".implode(" OR ", $confirmPart).";".implode('; ', $queryPart).";";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/result', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $data = $params['data']; // array of game entities
  $size = count($data);
  $queryPart = array();
  for ($i = 0; $i < $size; $i++) { // game entities
    $entity = $data[$i];
    $round = $entity['round'];
    $playerId = $entity['player_id'];
    $endPoints = isset($entity['end_points']) ? $entity['end_points'] : 'NULL';
    $place = isset($entity['place']) ? $entity['place'] : 'NULL';
    $queryPart[] = "UPDATE `$prefix-results` SET end_points=$endPoints,place=$place WHERE round=$round AND player_id=$playerId";
  }

  $conn = Flight::db();
  $sql = implode('; ', $queryPart).";";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('GET /api/totals', function() {
  global $prefix;
  $conn = Flight::db();
  $sql = "SELECT name, SUM(end_points) as score, AVG(place) as place FROM `$prefix-registrations` LEFT JOIN `$prefix-results` ON `$prefix-registrations`.id=`$prefix-results`.player_id WHERE disqual=0 GROUP BY `$prefix-registrations`.id ORDER BY score DESC";
  $data = $conn->query($sql);
  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $results = array_map(function($item) { return [$item->name, $item->score, $item->place]; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $results]);
  }
});

Flight::route('POST /api/apply', function() {
  global $prefix;
  $conn = Flight::db();

  $params = json_decode(file_get_contents("php://input"), true);
  //if (isForbidden($params)) return;

  $name = quote($params['name']);
  $contacts = quote($params['contacts']);
  $notify = $params['notify'];
  $anonymous = $params['anonymous'];
  $news = $params['news'];
  $discordName = quote($params['discordName']);
  $discriminator = quote($params['discriminator']);
  $offline = quote($params['offline']);
  $lang = quote(getallheaders()['Accept-Language']);
  $sql = "INSERT INTO `$prefix-registrations`(name, contacts, notify, anonymous, discordName, discriminator, offline, news, lang) VALUES($name, $contacts, $notify, $anonymous, $discordName, $discriminator, $offline, $news, $lang)";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed', 'data' => $data]);
  } else {
    Flight::json(['status' => 'ok', 'data' => intval($conn->lastInsertId())]);
  }
});


Flight::route('POST /api/report', function() {

  $params = json_decode(file_get_contents("php://input"), true);

  $who = $params['name'];
  $message = $params['message'];

  $data = saveReport($who, $message);

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok', 'data' => intval($conn->lastInsertId())]);
  }
});

Flight::route('POST /api/replay', function() {
  global $prefix;
  $conn = Flight::db();

  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden2($params)) {
    $url = quote($params['url']);
    $cheat = $params['cheat'];

    $data = $conn->query("INSERT INTO `$prefix-new_replays`(url, cheat) VALUES($url, $cheat)");

    if (!$data) {
      Flight::json(['status' => 'error', 'error' => 'query_failed']);
    } else {
      Flight::json(['status' => 'ok']);
    }
  } else {
    $url = quote($params['url']);
    $round = $params['round'];
    $board = $params['board'];

    $data = Flight::db()->query("INSERT INTO `$prefix-replays`(round,board,url) VALUES ($round,$board,$url)");

    if (!$data) {
      Flight::json(['status' => 'error', 'error' => 'query_failed']);
    } else {
      Flight::json(['status' => 'ok']);
    }
  }
});

Flight::route('GET /api/replays', function() {
  global $prefix;
  $conn = Flight::db();
  $sql = "SELECT url FROM `$prefix-replays`";
  $data = $conn->query($sql);

  if (!$data) {
	saveReport("SERVER", $sql);
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $results = array_map(function($item) { return $item->url; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $results]);
  }
});

Flight::route('POST /api/new_replays', function() {
  global $prefix;
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $conn = Flight::db();
  $data = $conn->query("SELECT * FROM `$prefix-new_replays` WHERE done=0");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $conn->query("UPDATE `$prefix-new_replays` SET done=1");
    Flight::json(['status' => 'ok', 'data' => $data->fetchAll()]);
  }
});

Flight::route('GET /api/cheat_replays_forbidden', function() {
  global $prefix;
  $conn = Flight::db();

  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $who = quote($params['name']);
  $message = quote($params['message']);

  $data = $conn->query("SELECT * FROM `$prefix-new_replays` WHERE cheat=1");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok', 'data' => $data]);
  }
});

Flight::route('POST /api/reset', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  global $reset_sql;

  $conn = Flight::db();
  $data = $conn->query($reset_sql);

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/sql', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $conn = Flight::db();
  $data = $conn->query($params['data']);

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok', 'data' => $data, 'fetch' => $data->fetchAll()]);
  }
});


Flight::start();
?>
