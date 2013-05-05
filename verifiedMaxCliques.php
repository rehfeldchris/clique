<?php


function containsVerifiedMaxCliques($cliques) {

    $verifiedMaxCliques = array();
    $verifiedMaxCliques[] = array(434215, 582470, 582478, 582488);
    $verifiedMaxCliques[] = array(190767, 190770, 190772, 190782, 190793, 190880, 190906, 191014, 191040, 192079);
    
    $numMatches = 0;
    foreach ($cliques as $clique) {
        foreach ($verifiedMaxCliques as $verifiedClique) {
            if (array_diff($clique, $verifiedClique) === array()) {
                $numMatches++;
                echo 1;
            }
        }
    }
    
    return $numMatches === count($verifiedMaxCliques);
    
    
}