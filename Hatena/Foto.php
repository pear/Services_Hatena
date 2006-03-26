<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Foto Life
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
 * Services_Hatena_Foto
 *
 * Interface for Hatena::Fotolife's Atom API
 * This class serves get, add, edit and delete method
 * for your image file to Hatena::Fotolife.
 *
 * @category   Services
 * @package    Services_Hatena
 * @author     Makoto Tanaka
 * @copyright  2006 Makoto Tanaka
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: 0.1.0
 */
class Services_Hatena_Foto extends Services_Hatena
{
    /**
     * To store HTTP_Request Object
     *
     * @see Services_Hatena_Foto
     */
    var $http;

    /**
     * constructer
     *
     * create HTTP_Request object.
     */
    function Services_Hatena_Foto()
    {
        $this->http = new HTTP_Request();

        $this->addurl   = 'http://' . $this->foto_url . '/atom/post';
        $this->editurl  = 'http://' . $this->foto_url . '/atom/edit';
    }

    /**
     * To add image
     *
     * @param   string $image image name
     * @param   string $title image title
     */
    function add_foto($image, $title)
    {
        $wsse = $this->getWSSEAuth();
        $based_image = base64_encode(file_get_contents($image));

        $rawdata = '
<entry xmlns="http://purl.org/atom/ns#">
<title>'.$title.'</title>
<content mode="base64" type="image/jpeg">'.$based_image.'</content>
</entry>';

        $this->setHeader(HTTP_REQUEST_METHOD_POST, $this->addurl);
        $this->http->addRawPostData($rawdata);

        return $this->http->sendRequest();
 
        // if you failed, you will be able to see PEAR::isError
    }

    /**
     * To get image title
     *
     * @param   string $fid hatena foto id
     */
    function get_foto($fid)
    {
        $this->setHeader(HTTP_REQUEST_METHOD_GET, $this->editurl . '/'. $fid);

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
     * To edit image title
     *
     * @param   string $fid hatena foto id
     * @param   string $title image title
     */
    function edit_foto($fid, $title)
    {
        $rawdata = '
<entry xmlns="http://purl.org/atom/ns#">
<title>'.$title.'</title>
</entry>';


        $this->setHeader(HTTP_REQUEST_METHOD_PUT, $this->editurl . '/' . $fid);
        $this->http->addRawPostData($rawdata);

        return $this->http->sendRequest();

        // if you failed, you will be able to see PEAR::isError
    }

    /**
     * To delete image
     *
     * @param   string $fid hatena foto id
     */
    function delete_foto($fid)
    {
        $this->setHeader(HTTP_REQUEST_METHOD_DELETE, $this->editurl . '/' . $fid);
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

