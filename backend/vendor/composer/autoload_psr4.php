<?php

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Firebase\\JWT\\' => array($vendorDir . '/firebase/php-jwt/src'),
    'App\\' => array($baseDir . '/App'),
);
