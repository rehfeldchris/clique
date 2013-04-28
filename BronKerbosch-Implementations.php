<?php



function bronKerbosch($potentialClique, $candidateVerts, $alreadyFoundVerts, $adjacencyList, &$resultCliques) {
    if (!$candidateVerts && !$alreadyFoundVerts) {
        //found maximal clique
        $resultCliques[] = $potentialClique;
        return;
    }
    
    $cliques = array();
    foreach ($candidateVerts as $candidateVert) {
        //get the neighbors of $candidateVert (the verts it has an edge to)
        $neighborVerts = $adjacencyList[$candidateVert];
        $newCandidates = array_intersect_key($candidateVerts, $neighborVerts);
        $newAlreadyFoundVerts = array_intersect_key($alreadyFoundVerts, $neighborVerts);
        
        //temporarily add the new vert to r
        $potentialClique[$candidateVert] = $candidateVert;
        //echo join(',', array_intersect_key($candidateVerts, $neighborVerts)), "\n";
        //exit;
        
        bronKerbosch($potentialClique, $newCandidates, $newAlreadyFoundVerts, $adjacencyList);
        //remove the temp vert from r
        unset($potentialClique[$candidateVert]);
        
        //take the vert our of candidates, and put it into already found(alreadyProcessed sounds like a better name)
        unset($candidateVerts[$candidateVert]);
        $alreadyFoundVerts[$candidateVert] = $candidateVert;
    }
    
    return $cliques;
}



function bronKerboschWithPivoting($potentialClique, $candidateVerts, $alreadyFoundVerts, $adjacencyList, &$resultCliques, $pivotChoosingFunction) {
    //if both sets are empty
    if (!$candidateVerts && !$alreadyFoundVerts) {
        //found maximal clique
        $resultCliques[] = $potentialClique;
        return;
    }
    
    $pivotVert = $pivotChoosingFunction($candidateVerts + $alreadyFoundVerts);
    $pivotNeighborVerts = $adjacencyList[$pivotVert];


    //array_diff_key means set minus or "except the stuff in $pivotNeighborVerts"
    foreach (array_diff_key($candidateVerts, $pivotNeighborVerts) as $candidateVert) {
        
        //get the neighbors of $candidateVert (the verts it has an edge to)
        $neighborVerts = $adjacencyList[$candidateVert];
        
        //temporarily add the new vert to r
        // like R U {v} in psuedo code
        $potentialClique[$candidateVert] = $candidateVert;
        // like P ^ N(v) in psuedocode
        $newCandidates = array_intersect_key($candidateVerts, $neighborVerts);
        // like X ^ N(V) in pesudo
        $newAlreadyFoundVerts = array_intersect_key($alreadyFoundVerts, $neighborVerts);
        

        
        bronKerboschWithPivoting($potentialClique, $newCandidates, $newAlreadyFoundVerts, $adjacencyList, $resultCliques, $pivotChoosingFunction);
        
        //remove the temp vert from r
        unset($potentialClique[$candidateVert]);
        
        //take the vert our of candidates, and put it into already found(alreadyProcessed sounds like a better name)
        //like P := P \ {v} in pseudo
        unset($candidateVerts[$candidateVert]);
        //like X := X U {V} in pseudo
        $alreadyFoundVerts[$candidateVert] = $candidateVert;
    }

}



function bronKerboschWithVertexOrdering($potentialClique, $candidateVerts, $alreadyFoundVerts, $adjacencyList, &$resultCliques, $pivotChoosingFunction) {
    //sort by vertex degree, ascending
    //this is a "degeneracy ordering"
    uasort($candidateVerts, function($a, $b){
        return count($a) - count($b);
    });
    return bronKerboschWithPivoting($potentialClique, $candidateVerts, $alreadyFoundVerts, $adjacencyList, $resultCliques, $pivotChoosingFunction);
}