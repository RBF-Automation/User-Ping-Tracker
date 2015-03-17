<?php

include_once '../SQLConnect.php';
include_once '../src/Log.php';
include_once '../src/FortuneTeller.php';

$users = User::getUsers();

$out = array();
foreach ($users as $user) {
    $lastAction = Log::getUserLastAction($user);
    
    
    
    $home = false;
    $status = null;
    $userStatus = "";
    
    if ($lastAction != null) {
        if ($lastAction->isHome() == 1) {
            $home = true;
        }
        
        $data = new Data(Log::getRawData($user), "time", "isHome");
        $fortuneTeller = new FortuneTeller($data);
        
        $timeTest = LocalizedTimeStamp::fromUnix(time());
        $status = $fortuneTeller->timeToEvent($timeTest, !$lastAction->isHome(), .7);
    }
    
    if ($status != null) {
        
        $time = LocalizedTimeStamp::fromInt($status['timeTo']);
        $range = LocalizedTimeStamp::fromInt($status['duration']);
        
        if ($home) {
            $userStatus = "Likely to leave in " . $time->getHours() . " hours and " . $time->getMins() . " minutes for about " . $range->getHours() . " hours and " . $range->getMins() . " minutes.";
        } else {
            $userStatus = "Likely to be back in " . $time->getHours() . " hours and " . $time->getMins() . " minutes for about " . $range->getHours() . " hours and " . $range->getMins() . " minutes.";
        }
    }
    
    $out[] = array(
        'ip' => $user->ip(),
        'user' => $user->remoteId(),
        'isHome' => $home,
        'status' => $userStatus,
    );
    
}

echo json_encode($out);

?>
