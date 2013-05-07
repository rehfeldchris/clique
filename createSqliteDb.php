<?php
/*
derives node/edge relations from a mysql dataset, and saves them to an sqlite db.
*/


ini_set('memory_limit', '1G');



//mysql server connection settings
$dsn = 'mysql:dbname=wowarmory;host=127.0.0.1;charset=utf8';
$user = 'root';
$password = 'root';

//name of the sqlite db we will create
$file = 't.sqlite';
if (file_exists($file)) {
    unlink($file);
}



//connect to mysql
try {
    $dbh = new PDO($dsn, $user, $password, array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false
	));
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

//open sqlite db, creating it if it doesnt exist
try {
    $sqlite = new PDO("sqlite:$file", null, null, array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	));
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}


//create the table
$sql = "
create table edges (
	node_a integer,
	node_b text key
)
";
$sqlite->exec($sql);


//make prepared statement for inserting into sqlite
$sql = "
insert into edges (node_a, node_b) values (?,?)
";
$insertStmt = $sqlite->prepare($sql);



//query to get the relevant data from mysql
//selects all rows with 2 diff nodes, as long as there is at least 3 diff achievements in common between the 2 nodes
$sql = "
select t1.character_id node_a
	 , t2.character_id node_b
	 , count(*) cnt
  from accountwide_denormalized_character_achievements t1
inner
  join accountwide_denormalized_character_achievements t2
    on t1.character_id != t2.character_id
   and t1.achievement_id = t2.achievement_id
   and t1.achievement_completed_ts = t2.achievement_completed_ts
 where t1.realm_name = 'Arthas'
group by t1.character_id, t2.character_id
having cnt >= 3
";
$stmt = $dbh->prepare($sql);



//insert rows into sqlite
$stmt->execute();
$sqlite->beginTransaction();
foreach ($stmt as $row) {
	$insertStmt->execute([$row['node_a'], $row['node_b']);
}
$sqlite->commit();
$stmt->closeCursor();
