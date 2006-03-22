<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Autolink
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

/**
 * Services_Hatena_Autolink
 *
 * Interface for Hatena::Diary's setKeywordLink XML-RPC API
 * This class make a call to 'hatena.setKeywordLink' method of the Hatena Web
 * Services. The arguments is keyword to get autolinked keyword.
 *
 * @category   Services
 * @package    Services_Hatena
 * @author     Makoto Tanaka
 * @copyright  2006 Makoto Tanaka
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: 0.1.0
 */
class Services_Hatena_Autolink extends Services_Hatena
{
    /**
     * To store parameter
     *
     * @see makeParams
     */
    var $parameters;

    /**
     * execute
     *
     * return autolinked keyword
     *
     * @param   string $keyword keyword
     * @return  string autolinked keyword
     */
    function execute($keyword)
    {
        require_once 'XML/RPC.php';

        /**
         * @note
         * I hope the keyword is encoded by "UTF-8".
         * $keyword = mb_convert_encoding($keyword, "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
         */
        $params  = $this->makeParams($keyword);

        $msg      = new XML_RPC_Message('hatena.setKeywordLink', array($params));
        $client   = new XML_RPC_Client('/xmlrpc', $this->diary_url, 80);
        $response = $client->send($msg);

        if (!$response->faultCode()) {
            $r = $response->value();
            $r = $r->scalarval();
            $r = html_entity_decode($r);
            return $r;
        }
        else {
            return PEAR::raiseError('missing the result.');
        }
    }

    /**
     * To set score
     *
     * You can set score.(You can omit it.)
     *
     * @param   string $score score
     */
    function setScore($score)
    {
        if ($score < 0 || 50 < $score) {
            return;
        }
        $this->parameters['score'] = $score; 
    }

    /**
     * To set target
     *
     * You can set target.(You can omit it.)
     *
     * @param   string $target target
     */
    function setTarget($target)
    {
        $this->parameters['a_target'] = $target; 
    }

    /**
     * To set class
     *
     * You can set class.(You can omit it.)
     *
     * @param   string $class class
     */
    function setClass($class)
    {
        $this->parameters['a_class'] = $class; 
    }

    /**
     * To set category
     *
     * You can set category.(You can omit it.)
     *
     * @param   string $cname Category
     */
    function setCname($cname)
    {
        if (!(is_array($cname))) {
            $cname = array($cname);
        }
        $this->parameters['cname'] = $cname; 
    }

    /**
     * To make parameter
     *
     * @param   string $keyword keyword
     */
    function makeParams($keyword)
    {
        $score    = isset($this->parameters['score'])    ? $this->parameters['score']    : 0; 
        $a_target = isset($this->parameters['a_target']) ? $this->parameters['a_target'] : '_blank'; 
        $a_class  = isset($this->parameters['a_class'])  ? $this->parameters['a_class']  : 'keyword'; 
        $cname    = array();
        if (isset($this->parameters['cname'])) {
            foreach ($this->parameters['cname'] as $c) {
                 $xml_rpc_value = new XML_RPC_Value($c,'string');
                 array_push($cname, $xml_rpc_value);
            }
        }

        $params  = new XML_RPC_Value(array(
           'body'     => new XML_RPC_Value($keyword , 'string'),
           'score'    => new XML_RPC_Value($score   , 'int'),
           'cname'    => new XML_RPC_Value($cname   , 'array'),
           'a_target' => new XML_RPC_Value($a_target, 'string'),
           'a_class'  => new XML_RPC_Value($a_class , 'string')
        ), 'struct');

        return $params;
    }
}
?>

