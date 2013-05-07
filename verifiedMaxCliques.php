<?php


function containsVerifiedMaxCliques($cliques) {

    $verifiedMaxCliques = array();
    $verifiedMaxCliques[] = array(434215, 582470, 120866, 120864, 582478, 582488);
    $verifiedMaxCliques[] = array(297358, 117037, 179270, 117511, 175780);
    $verifiedMaxCliques[] = array(172315, 172322, 172327, 172316, 172305, 172279, 172282, 172284, 172277, 172280, 172299, 172300, 172301, 172302, 172308);
    
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