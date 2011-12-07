--TEST--
SIMILAR
--SKIPIF--
--FILE--
<?php
require_once 'Services/Hatena.php';

$hatena = Services_Hatena::factory('Similar');

$result = $hatena->execute('PHP');

// 2008.11.09 Result is 30. but it may change.
print(count($result));

?>
--GET--
--POST--
--EXPECTREGEX--
\d+
