<?php

include_once 'Data.php';

class FortuneTeller {
    
    private $data;
    
    public function __construct($data) {
        $this->data = $data;
    }
    
    public function timeToEvent($startTime, $isHome, $isHomeThreshold) {
        $data = $this->data->getPeakAverage();
        
        //$checkTime = LocalizedTimeStamp::fromUnix($startTime);
        $checkTime = $startTime->intVal();
        
        $nextTime = 0;
        $endTime = 0;
        foreach ($data as $time => $value) {
            if ($time >= $checkTime && self::compare($isHome, $isHomeThreshold, $value)) {
                $nextTime = $time;
                break;
            }
        }
        
        foreach ($data as $time => $value) {
            if ($time > $nextTime && self::compare(!$isHome, $isHomeThreshold, $value)) {
                $endTime = $time;
                break;
            }
        }
        
        
        return array(
            'timeTo' => $nextTime - $checkTime,
            'duration' => $endTime - $nextTime
        );
    }
    
    private static function compare($isHome, $threshold, $val) {
        return $isHome == 1 ? $threshold <= $val : $threshold >= $val;
    }
    
}

?>
