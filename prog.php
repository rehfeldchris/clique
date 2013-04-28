<?php

ini_set('memory_limit', '512M');

require_once 'datasource.php';
require_once 'BronKerbosch-Implementations.php';

/**
The following lambda functions serve as pivot selection strategies for 
bronKerboschWithPivoting()
*/
//randomly picks a vert
$randomVert = function($vertices) {
    return array_rand($vertices);
};
//always picks the first vert in the list
$firstVert = function($vertices) {
    return current($vertices);
};
//always picks the last vert in the list
$lastVert = function($vertices) {
    return end($vertices);
};
//always picks the middle vert in the list(biases toward the front when there's an odd number of verts)
$midVert = function($vertices) {
    $verts = array_keys($vertices);
    return $verts[ floor(count($verts) / 2) ];
};











gc_disable();

$s = memory_get_usage();
echo "loading data...\n";
$list = getAdjacencyList();
echo "data loaded\n";
$verts = array_combine(array_keys($list), array_keys($list));


$numNodes = count($list);
$numEdges = array_sum(array_map('count', $list));


//test bronKerbosch with pivot
echo "testing bronKerbosch with pivoting algo on $numNodes nodes and $numEdges edges\n";
$cliques = array();
$ts = microtime(true);
bronKerboschWithPivoting(array(), $verts, array(), $list, $cliques, $randomVert);
$te = microtime(true);
printf("algo execution time %.4f seconds (doesnt include data structure creation time)\n", ($te - $ts));
printf("num max cliques %d\n", count($cliques));




echo "testing bronKerbosch with pivoting + vertex ordering algo on $numNodes nodes and $numEdges edges\n";
$cliques = array();
$ts = microtime(true);
bronKerboschWithVertexOrdering(array(), $verts, array(), $list, $cliques, $randomVert);
$te = microtime(true);
$e = memory_get_peak_usage();
$mem = $e - $s;



printf("peak memory %.4f MB\n", ($mem/1024)/1024);
printf("algo time %.4f seconds (doesnt include data structure creation time)\n", ($te - $ts));
printf("num max cliques %d\n", count($cliques));
//print_r($cliques);
