<?php
include_once '../SQLConnect.php';
include_once '../src/User.php';
if (isset($_GET['ip']) && isset($_GET['id'])) {
    
    try {
        $user = User::fromIp($_GET['ip']);
        $user->active(1);
        $user->remoteId($_GET['id']);
        $out = array('result' => true, "message" => 'done');
        
    } catch (Exception $e) {
    
        try {
            $user = User::CreateNew($_GET['ip'], $_GET['id']);
            if ($user != false) {
                $out = array('result' => true);
            } else {
                $out = array('result' => false, "message" => 'failed to create user');
            }
        } catch (Exception $e) {
            $out = array('result' => false, "message" => 'exception');
        }
    }
} else {
    $out = array('result' => false, "message" => 'ip or remote not set');
}
echo json_encode($out);
?>