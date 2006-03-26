<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Asin class
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
 * Services_Hatena_Asin
 *
 * Interface for Hatena::Bookmark's getAsinCount XML-RPC API
 * This class make a call to 'bookmark.getAsinCount' method of the Hatena Web
 * Services. The arguments is ASIN Code to get a number of registrations
 * in Hatena.
 * (ASIN Code : Amazon Standard Identification Number)
 *
 * @category   Services
 * @package    Services_Hatena
 * @author     Makoto Tanaka
 * @copyright  2006 Makoto Tanaka
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: 0.1.0
 */
class Services_Hatena_Asin extends Services_Hatena
{
    /**
     * execute function
     *
     * @param   string $asin ASIN Code
     * @return  string number of collectioned asin
     */
    function execute($asin)
    {
        require_once 'XML/RPC.php';

        $params = new XML_RPC_Value($asin,'string');

        $msg      = new XML_RPC_Message('bookmark.getAsinCount', array($params));
        $client   = new XML_RPC_Client('/xmlrpc', $this->bookmark_url, 80);
        $response = $client->send($msg);

        if (!$response->faultCode()) {
            $r = $response->value();
            $r = $r->scalarval();
            return $r[$asin]->me['int'];
        } else {
            return PEAR::raiseError('missing the result.');
        }
    }
}
?>

