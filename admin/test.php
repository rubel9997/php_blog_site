<?php

$name = null;
$default_username = 'Guest';

$username = $name ?? $default_username;

echo $username;

?>