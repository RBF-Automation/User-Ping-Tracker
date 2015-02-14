<?php

include_once '../SQLConnect.php';
include_once '../src/Log.php';

$users = User::getUsers();

$out = array();
foreach ($users as $user) {
    $lastAction = Log::getUserLastAction($user);
    
    $home = false;
    
    if ($lastAction != null) {
        if ($lastAction->isHome() == 1) {
            $home = true;
        }
    }
            
            
    $out[] = array(
        'ip' => $user->ip(),
        'user' => $user->remoteId(),
        'isHome' => $home,
    );
    
}

echo json_encode($out);

?>
