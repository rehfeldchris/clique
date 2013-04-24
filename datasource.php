<?php
    /**
     * return format like 
     * $adjacencyList = [
     *     2345 => [111 => 111, 368 => 368, 44 => 44]
     *   , 2346 => [1141 => 1141, 3685 => 3685, 445 => 445]
     *   , 44 => [2345]
     * ];
     */
function getAdjacencyList() {
    $file = 'C:\ws\apache\htdocs\wowarmory\script\clique.sqlite';
    try {
        $dbh = new PDO("sqlite:$file");
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "cant connect to db.\n";
        echo $e->getMessage();
        exit;
    }
    
    $sql = "select node_a, node_b from edges limit 100000"; 

    $adjacencyList = array();
    foreach ($dbh->query($sql) as $row) {
        $adjacencyList[(int) $row['node_a']][(int) $row['node_b']] = (int) $row['node_b'];
        $adjacencyList[(int) $row['node_b']][(int) $row['node_a']] = (int) $row['node_a'];
    }
    
    $sql = "select node_a, node_b from edges where node_a in (295228, 112935, 140040, 292052, 364134) or node_b in (295228, 112935, 140040, 292052, 364134)";
    foreach ($dbh->query($sql) as $row) {
        $adjacencyList[(int) $row['node_a']][(int) $row['node_b']] = (int) $row['node_b'];
        $adjacencyList[(int) $row['node_b']][(int) $row['node_a']] = (int) $row['node_a'];
    }
    
    echo max(array_map('count', $adjacencyList));

    return $adjacencyList;
}