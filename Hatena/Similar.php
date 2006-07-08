<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Similar
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
 * Services_Hatena_Similar
 *
 * Interface for Hatena::Diary's getSimilarWord XML-RPC API
 * This class make a call to 'hatena.getSimilarWord' method of the Hatena Web
 * Services. The arguments is Keyword to get similar keyword list.
 *
 * @category   Services
 * @package    Services_Hatena
 * @author     Makoto Tanaka
 * @copyright  2006 Makoto Tanaka
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: 0.1.0
 */
class Services_Hatena_Similar extends Services_Hatena
{
    /**
     * execute
     *
     * @param   string $similar keyword
     * @return  string similar words
     */
    function execute($similar)
    {
        require_once 'XML/RPC.php';

        /**
         * @note
         * I hope the similar keyword is encoded by "UTF-8".
         * $similar = mb_convert_encoding($similar, "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
         */
        $params = $this->makeParams($similar);

        $msg      = new XML_RPC_Message('hatena.getSimilarWord', array($params));
        $client   = new XML_RPC_Client('/xmlrpc', $this->diary_url, 80);
        $response = $client->send($msg);

        if ($response && !$response->faultCode()) {
            $r = $response->value();
            $r = $r->scalarval();
            $r_array = $r['wordlist']->me['array'];

            $match_result = array();
            foreach ( $r_array as $rs ) {
                $word = $rs->me['struct']['word']->me['string'];
                array_push($match_result, $word);
            }

            return $match_result;
        }
        else {
            return PEAR::raiseError('missing the class.');
        }
    }

    /**
     * To make parameter
     *
     * @param   string $similar keyword
     */
    function makeParams($similar)
    {
        if (!(is_array($similar))) {
            $similar = array($similar);
        }

        $wordlist = array();
        foreach ($similar as $s) {
            $xml_rpc_value = new XML_RPC_Value($s,'string');
            array_push($wordlist, $xml_rpc_value);
        }

        $params = new XML_RPC_Value(array(
            'wordlist' => new XML_RPC_Value($wordlist, 'array'),
            ), 'struct');

        return $params;
    }
}
?>

