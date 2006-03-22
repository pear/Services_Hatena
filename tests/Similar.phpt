--TEST--
SIMILAR
--SKIPIF--
--FILE--
<?php
error_reporting(E_ALL);
require_once 'Services/Hatena.php';

$hatena = Services_Hatena::factory('Similar');

$result = $hatena->execute('PHP');

// 2006.02.27 Result is 11. but it may change.
print(count($result));

?>
--GET--
--POST--
--EXPECT--
11
