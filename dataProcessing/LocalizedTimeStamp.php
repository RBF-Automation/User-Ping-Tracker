<?php

class LocalizedTimeStamp {
    
    private $rawDate;
    private $NHis;
    private $day;
    private $hour;
    private $min;
    private $second;
    
    
    public function __construct($unixStamp) {
        
        $this->rawDate = $unixStamp;
        
        $this->NHis = date("N-H-i-s", $this->rawDate);
        
        $timeArr = explode('-', $this->NHis);
        
        $this->day = $timeArr[0];
        $this->hour = $timeArr[1];
        $this->min = $timeArr[2];
        $this->second = $timeArr[3];
    }
    
    public function getDay() {
        return $this->day;
    }
    
    public function getHour() {
        return $this->hour;
    }
    
    public function getMin() {
        return $this->min;
    }
    
    public function getSecond() {
        return $this->second;
    }
    
    /*BROKEN
    public function isGreaterThan($lTimeObj) {
        if ($this->getDay() > $lTimeObj->getDay()) {
            return true;
        }
        if ($this->getHour() > $lTimeObj->getHour()) {
            return true;
        }
        if ($this->getMin() > $lTimeObj->getMin()) {
            return true;
        }
        if ($this->getSecond() > $lTimeObj->getSecond()) {
            return true;
        }
        return false;
    }
    */
    
    public function isLessThan($lTimeObj) {
        if ($this->getDay() < $lTimeObj->getDay()) {
            return true;
        }
        if ($this->getDay() <= $lTimeObj->getDay() && $this->getHour() < $lTimeObj->getHour()) {
            return true;
        }
        if ($this->getDay() <= $lTimeObj->getDay() && $this->getHour() <= $lTimeObj->getHour() &&$this->getMin() < $lTimeObj->getMin()) {
            return true;
        }
        if ($this->getDay() <= $lTimeObj->getDay() && $this->getHour() <= $lTimeObj->getHour() &&$this->getMin() <= $lTimeObj->getMin() &&$this->getSecond() < $lTimeObj->getSecond()) {
            return true;
        }
        return false;
    }
    
    public function getString() {
        return $this->NHis;
    }
    
    public static function parseToInt($str) {
        $timeArr = explode('-', $str);
        
        $day = $timeArr[0];
        $hour = $timeArr[1];
        $min = $timeArr[2];
        $second = $timeArr[3];
        
        $min = $min * 60;
        $hour = $hour * 60 * 60;
        $day = $day * 60 * 60 * 24;
        
        return $second + $min + $hour + $day;
    }
    
}

?>
