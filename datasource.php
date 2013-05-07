<?php
    /**
     * return format like 
     * $adjacencyList = [
     *     2345 => [111 => 111, 368 => 368, 44 => 44]
     *   , 2346 => [1141 => 1141, 3685 => 3685, 445 => 445]
     *   , 44 => [2345]
     * ];
     */
function getAdjacencyList($limit) {
    $file = 'edges.sqlite';
    try {
        $dbh = new PDO("sqlite:$file");
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "cant connect to db.\n";
        echo $e->getMessage();
        exit;
    }
    
    $sql = "select node_a, node_b from edges limit $limit"; 

    $adjacencyList = array();
    foreach ($dbh->query($sql) as $row) {
		$node_a = (int) $row['node_a'];
		$node_b = (int) $row['node_b'];
        $adjacencyList[$node_a][$node_b] = $node_b;
        $adjacencyList[$node_b][$node_a] = $node_a;
    }


    return $adjacencyList;
}