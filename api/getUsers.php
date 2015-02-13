<?php

include_once '../SQLConnect.php';
include_once '../src/Log.php';

$users = User::getUsers();

$out = array();
foreach ($users as $user) {
    $out[] = $user->ip();
}

echo json_encode($out);

?>
