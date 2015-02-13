<?php
include_once 'SQLConnect.php';
include_once 'src/User.php';

include_once 'src/Log.php';

$users = User::getUsers();

foreach ($users as $user) {
    if (ping($user->ip())) {
        Log::logAction($user->ID(), 1);
    } else {
        Log::logAction($user->ID(), 0);
    }
}

function ping($ip) {
    $pingresult = exec("ping -c 2 " . $ip, $outcome, $status);
    return (0 == $status);
}




?>
