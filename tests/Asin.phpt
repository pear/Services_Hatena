--TEST--
ASIN
--SKIPIF--
--FILE--
<?php
error_reporting(E_ALL);
require_once 'Services/Hatena.php';

$hatena = Services_Hatena::factory('Asin');

$result = $hatena->execute('4774124966');

// 2006.02.27 Result is 84. but it may change.
print($result);

?>
--GET--
--POST--
--EXPECT--
84
