<?php

$output = [
    'key' => 'this is here to make sure you called the correct file',
    'message' => 'Your setup is correct, successfully communicated with Apache server',
    'success' => true,
];

print(json_encode($output));
