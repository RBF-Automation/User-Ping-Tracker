<?php
include_once 'SQLConnect.php';
include_once 'src/User.php';

include_once 'src/Log.php';

$users = User::getUsers();

foreach ($users as $user) {
    
    $lastAction = Log::getUserLastAction($user);
    
    $action = 0;
    
    if (ping($user->ip())) {
        $action = 1;
    }
    
    if ($lastAction == null) {
        Log::logAction($user->ID(), $action);
    } else if ($lastAction->isHome() != $action) {
        Log::logAction($user->ID(), $action);
    }
    
}

function ping($ip) {
    $pingresult = exec("ping -c 2 " . $ip, $outcome, $status);
    return (0 == $status);
}

?>
