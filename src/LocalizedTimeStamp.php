<?php

class LocalizedTimeStamp {
    
    private $day;
    private $hour;
    private $min;
    private $second;
    
    
    public static function fromUnix($unix) {
        $strStamp = date("N-H-i-s", $unix);
        $timeArr = explode('-', $strStamp);
        return new self($timeArr);
    }
    
    public static function fromInt($numTime) {
        $timeArr = array();
        
        $oneDay = (60 * 60 * 24);
        $oneHour = (60 * 60);
        $oneMin = (60);
        
        $days = floor($numTime / $oneDay);
        $numTime = $numTime % $oneDay;
        
        $hours = floor($numTime / $oneHour);
        $numTime = $numTime % $oneHour;
        
        $mins = floor($numTime / $oneMin);
        $seconds = $numTime % $oneMin;
        
        return new self(array($days, $hours, $mins, $seconds));
    }
    
    
    public function __construct($timeArr) {
        $this->day = $timeArr[0];
        $this->hour = $timeArr[1];
        $this->min = $timeArr[2];
        $this->second = $timeArr[3];
    }
    
    public function intVal() {
        $min = $this->min * 60;
        $hour = $this->hour * 60 * 60;
        $day = $this->day * 60 * 60 * 24;
        
        return $this->second + $min + $hour + $day;
    }
    
    public function getDays() {
        return $this->day;
    }
    
    public function getHours() {
        return $this->hour;
    }
    
    public function getMins() {
        return $this->min;
    }
    
    public function getSeconds() {
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
    
    public function equals($lTimeObj) {
        if ($this->getDay() == $lTimeObj->getDay()) {
            return true;
        }
        if ($this->getHour() == $lTimeObj->getHour()) {
            return true;
        }
        if ($this->getMin() == $lTimeObj->getMin()) {
            return true;
        }
        if ($this->getSecond() == $lTimeObj->getSecond()) {
            return true;
        }
        return false;
    }
    
    
    /*
    public function getString() {
        return $this->NHis;
    }
    */
    
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
