<?php
include_once '../SQLConnect.php';
include_once '../src/User.php';
if (isset($_GET['ip'])) {
    try {
        $user = User::fromIp($_GET['ip']);
        $user->active(0);
        $out = array('result' => true, "message" => 'done');
    } catch (Exception $e) {
        $out = array('result' => false, "message" => 'exception');
    }
} else {
    $out = array('result' => false, "message" => 'ip not set');
}
echo json_encode($out);
?>