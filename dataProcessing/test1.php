<?php

    include_once 'FortuneTeller.php';
    
    $file = "brian.csv";
    $arr = array_map('str_getcsv', file($file));
    
    $data = new Data($arr, 3, 2);
    $f = new FortuneTeller($data);
    
    //$timeTest = new LocalizedTimeStamp(array(1, 9, 0, .5));
    $timeTest = LocalizedTimeStamp::fromUnix(time());
    
    
    $t = $f->timeToEvent($timeTest, 0, .7);
    
    $time = LocalizedTimeStamp::fromInt($t['timeTo']);
    $range = LocalizedTimeStamp::fromInt($t['duration']);
    
    echo 'Hours: ' . $time->getHours() . " and Mins: " .  $time->getMins() . " for " . 'Hours: ' . $range->getHours() . " and Mins: " . $range->getMins() . "\n";
    
    return;

?>