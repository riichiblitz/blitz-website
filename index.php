<?php

header('Access-Control-Allow-Origin: *');

require_once 'flight/Flight.php';

$dbhost = getenv(strtoupper(getenv("OPENSHIFT_MYSQL_SERVICE_NAME"))."_SERVICE_HOST");
$dbport = getenv(strtoupper(getenv("OPENSHIFT_MYSQL_SERVICE_NAME"))."_SERVICE_PORT");
$dbusername = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
$dbpassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
$db_name = getenv('OPENSHIFT_GEAR_NAME');
$secret_token = getenv('OPENSHIFT_SECRET_TOKEN');

$player_per_table = 4;
$max_rounds = 4;

$reset_sql = "
DROP TABLE `params` ;
DROP TABLE `forceseats` ;
DROP TABLE `new_replays` ;
DROP TABLE `registrations` ;
DROP TABLE `replays` ;
DROP TABLE `reports` ;
DROP TABLE `results` ;
DROP TABLE `wish` ;
CREATE TABLE IF NOT EXISTS `params` ( `id` int(11) NOT NULL AUTO_INCREMENT, `status` varchar(15) NOT NULL, `round` int(1) NOT NULL, `time` bigint(13) NOT NULL, `lobby` varchar(10) NOT NULL, `delay` int(10) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
DELETE FROM `params` WHERE `id`=1 ;
INSERT INTO `params` (`id`, `status`, `round`, `time`, `lobby`, `delay`) VALUES (1, 'announce', 0, 0, '—', 600000);
CREATE TABLE IF NOT EXISTS `forceseats` ( `id` int(11) NOT NULL AUTO_INCREMENT, `names` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `new_replays` ( `id` int(11) NOT NULL AUTO_INCREMENT, `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, `cheat` int(1) NOT NULL DEFAULT '0', `done` int(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;
CREATE TABLE IF NOT EXISTS `registrations` ( `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(10) NOT NULL, `contacts` varchar(300) DEFAULT NULL, `comment` varchar(200) DEFAULT NULL, `anonymous` int(1) NOT NULL, `notify` int(1) NOT NULL, `news` int(1) NOT NULL, `lang` varchar(20) NOT NULL, `discordName` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL, `discriminator` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL, `offline` varchar(250) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL, `confirmed` int(1) NOT NULL DEFAULT '0', `disqual` int(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=78 ;
CREATE TABLE IF NOT EXISTS `replays` ( `id` int(11) NOT NULL AUTO_INCREMENT, `round` int(1) NOT NULL, `board` int(2) NOT NULL, `url` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `round` (`round`,`board`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;
CREATE TABLE IF NOT EXISTS `reports` ( `id` int(11) NOT NULL AUTO_INCREMENT, `who` varchar(20) NOT NULL, `message` varchar(500) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;
CREATE TABLE IF NOT EXISTS `results` ( `id` int(11) NOT NULL AUTO_INCREMENT, `round` int(1) NOT NULL, `board` int(2) NOT NULL, `player_id` int(3) NOT NULL, `start_points` int(5) DEFAULT NULL, `end_points` int(6) DEFAULT NULL, `place` int(1) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=201 ;
CREATE TABLE IF NOT EXISTS `wish` ( `id` int(11) NOT NULL AUTO_INCREMENT, `who` int(11) NOT NULL, `withWhom` int(11) NOT NULL, `done` int(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
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
  Flight::json(['status' => 'ok', 'data' => getallheaders()]);
});

Flight::route('GET /api/language', function() {
  Flight::json(['status' => 'ok', 'data' => explode(',', getallheaders()['Accept-Language'])]);
});

Flight::route('GET /api/status', function() {
  $conn = Flight::db();
  $data = $conn->query("SELECT status, round, time, lobby, delay FROM params WHERE id=1");

  if (!$data) {
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
  $params = json_decode(file_get_contents("php://input"), true);

  if (isForbidden($params)) return;

  $updates = [];
  $updates = appendQuoted($params, 'status', $updates);
  $updates = appendQuoted($params, 'lobby', $updates);
  $updates = append($params, 'time', $updates);
  $updates = append($params, 'round', $updates);
  $updates = append($params, 'delay', $updates);
  $set = implode($updates, ',');
  $data = Flight::db()->query("UPDATE params SET $set WHERE id=1");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('GET /api/registrations', function() {
  $conn = Flight::db();
  $data = $conn->query("SELECT name, discordName, discriminator FROM registrations WHERE anonymous=0");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $count = $conn->query("SELECT COUNT(*) as count FROM registrations");
    if (!$count) {
      Flight::json(['status' => 'error', 'error' => 'query_failed']);
    } else {
      $names = array_map(function($item) { return [$item->name, ($item->discordName != null && $item->discriminator != null ? 1 : 0)]; }, $data->fetchAll());
      Flight::json(['status' => 'ok','data' => ['count' => $count->fetch()->count, 'names' => $names]]);
    }
  }
});

Flight::route('GET /api/confirmed', function() {
  $conn = Flight::db();
  $data = $conn->query("SELECT name FROM registrations WHERE confirmed=1 AND disqual=0");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $names = array_map(function($item) { return $item->name; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $names]);
  }
});

Flight::route('POST /api/confirmed', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $conn = Flight::db();
  $data = $conn->query("SELECT id, name FROM registrations WHERE confirmed=1 AND disqual=0");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $names = array_map(function($item) { return ['id' => $item->$id, 'name' => $item->name]; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $names]);
  }
});

Flight::route('GET /api/unconfirmed', function() {
  $conn = Flight::db();
  $data = $conn->query("SELECT name FROM registrations WHERE confirmed=0 AND disqual=0");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $names = array_map(function($item) { return $item->name; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $names]);
  }
});


Flight::route('POST /api/unconfirmed', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $conn = Flight::db();
  $data = $conn->query("SELECT id, name FROM registrations WHERE confirmed=0 AND disqual=0");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $names = array_map(function($item) { return ['id' => $item->$id, 'name' => $item->name]; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $names]);
  }
});


Flight::route('POST /api/wish', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $who = $params['who'];
  $withWhom = $params['withWhom'];
  $conn = Flight::db();
  $data = $conn->query("INSERT INTO wish(who, withWhom) VALUES ($who, $withWhom)");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/remove_wishes', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $conn = Flight::db();
  $data = $conn->query("UPDATE wish SET done=1");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/initial_state', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $conn = Flight::db();
  $playersData = $conn->query("SELECT registrations.id as id, name, discordName, discriminator, offline, SUM(place) as placeSum FROM registrations LEFT JOIN results ON registrations.id=results.player_id WHERE confirmed=1 GROUP BY id");
  $gamesData = $conn->query("SELECT id, round, board, player_id FROM results");
  $wishData = $conn->query("SELECT id, who, withWhom, done FROM wish");
  $status = $conn->query("SELECT status, round, time, lobby, delay FROM params WHERE id=1");
  $force = $conn->query("SELECT names FROM forceseats");

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
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;
  $conn = Flight::db();
  $data = $conn->query("SELECT id, round, board, player_id FROM results WHERE start_points=NULL");

  if (!$playersData) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok','data' => $data->fetchAll()]);
  }
});

Flight::route('POST /api/players', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $conn = Flight::db();
  $data = $conn->query("SELECT id, name FROM registrations");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok','data' => $data->fetchAll()]);
  }
});

