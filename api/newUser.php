<?php

include_once '../SQLConnect.php';
include_once '../src/User.php';

if (isset($_GET['ip'])) {

    try {
        $user = User::CreateNew($_GET['ip']);
        if ($user != false) {
            $out = array('result' => true);
        } else {
            $out = array('result' => false, "message" => 'failed to create user');
        }
    } catch (Exception $e) {
        $out = array('result' => false, "message" => 'exception');
    }


} else {
    $out = array('result' => false, "message" => 'ip not set');
}

echo json_encode($out);

?>
