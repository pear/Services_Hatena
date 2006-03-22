<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Test Script
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Services
 * @package    Services_Hatena
 * @author     Makoto Tanaka
 * @copyright  2006 Makoto Tanaka
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 */

require_once 'Services/Hatena.php';

// $type  = 'Search';
$type  = 'Autolink';
// $type  = 'Bookmarknum';
// $type  = 'Asin';
// $type  = 'Similar';
// $type  = 'Exist';

$datum = array( 'Search'      => 'PHP',
                'Autolink'    => 'I like PHP.',
                'Bookmarknum' => 'http://www.yahoo.co.jp/',
                'Asin'        => '4774124966',
                'Similar'     => 'PHP',
                'Exist'       => 'http://www.yahoo.co.jp',
              );
$data  = $datum[$type];
$hatena = Services_Hatena::factory($type);

if (PEAR::isError($hatena)) {
    print('i have an Error : ' . $hatena->getMessage());
    exit();
}
   
/**
 * @note
 * only for autolink
 *
 $hatena->setScore(0);
 $hatena->setCname(array('food', 'movie'));
 $hatena->setTarget('_blank');
 $hatena->setClass('keyword');
 */

$result = $hatena->execute($data);
var_dump($result);
?>

