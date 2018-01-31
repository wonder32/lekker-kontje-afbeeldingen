<?php
/*-------------------------/
*
*      Redirects
*
*-------------------------*/

function add_query_vars($aVars) {
    array_push($aVars, 'img_group', 'img_tag');
    //$aVars[] = "img_group"; // represents the name of the product category as shown in the URL
    return $aVars;
}

// hook add_query_vars function into query_vars
add_filter('query_vars', 'add_query_vars');


function add_rewrite_rules($aRules) {
$aNewRules1 = array('kontjes/([^/]+)/([^/]+)/?$' => 'index.php?pagename=kontjes&img_group=$matches[1]&img_tag=$matches[2]');
$aNewRules2 = array('kontjes/([^/]+)/?$' => 'index.php?pagename=kontjes&img_group=$matches[1]');

$aRules = $aNewRules1 + $aNewRules2 + $aRules;
return $aRules;
}

// hook add_rewrite_rules function into rewrite_rules_array
add_filter('rewrite_rules_array', 'add_rewrite_rules');