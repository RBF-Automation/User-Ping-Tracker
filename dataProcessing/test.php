<?php

    include_once 'Data.php';
    include_once 'LocalizedTimeStamp.php';
    
    
    $file = "brian1.csv";
    $arr = array_map('str_getcsv', file($file));
    
    $data = new Data($arr, 3, 2);
    $data->runPrePeak();
    $average = $data->getPeakAverage();
    
    
    $x = array();
    $y = array();
    foreach ($average as $key => $val) {
        //echo LocalizedTimeStamp::parseToInt($key) . " " . $key . "\n";
        $x[] = $key;
        $y[] = $val * 100;
    }
    
    
    require_once ('jpgraph/src/jpgraph.php');
    require_once ('jpgraph/src/jpgraph_line.php');
    require_once ('jpgraph/src/jpgraph_scatter.php');
    require_once ('jpgraph/src/jpgraph_regstat.php');
    require_once( "jpgraph/src/jpgraph_date.php" );
    
    // Original data points
    
    // Get the interpolated values by creating
    // a new Spline object.
    $spline = new Spline($x,$y);
    
    // For the new data set we want 40 points to
    // get a smooth curve.
    list($newx,$newy) = $spline->Get(100);
    
    // Create the graph
    $g = new Graph(4000,300);
    $g->SetMargin(30,20,40,30);
    $g->title->Set("Natural cubic splines");
    $g->title->SetFont(FF_ARIAL,FS_NORMAL,12);
    $g->subtitle->Set('(Control points shown in red)');
    $g->subtitle->SetColor('darkred');
    $g->SetMarginColor('lightblue');
    
    $g->SetScale('linlin');
    
    $lplot = new LinePlot($y,$x);
    $lplot->SetColor('navy');
    
    $g->Add($lplot);
    $g->Stroke();
    
    /*
    $spline = new Spline($x,$y);
    
    list($newx,$newy) = $spline->Get(50);
    
    
    $g = new Graph(4000,300);
    $g->SetMargin(30,20,40,160);
    $g->title->Set("Natural cubic splines");
    $g->title->SetFont(FF_ARIAL,FS_NORMAL,12);
    $g->subtitle->Set('');
    $g->subtitle->SetColor('darkred');
    $g->SetMarginColor('lightblue');
    
    $g->SetScale('linlin',0,0,$x[0],$x[sizeof($x)-1]);
    
    $lplot = new LinePlot($y,$x);
    $lplot->SetColor('navy');
    
    //$g->xaxis->SetMajTickPositions($tpos, $tlabel);
    //$g->xaxis->SetFont(FF_ARIAL,FS_NORMAL,14);
    //$g->xaxis->SetLabelAngle(90);
    //$g->xgrid->Show();
    
    $g->Add($lplot);
    $g->Stroke();
    */

?>