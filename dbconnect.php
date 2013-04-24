<?php

try {
	$dbh = new PDO('sqlite:./clique.sqlite');
} catch (PDOException $e) {
	echo "cant connect to db.\n";
	echo $e->getMessage();
	exit;
}

