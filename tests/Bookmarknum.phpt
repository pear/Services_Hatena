--TEST--
BOOKMARKNUM
--SKIPIF--
--FILE--
<?php
require_once 'Services/Hatena.php';

$hatena = Services_Hatena::factory('Bookmarknum');

$result = $hatena->execute('http://www.yahoo.co.jp/');

// 2008.11.09 Result is 3850. but it may change.
print($result);

?>
--GET--
--POST--
--EXPECTREGEX--
\d+
