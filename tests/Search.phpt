--TEST--
SEARCH
--SKIPIF--
--FILE--
<?php
error_reporting(E_ALL);
require_once 'Services/Hatena.php';

$hatena = Services_Hatena::factory('Search');

$hatena->execute('PHP');

// 2006.02.27 Result is 12. but it may change.
print($hatena->getTotalSearchResult());

?>
--GET--
--POST--
--EXPECT--
12
