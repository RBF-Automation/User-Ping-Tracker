<?php

include_once 'LocalizedTimeStamp.php';

class Data {
    
    private $rawData;
    private $dayFrameData;
    private $normalizedData;
    
    function __construct($data) {
        $this->rawData = $data;
    }
    
    function normalize() {
        $dayFramedata = array();
        
        $TIME = 3;
        $VAL = 2;
        
        $dayIndex = 0;
        $lastDay = "";
        foreach ($this->rawData as $point) {
            $day = date('N', $point[$TIME]);
            if ($lastDay > $day) {
                $dayIndex++;
            }
            $time = new LocalizedTimeStamp($point[$TIME]);
            $this->dayFrameData[$dayIndex][] = array($time, $point[$VAL]);
            $lastDay = $day;
        }
        
    }
    
    function average() {
        
        $pointTracker = array();
        
        $prevlast;
        for ($i = 0; $i < sizeof($this->dayFrameData); $i++) {
            if ($i == 0) {
                $pointTracker[] = null;
            } else {
                $pointTracker[] = $prevlast;
            }
            $prevlast = $this->dayFrameData[$i][sizeof($this->dayFrameData[$i]) - 1][1];
        }
        
        $maxLen = 0;
        foreach ($this->dayFrameData as $dayFrame) {
            $maxLen = $maxLen < sizeof($dayFrame) ? sizeof($dayFrame) : $maxLen;
        }
        
        
        $done = false;
        $normalData = array();
        $dayFrameData = $this->dayFrameData;
        
        $allDates = array();
        
        while (!$done) {
            
            $current = null;
            $trackKey = null;
            for ($i = 0; $i < sizeof($dayFrameData); $i++) {
                if ($current == null) {
                    if (isset($dayFrameData[$i][0])) {
                        $current = $dayFrameData[$i][0];
                        $trackKey = $i;
                    }
                } else {
                    if (isset($dayFrameData[$i][0]) && $dayFrameData[$i][0][0]->isLessThan($current[0])) {
                        $current = $dayFrameData[$i][0];
                        $trackKey = $i;
                    }
                }
            }
            
            unset($dayFrameData[$trackKey][0]);
            $dayFrameData[$trackKey] = array_values($dayFrameData[$trackKey]);
            
            if (!in_array($current[0]->getString(), $allDates)) {
                $allDates[] = $current[0]->getString(); 
            }
            
            if (!isset($pointTracker[$trackKey]) || $pointTracker[$trackKey] != $current[1]) {
                $pointTracker[$trackKey] = $current[1];
            }
            
            for ($i = 0; $i < sizeof($pointTracker); $i++) {
                if ($pointTracker[$i] != null) {
                    $normalData[$i][$current[0]->getString()] = $pointTracker[$i];
                }
            }
            
            $done = true;
            for ($i = 0; $i < sizeof($dayFrameData); $i++) {
                if (sizeof($dayFrameData[$i]) != 0) {
                    $done = false;
                    break;
                }
            }
            
        }
        
        $average = array();
        
        foreach ($allDates as $date) {
            $sum = 0;
            $avgCount = 0;
            foreach ($normalData as $dayFrame) {
                if (isset($dayFrame[$date])) {
                    $sum += $dayFrame[$date];
                    $avgCount++;
                }
            }
            $average[$date] = $sum/$avgCount;
        }
        
        return $average;
        
        
    }
    
}

?>
