--TEST--
ASIN
--SKIPIF--
--FILE--
<?php
require_once 'Services/Hatena.php';

$hatena = Services_Hatena::factory('Asin');

$result = $hatena->execute('4774124966');

// 2008.11.09 Result is 129. but it may change.
print($result);

?>
--GET--
--POST--
--EXPECTREGEX--
\d+
