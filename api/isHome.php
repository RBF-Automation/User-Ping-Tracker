<?php

include_once '../SQLConnect.php';
include_once '../src/Log.php';

if (isset($_GET['ip'])) {
    try {
        $user = User::fromIp($_GET['ip']);
        $lastAction = Log::getUserLastAction($user);
        
        if ($lastAction != null) {
            if ($lastAction->isHome() == 1) {
                $out = array('result' => true, "isHome" => true);
            } else {
                $out = array('result' => true, "isHome" => false);
            }
            
        } else {
            $out = array('result' => false, "message" => 'No Log');
        }
    } catch (Exception $e) {
        $out = array('result' => false, "message" => 'Exception User does not exist');
    }

} else {
    $out = array('result' => false, "message" => 'ip not set');
}

echo json_encode($out);

?>
