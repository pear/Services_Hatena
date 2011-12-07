--TEST--
SEARCH
--SKIPIF--
--FILE--
<?php
require_once 'Services/Hatena.php';

$hatena = Services_Hatena::factory('Search');

$hatena->execute('PHP');

// 2008.11.09 Result is 16. but it may change.
print($hatena->getTotalSearchResult());

?>
--GET--
--POST--
--EXPECTREGEX--
\d+
