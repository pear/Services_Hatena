<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Question
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
 * Services_Hatena_Question
 *
 * Interface for Hatena::Question's getSimilarQuestion XML-RPC API
 * This class make a call to 'hatena.getSimilarQuestion' method of the Hatena Web
 * Services. The arguments is question to get similar question.
 *
 * @category   Services
 * @package    Services_Hatena
 * @author     Makoto Tanaka
 * @copyright  2006 Makoto Tanaka
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: 0.1.0
 */
class Services_Hatena_Question extends Services_Hatena
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
     * @param   string $question question
     * @return  array similar questions
     */
    function execute($question)
    {
        require_once 'XML/RPC.php';

        /**
         * @note
         * I hope the question is encoded by "UTF-8".
         * $question = mb_convert_encoding($question, "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
         */
        $params = $this->makeParams($question);

        $msg      = new XML_RPC_Message('question.getSimilarQuestion', array($params));
        $client   = new XML_RPC_Client('/xmlrpc', $this->question_url, 80);
        $response = $client->send($msg);


        if ($response && !$response->faultCode()) {
            $r = $response->value();
            $r_array = $r->me['array'];

            $match_result = array();
            foreach ( $r_array as $rs ) {
                $qid     = $rs->me['struct']['qid']->me['int'];
                $content = $rs->me['struct']['content']->me['string'];
                $uri     = $rs->me['struct']['uri']->me['string'];

		        $match_result[$qid] = array('content' => $content, 'uri' => $uri);
            }


            return $match_result;
        }
        else {
            return PEAR::raiseError('missing the class.');
        }
    }

    /**
     * To set limit
     *
     * You can set limit.(You can omit it.)
     *
     * @param   int $limit result limit
     */ 
    function setLimit($limit)
    {   
        $this->parameters['limit'] = $limit;
    }

    /**
     * To set threshold
     *
     * You can set threshold.(You can omit it.)
     *
     * @param   int $threshold threshold
     */ 
    function setThreshold($threshold)
    {   
        $this->parameters['threshold'] = $threshold;
    }

    /**
     * To make parameter
     *
     * @param   string $question keyword
     */
    function makeParams($question)
    {
        $limit     = isset($this->parameters['limit'])     ? $this->parameters['limit']     : 5;
        $threshold = isset($this->parameters['threshold']) ? $this->parameters['threshold'] : 2;

        $params  = new XML_RPC_Value(array(
           'limit'     => new XML_RPC_Value($limit    , 'int'),
           'threshold' => new XML_RPC_Value($threshold, 'int'),
           'content'   => new XML_RPC_Value($question , 'string')
        ), 'struct');

        return $params;
    }
}
?>
