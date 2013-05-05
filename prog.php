<?php

ini_set('memory_limit', '512M');

require_once 'datasource.php';
require_once 'verifiedMaxCliques.php';
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










$edgeLimit = 4000000;
gc_disable();

$s = memory_get_usage();
echo "loading data from database...";
$list = getAdjacencyList($edgeLimit);
echo "done\n\n";
$verts = array_combine(array_keys($list), array_keys($list));



$numNodes = count($list);
$numEdges = array_sum(array_map('count', $list));

//test bronKerbosch with pivot
printf("\nTesting bronKerbosch with pivoting algo on %s nodes and %s edges...\n", number_format($numNodes), number_format($numEdges));
echo "This may take a while. Consider a cup of tea.\n";
$cliques = array();
$ts = microtime(true);
bronKerboschWithPivoting(array(), $verts, array(), $list, $cliques, $lastVert);
$te = microtime(true);
printf("  algo execution time %.2f seconds\n", ($te - $ts));
printf("  num max cliques found %d\n", count($cliques));
printf("  contains all verified max cliques? %s\n", containsVerifiedMaxCliques($cliques) ? 'true' : 'false');




printf("\nTesting bronKerbosch with pivoting + vertex ordering algo on %s nodes and %s edges...\n", number_format($numNodes), number_format($numEdges));
echo "This may take a while. Consider another cup of tea.\n";
$cliques = array();
$ts = microtime(true);
bronKerboschWithVertexOrdering(array(), $verts, array(), $list, $cliques, $lastVert);
$te = microtime(true);


printf("  algo execution time %.2f seconds\n", ($te - $ts));
printf("  num max cliques found %d\n", count($cliques));
printf("  contains all verified max cliques? %s\n", containsVerifiedMaxCliques($cliques) ? 'true' : 'false');

//print_r($cliques);