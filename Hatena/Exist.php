<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Exist
 *
 * PHP version 4 or newer
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

/**
 * Services_Hatena_Exist
 *
 * Interface for Hatena's exist REST API
 * This class make a method to get Resources of a URL in Hatena Services.
 * The arguments is URL to get information of registrations in Hatena.
 *
 * @category   Services
 * @package    Services_Hatena
 * @author     Makoto Tanaka
 * @copyright  2006 Makoto Tanaka
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: 0.1.0
 */
class Services_Hatena_Exist extends Services_Hatena
{
    /**
     * To send request URL. This will not change.
     *
     * @see execute
     */
    var $exist_url = 'http://d.hatena.ne.jp/exist?mode=xml&url=';

    /**
     * execute
     *
     * @param   string $url URL
     * @return  string information
     */
    function execute($url)
    {
        require_once 'HTTP/Request.php';

        $http = new HTTP_Request();

        $req_url = $this->exist_url . $url;

        $http->setURL($req_url);
        $http->setMethod(HTTP_REQUEST_METHOD_GET);

        $res = $http->sendRequest();

        $local_result = $http->getResponseBody();

        $matches = array();
        $pattern = '/<count name="(.*)">(.*)<\/count>/';
        preg_match_all($pattern, $local_result, $matches);

        /**
         * @note
         * this is example matches result
         *  [1] => Array (
         *          [0] => diary
         *          [1] => antenna
         *          [2] => bookmark
         *         )
         *  [2] => Array (
         *          [0] => 796
         *          [1] => 1
         *          [2] => 577
         *         )
         *
         * if the information is nothing, i will return null.
         */
        $match_result = array();
        if ($matches[1][0] && $matches[2][0]) { 
            $match_result[$matches[1][0]] = $matches[2][0];
        }
        if ($matches[1][1] && $matches[2][1]) {
            $match_result[$matches[1][1]] = $matches[2][1];
        }
        if ($matches[1][2] && $matches[2][2]) {
            $match_result[$matches[1][2]] = $matches[2][2];
        }
        return $match_result;
    }
}
?>

