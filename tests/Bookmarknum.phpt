--TEST--
BOOKMARKNUM
--SKIPIF--
--FILE--
<?php
error_reporting(E_ALL);
require_once 'Services/Hatena.php';

$hatena = Services_Hatena::factory('Bookmarknum');

$result = $hatena->execute('http://www.yahoo.co.jp/');

// 2006.02.27 Result is 586. but it may change.
print($result);

?>
--GET--
--POST--
--EXPECT--
586