Flight::route('POST /api/autodisqual', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $conn = Flight::db();
  $data = $conn->query("UPDATE registrations SET disqual=1 WHERE confirmed=0");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/autodisqualpending', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $conn = Flight::db();
  $round = $params['data'];
  $data = $conn->query("UPDATE registrations SET disqual=1 WHERE confirmed=0; UPDATE results SET player_id=0 WHERE round=$round AND player_id IN (SELECT id FROM registrations WHERE disqual=1);");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/autounconfirm', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $conn = Flight::db();
  $data = $conn->query("UPDATE registrations SET confirmed=0");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('GET /api/results', function() {
  $conn = Flight::db();
  $data = $conn->query("SELECT round, board, registrations.name, start_points, end_points FROM results LEFT JOIN registrations ON registrations.id=results.player_id ORDER BY results.id ASC");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $results = array_map(function($item) { return [$item->round, $item->board, $item->name, $item->start_points, $item->end_points]; }, $data->fetchAll());

    $data = $conn->query("SELECT round, board, url FROM replays");

    if (!$data) {
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
  $conn = Flight::db();
  $data = $conn->query("SELECT registrations.name, start_points, end_points FROM results LEFT JOIN registrations ON registrations.id=results.player_id WHERE round=$round ORDER BY results.id ASC");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $results = array_map(function($item) { return [$item->name, $item->start_points, $item->end_points]; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $results]);
  }
});

// Flight::route('POST /api/result', function() {
//   $params = json_decode(file_get_contents("php://input"), true);
//   if (isForbidden($params)) return;
//   $round = $params['round'];
//   $board = $params['board'];
//   $player = quote($params['player']);
//   $score = $params['score'];
//   $points = $params['points'];
//   $place = $params['place'];
//
//   $conn = Flight::db();
//   $sql = "UPDATE results SET score=$score,points=$points,place=$place WHERE round=$round AND board=$board AND player=$player";
//   $data = $conn->query($sql);
//
//   if (!$data) {
//     Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
//   } else {
//     Flight::json(['status' => 'ok']);
//   }
// });

// Flight::route('POST /api/results', function() {
//   $params = json_decode(file_get_contents("php://input"), true);
//   if (isForbidden($params)) return;
//   $results = $params['data'];
//
//   $conn = Flight::db();
//
//   $data = $conn->query("SELECT round FROM params WHERE id=0");
//   if (!$data) {
//     Flight::json(['status' => 'error', 'error' => 'query_failed']);
//   } else {
//     $round = $data->fetch()->round;
//     $sql = "";
//     for ($i = 0; $i < count($results); $i++) {
//       $name = $results[$i]['name'];
//       $score = $results[$i]['score'];
//       $place = $results[$i]['place'];
//       $points = $results[$i]['points'];
//       $sql = $sql."UPDATE results SET state='idle',score=$score,points=$points,place=$place WHERE round=$round AND player='$name';";
//     }
//
//     $data = $conn->query($sql);
//     if (!$data) {
//       Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
//     } else {
//       Flight::json(['status' => 'ok']);
//     }
//   }
//
//
//   if (!$data) {
//     Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
//   } else {
//     Flight::json(['status' => 'ok']);
//   }
// });

Flight::route('POST /api/confirm', function() {
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
    $sql = "UPDATE registrations SET confirmed=1 WHERE ".implode(' OR ', $values).";";
    $data = $conn->query($sql);

    if (!$data) {
      Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
    } else {
      Flight::json(['status' => 'ok']);
    }
  } else {
      Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/unconfirm', function() {
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
  $sql = "UPDATE registrations SET confirmed=0 WHERE ".implode(' OR ', $values);
  $data = $conn->query($sql);

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/start', function() {
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
  $sql = "UPDATE registrations SET disqual=1 WHERE confirmed=0; INSERT INTO results(round, board, player_id, start_points) VALUES ".implode(',', $values);
  $data = $conn->query($sql);

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/start_last', function() {
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
    $queryPart[] = "UPDATE results SET start_points=$startPoints WHERE round=$round AND player_id=$playerId";
    $confirmPart[] = "id=$playerId";
  }

  $conn = Flight::db();
  $sql = "UPDATE registrations SET confirmed=1 WHERE ".implode(" OR ", $confirmPart).";".implode('; ', $queryPart).";";
  $data = $conn->query($sql);

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('POST /api/result', function() {
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
    $queryPart[] = "UPDATE results SET end_points=$endPoints,place=$place WHERE round=$round AND player_id=$playerId";
  }

  $conn = Flight::db();
  $sql = implode('; ', $queryPart).";";
  $data = $conn->query($sql);

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed', 'query' => $sql]);
  } else {
    Flight::json(['status' => 'ok']);
  }
});

