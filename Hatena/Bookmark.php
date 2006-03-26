<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Bookmark
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
 * @author     Makoto Tanaka, shoma
 * @copyright  2006 Makoto Tanaka, shoma
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 */

/**
 * Services_Hatena_Bookmark
 *
 * Interface for Hatena::Bookmark's Atom API
 * This class serves get, add, edit and delete method
 * for your bookmark to Hatena::Bookmark.
 *
 * @category   Services
 * @package    Services_Hatena
 * @author     Makoto Tanaka, shoma
 * @copyright  2006 Makoto Tanaka, shoma
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: 0.1.0
 */
class Services_Hatena_Bookmark extends Services_Hatena
{
    /**
     * To store HTTP_Request object.
     *
     * @see Services_Hatena_Bookmark
     */
    var $http;

    /**
     * constructer
     *
     * create HTTP_Request object
     */
    function Services_Hatena_Bookmark()
    {
        $this->http = new HTTP_Request();

        $this->addurl   = 'http://' . $this->bookmark_url . '/atom/post';
        $this->editurl  = 'http://' . $this->bookmark_url . '/atom/edit';
    }

    /**
     * To add bookmark
     *
     * @param   string $url URL
     * @param   string $comment comment
     */
    function add_bookmark($url, $comment)
    {
        $wsse    = $this->getWSSEAuth();

        $rawdata = '
<entry xmlns="http://purl.org/atom/ns#">
<title>PAGE\'S TITLE</title>
<link rel="related" type="text/html" href="'.$url.'" />
<summary type="text/plain">'.$comment.'</summary>
</entry>';

        $this->setHeader(HTTP_REQUEST_METHOD_POST, $this->addurl);
        $this->http->addRawPostData($rawdata);

        return $this->http->sendRequest();

        // if you failed, you will be able to see PEAR::isError
    }

    /**
     * To get bookmark title
     *
     * @param   string $eid hatena bookmark id
     */
    function get_bookmark($eid)
    {
        $this->setHeader(HTTP_REQUEST_METHOD_GET, $this->editurl . '/' . $eid);

        $res = $this->http->sendRequest();
        $local_result = $this->http->getResponseBody();

        $matches = array();
        $pattern = '/<title>(.*)<\/title>/';
        preg_match($pattern, $local_result, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }
        else {
            return PEAR::raiseError('missing the result.');
        }
    }

    /**
     * TO edit bookmark title
     *
     * @param   string $eid hatena bookmark id
     * @param   string $title title
     * @param   string $comment comment
     */
    function edit_bookmark($eid, $title, $comment)
    {
        $rawdata = '
<entry xmlns="http://purl.org/atom/ns#">
<title>'.$title.'</title>
<summary type="text/plain">'.$comment.'</summary>
</entry>';


        $this->setHeader(HTTP_REQUEST_METHOD_PUT, $this->editurl . '/' . $eid);
        $this->http->addRawPostData($rawdata);

        return $this->http->sendRequest();

        // if you failed, you will be able to see PEAR::isError
    }

    /**
     * To delete bookmark
     *
     * @param   string $eid hatena bookmark id
     */
    function delete_bookmark($eid)
    {
        $this->setHeader(HTTP_REQUEST_METHOD_DELETE, $this->editurl . '/' . $eid);
        $this->http->addRawPostData($rawdata);

        return $this->http->sendRequest();

        // if you failed, you will be able to see PEAR::isError
    }

    /**
     * To make header
     *
     * @param   string $method HTTP Method
     * @param   string $url Request URL
     */
    function setHeader($method, $url)
    {
        $this->http->addHeader('Accept', 'application/x.atom+xml, application/xml, text/xml, */*');
        $this->http->addHeader('Authorization', 'WSSE profile="UsernameToken"');
        $this->http->addHeader('X-WSSE', $this->getWSSEAuth());
        $this->http->addHeader('Content-Type', 'application/x.atom+xml');
        $this->http->setMethod($method);
        $this->http->setURL($url);
    }
}
?>

