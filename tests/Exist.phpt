--TEST--
EXIST
--SKIPIF--
--FILE--
<?php
require_once 'Services/Hatena.php';

$hatena = Services_Hatena::factory('Exist');

$result = $hatena->execute('http://www.yahoo.co.jp');

// 2008.11.09 Result is 357. but it may change.
print($result['diary']);

?>
--GET--
--POST--
--EXPECTREGEX--
\d+
