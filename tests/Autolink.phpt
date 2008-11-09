--TEST--
AUTOLINK
--SKIPIF--
--FILE--
<?php
error_reporting(E_ALL);
require_once 'Services/Hatena.php';

$hatena = Services_Hatena::factory('Autolink');

$result = $hatena->execute('This is a test.');

print($result);

?>
--GET--
--POST--
--EXPECT--
This is a <a class="keyword" target="_blank" href="http://d.hatena.ne.jp/keyword/test">test</a>.
