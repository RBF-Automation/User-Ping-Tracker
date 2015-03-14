<?php

include_once 'LocalizedTimeStamp.php';

class Data {
    
    private $rawData;
    private $dayFrameData;
    private $allDates;
    private $peakSyncedData;
    private $peakAverage;
    
    private $timeIndex;
    private $valIndex;
    
    public function __construct($data, $timeIndex, $valIndex) {
        $this->rawData = $data;
        $this->timeIndex = $timeIndex;
        $this->valIndex = $valIndex;
    }
    
    public function getRawdata() {
        return $this->rawData;
    }
    
    public function getDayFrameData() {
        if ($this->normalizedData == null) {
            $this->normalizeDayFrame();
        }
        return $this->normalizedData;
    }
    
    public function getPeakSyncdata() {
        if ($this->peakSyncedData == null) {
            $this->peakSync();
        }
        return $this->peakSyncedData;
    }
    
    public function getPeakAverage() {
        if ($this->peakSyncedData == null) {
            $this->peakAverage();
        }
        return $this->peakAverage;
    }
    
    
    /**
     * Breaks data into normalized weeks. 
     */
    public function normalizeDayFrame() {
        $this->dayFramedata = array();
        
        $dayIndex = 0;
        $lastDay = "";
        foreach ($this->rawData as $point) {
            $day = date('N', $point[$this->timeIndex]);
            if ($lastDay > $day) {
                $dayIndex++;
            }
            $time = LocalizedTimeStamp::fromUnix($point[$this->timeIndex]);
            $this->dayFrameData[$dayIndex][] = array($time->intVal(), $point[$this->valIndex]);
            $lastDay = $day;
        }
        
        
        return $this->dayFramedata;
    }
    
    public function peakSync() {
        
        if ($this->dayFrameData == null) {
            $this->normalizeDayFrame();
        }
        
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
        $this->peakSyncedData = array();
        $dayFrameData = $this->dayFrameData;
        
        $this->allDates = array();
        
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
                    if (isset($dayFrameData[$i][0]) && $dayFrameData[$i][0][0] < $current[0]) {
                        $current = $dayFrameData[$i][0];
                        $trackKey = $i;
                    }
                }
            }
            
            unset($dayFrameData[$trackKey][0]);
            $dayFrameData[$trackKey] = array_values($dayFrameData[$trackKey]);
            
            if (!in_array($current[0], $this->allDates)) {
                $this->allDates[] = $current[0]; 
            }
            
            if (!isset($pointTracker[$trackKey]) || $pointTracker[$trackKey] != $current[1]) {
                $pointTracker[$trackKey] = $current[1];
            }
            
            for ($i = 0; $i < sizeof($pointTracker); $i++) {
                if ($pointTracker[$i] != null) {
                    $this->peakSyncedData[$i][$current[0]] = $pointTracker[$i];
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
    }
    
    public function peakAverage() {
        
        if ($this->peakSyncedData == null) {
            $this->peakSync();
        }
        
        $this->peakAverage = array();
        
        foreach ($this->allDates as $date) {
            $sum = 0;
            $avgCount = 0;
            foreach ($this->peakSyncedData as $dayFrame) {
                if (isset($dayFrame[$date])) {
                    $sum += $dayFrame[$date];
                    $avgCount++;
                }
            }
            $this->peakAverage[$date] = $sum/$avgCount;
        }
        
    }
    
}

?>
