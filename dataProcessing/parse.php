<?php

    $file = "jake.csv";
    $arr = array_map('str_getcsv', file($file));
    //print_r($arr);
    //return;

    $fiveMins = 5 * 60;

    $fixedData = array();

    $startTime = $arr[0][3];

    $endTime = $arr[sizeof($arr)-1][3];

    $currentTime = $startTime;
    $currentVal = $arr[0][2];
    $index = 0;
    while($currentTime < $endTime) {
        $timeInData = $arr[$index][3];
        if (abs($currentTime - $timeInData) < $fiveMins) {
            $currentVal = $arr[$index][2];
            $index++;

        }
        $fixedData[$currentTime] = $currentVal;
        $currentTime += 60;
    }

    // print_r($fixedData);

    $oneDay = 60 * 60 * 24;
    $oneWeek = 7 * $oneDay;

    $data = array();
    $count = 0;
    $timeCount = 0;
    $day = "";
    //$dayOfTheWeekStart = date('l', $fixedData[0]);
    foreach ($fixedData as $key => $val) {
        $day = date('l', $key);
        if ($lastDay == "Saturday" && $day != "Saturday") {
            $count++;
            $timeCount = 0;
        }
        $data[$count][$timeCount] = $val;
        $timeCount += 60;
        $lastDay = $day;
    }
    
    $average = array();
    
    $stamp = 0;
    while ($stamp < $oneWeek) {
        $sum = 0;
        $avgCount = 0;
        foreach ($data as $item) {
            if (isset($item[$stamp])) {
                $sum += $item[$stamp];
                $avgCount++;
            }
        }
        $average[$stamp] = $sum/$avgCount;
        $stamp += 60;
    }
    
    //print_r($average);
    $lastTime = 0;
    $lastTime1 = 0;
    $timeStamp = 0;
    $x = array();
    $y = array();
    
    $s = function($t) use ($oneDay) {return $t < $oneDay;};
    $m = function($t) use ($oneDay) {return $t < $oneDay * 2 && $t > $oneDay;};
    $t = function($t) use ($oneDay) {return $t < $oneDay * 3 && $t > $oneDay * 2;};
    $w = function($t) use ($oneDay) {return $t < $oneDay * 4 && $time > $oneDay * 3;};
    $r = function($t) use ($oneDay) {return $t < $oneDay * 5 && $time > $oneDay * 4;};
    $f = function($t) use ($oneDay) {return $t < $oneDay * 6 && $time > $oneDay * 5;};
    $sa = function($t) use ($oneDay) {return $t < $oneDay * 7 && $time > $oneDay * 6;};
    
    $day = $t;
    
    foreach ($average as $time => $value) {
        //if ($day($time)) {
            $x[] = $time;
            $y[] = $value * 100;
        //}
        
        /*
        if ($lastTime + ($oneDay) < $time) {
            $lastTime = $time;
            $timeStamp = 0;
            if ($time < $oneDay) {
                $day = $s;
            } else if ($time < $oneDay * 2) {
                $day = $m;
            } else if ($time < $oneDay * 3) {
                $day = $t;
            } else if ($time < $oneDay * 4) {
                $day = $w;
            } else if ($time < $oneDay * 5) {
                $day = $r;
            } else if ($time < $oneDay * 6) {
                $day = $f;
            } else if ($time < $oneDay * 7) {
                $day = $sa;
            }
        }
        */
    }
    
    $days = array(
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
    );
    
    $dayTrack = 0;
    
    $f = 0;
    
    $tpos = array();
    $tlabel = array();
    $t = 0;
    $hr = 11;
    $realHr = 11;
    $apm = 'am';
    while (sizeof($tpos) < (24 * 7)) {
        if ($t % (60 * 60) == 0) {
            
            $hr++;
            $realHr++;
            $tpos[] = $t;
            if ($f < 24) {
                $tlabel[] = $realHr . $apm;
            } else {
                $f = 0;
                $dayTrack++;
                $tlabel[] = $days[$dayTrack] . $hr . $apm;
            }
            $f++;
            
        }
        $t += 60;
        
        if ($hr == 12) {
            $realHr = 0;
        }
        if ($hr == 11) {
            $apm = 'am';
        }
        if ($hr == 24) {
            $realHr = 0;
            $hr = 0;
            $apm = 'pm';
        }
        if ($hr == 23) {
            $apm = 'pm';
        }
        
        
    }
    
    //print_r($x);
    //return;
    require_once ('jpgraph/src/jpgraph.php');
    require_once ('jpgraph/src/jpgraph_line.php');
    require_once ('jpgraph/src/jpgraph_scatter.php');
    require_once ('jpgraph/src/jpgraph_regstat.php');
    require_once( "jpgraph/src/jpgraph_date.php" );
    
    // Original data points
    //$xdata = array(1,3,5,7,9,12,15,17.1);
    //$ydata = array(5,1,9,6,4,3,19,12);
    
    // Get the interpolated values by creating
    // a new Spline object.
    //$spline = new Spline($x,$y);
    
    // For the new data set we want 40 points to
    // get a smooth curve.
    //list($newx,$newy) = $spline->Get(50);
    
    // Create the graph
    $g = new Graph(4000,300);
    $g->SetMargin(30,20,40,160);
    $g->title->Set("Natural cubic splines");
    $g->title->SetFont(FF_ARIAL,FS_NORMAL,12);
    $g->subtitle->Set('');
    $g->subtitle->SetColor('darkred');
    $g->SetMarginColor('lightblue');
    
    //$g->img->SetAntiAliasing();
    
    // We need a linlin scale since we provide both
    // x and y coordinates for the data points.
    $g->SetScale('linlin',0,0,$x[0],$x[sizeof($x)-1]);
    
    //$g->xaxis->SetTimeAlign( HOURADJ_1 );
    
    // We want 1 decimal for the X-label
    //$g->xaxis->SetLabelFormat('%1.1f');
    
    // We use a scatterplot to illustrate the original
    // contro points.
    //$splot = new ScatterPlot($y,$x);
    
    // 
    //$splot->mark->SetFillColor('red@0.3');
    //$splot->mark->SetColor('red@0.5');
    
    // And a line plot to stroke the smooth curve we got
    // from the original control points
    $lplot = new LinePlot($y,$x);
    $lplot->SetColor('navy');
    
    //$g->SetScale( 'datlin' );
    $g->xaxis->SetMajTickPositions($tpos, $tlabel);
    $g->xaxis->SetFont(FF_ARIAL,FS_NORMAL,14);
    $g->xaxis->SetLabelAngle(90);
    $g->xgrid->Show();
    
    // Add the plots to the graph and stroke
    $g->Add($lplot);
    //$g->Add($splot);
    $g->Stroke();
    
    
    
    
    
    
    



?>