Flight::route('GET /api/totals', function() {
  $conn = Flight::db();
  $data = $conn->query("SELECT name, SUM(end_points) as score, AVG(place) as place FROM registrations LEFT JOIN results ON registrations.id=results.player_id WHERE disqual=0 GROUP BY registrations.id ORDER BY score DESC");
  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $results = array_map(function($item) { return [$item->name, $item->score, $item->place]; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $results]);
  }
});

Flight::route('POST /api/apply', function() {
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
  $data = $conn->query("INSERT INTO registrations(name, contacts, notify, anonymous, discordName, discriminator, offline, news, lang) VALUES($name, $contacts, $notify, $anonymous, $discordName, $discriminator, $offline, $news, $lang)");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok', 'data' => intval($conn->lastInsertId())]);
  }
});

Flight::route('POST /api/report', function() {
  $conn = Flight::db();

  $params = json_decode(file_get_contents("php://input"), true);

  $who = quote($params['name']);
  $message = quote($params['message']);

  $data = $conn->query("INSERT INTO reports(who, message) VALUES($who, $message)");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    Flight::json(['status' => 'ok', 'data' => intval($conn->lastInsertId())]);
  }
});

Flight::route('POST /api/replay', function() {
  $conn = Flight::db();

  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden2($params)) {
    $url = quote($params['url']);
    $cheat = $params['cheat'];

    $data = $conn->query("INSERT INTO new_replays(url, cheat) VALUES($url, $cheat)");

    if (!$data) {
      Flight::json(['status' => 'error', 'error' => 'query_failed']);
    } else {
      Flight::json(['status' => 'ok']);
    }
  } else {
    $url = quote($params['url']);
    $round = $params['round'];
    $board = $params['board'];

    $data = Flight::db()->query("INSERT INTO replays(round,board,url) VALUES ($round,$board,$url)");

    if (!$data) {
      Flight::json(['status' => 'error', 'error' => 'query_failed']);
    } else {
      Flight::json(['status' => 'ok']);
    }
  }
});

Flight::route('GET /api/replays', function() {
  $conn = Flight::db();
  $data = $conn->query("SELECT url FROM replays");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $results = array_map(function($item) { return $item->url; }, $data->fetchAll());
    Flight::json(['status' => 'ok','data' => $results]);
  }
});

Flight::route('POST /api/new_replays', function() {
  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $conn = Flight::db();
  $data = $conn->query("SELECT * FROM new_replays WHERE done=0");

  if (!$data) {
    Flight::json(['status' => 'error', 'error' => 'query_failed']);
  } else {
    $conn->query("UPDATE new_replays SET done=1");
    Flight::json(['status' => 'ok', 'data' => $data->fetchAll()]);
  }
});

Flight::route('GET /api/cheat_replays_forbidden', function() {
  $conn = Flight::db();

  $params = json_decode(file_get_contents("php://input"), true);
  if (isForbidden($params)) return;

  $who = quote($params['name']);
  $message = quote($params['message']);

  $data = $conn->query("SELECT * FROM new_replays WHERE cheat=1");

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


Flight::start();
?>
