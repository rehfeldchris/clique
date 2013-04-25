<?php

require_once 'datasource.php';
require_once 'BronKerbosch.php';

//pivot selection functions
$randomVert = function($vertices) {
    return array_rand($vertices);
};
$firstVert = function($vertices) {
    return current($vertices);
};
$lastVert = function($vertices) {
    return end($vertices);
};
$midVert = function($vertices) {
    $verts = array_keys($vertices);
    return $verts[ floor(count($verts) / 2) ];
};


$s = memory_get_usage();
$list = getAdjacencyList();
$verts = array_combine(array_keys($list), array_keys($list));
$cliques = [];
//bronKerbosch([], $verts, [], $list);
//bronKerboschWithPivoting([], $verts, [], $list, $firstVert);
$ts = microtime(true);
bronKerboschWithVertexOrdering(array(), $verts, array(), $list, $lastVert);
$te = microtime(true);
$e = memory_get_peak_usage();
$mem = $e - $s;


printf("peak memory %.4f MB\n", ($mem/1024)/1024);
printf("algo time %.4f seconds (doesnt include data structure creation time)\n", ($te - $ts));
//print_r($cliques);
