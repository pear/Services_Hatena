<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Services_Hatena Dispatcher
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

require_once 'PEAR.php';
require_once 'HTTP/Request.php';

/**
 * Services_Hatena
 *
 * This class serves method to store or get result.
 * This class is for creating object using Hatena's
 * Web Services API (search, autolink, bookmark, bookmarknum, foto, asin, similar,exist).
 *
 * @category   Services
 * @package    Services_Hatena
 * @author     Makoto Tanaka
 * @copyright  2006 Makoto Tanaka
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: 0.1.0
 */
class Services_Hatena
{
    /**
     * To store login object.
     *
     * @see hatena_login
     */
    var $_login = null;

    /**
     * To store wsse.
     *
     * @see getWSSEAuth
     */
    var $_wsse  = null;

    /**
     * To store add url for atom API.
     */
    var $addurl = null;

    /**
     * To store edit url for atom API.
     */
    var $editurl = null;

    /**
     * hatena bookmark domain
     */
    var $bookmark_url = 'b.hatena.ne.jp';

    /**
     * hatena foto domain
     */
    var $foto_url     = 'f.hatena.ne.jp';

    /**
     * hatena diary domain
     */
    var $diary_url    = 'd.hatena.ne.jp';

    /**
     * hatena question domain
     */
    var $question_url = 'q.hatena.ne.jp';

    /**
     * factory class and return.
     *
     * @param   string $type servics name (search, autolink, asin, bookmarknum, bookmark, similar, foto, exist)
     * @return object class object for using API.
     */
    function factory($type)
    {

        switch ($type) {

        case 'Search'      : // class for Search API
        case 'Autolink'    : // class for autolink API
        case 'Bookmarknum' : // class for bookmarknum API
        case 'Asin'        : // class for asin API
        case 'Similar'     : // class for similar API
        case 'Bookmark'    : // class for bookmark API
        case 'Foto'        : // class for foto API
        case 'Exist'       : // class for exist API
        case 'Question'    : // class for question API

            require_once 'Services/Hatena/' . $type . '.php';
            $classname = 'Services_Hatena_' . $type;

            if (class_exists($classname)) {
                return new $classname;
            } else {
                return PEAR::raiseError('missing the class.');
            }
            break;

        default :
            return PEAR::raiseError('missing the class.');
            break;
        }
    }

    /**
     * To login to hatena
     *
     * @param   string $user user name
     * @param   string $pass password
     */
    function hatena_login($user, $pass)
    {
        $this->_wsse = $this->setWSSEAuth($user, $pass);
    }

    /**
     * Retrun wsse.
     *
     * @return  string WSSE
     */
    function getWSSEAuth()
    {
        return $this->_wsse;
    }

    /**
     * To create string for WSSE
     *
     * @param   string $user user name
     * @param   string $pass password
     * @return  string WSSE
     */
    function setWSSEAuth($user, $pass, $sha1 = false)
    {
         $nowtime = gmdate('Y-m-d\TH:i:s\Z');
         $nonce   = pack('H*', sha1(md5(time())));
         $digest  = base64_encode(pack('H*', sha1($nonce.$nowtime.$pass)));
         $wsse = 'UsernameToken Username="'.$user.'", PasswordDigest="'.$digest.'", Created="'.$nowtime.'", Nonce="'.base64_encode($nonce).'"';

         return $wsse;
    }
}
?>
