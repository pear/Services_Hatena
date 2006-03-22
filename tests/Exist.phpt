--TEST--
EXIST
--SKIPIF--
--FILE--
<?php
error_reporting(E_ALL);
require_once 'Services/Hatena.php';

$hatena = Services_Hatena::factory('Exist');

$result = $hatena->execute('http://www.yahoo.co.jp');

// 2006.02.27 Result is 190. but it may change.
print($result['diary']);

?>
--GET--
--POST--
--EXPECT--
190
