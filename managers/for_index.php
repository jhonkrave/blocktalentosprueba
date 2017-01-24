<?php
require('query.php');

if(isset($_POST['cohorte'])){
    $result = new stdClass();
    $result->cohorts = getConcurrentCohortsSPP();
    $result->enfasis = getConcurrentEnfasisSPP();
    echo json_encode($result);
}else{
    echo 'no';
}
?>