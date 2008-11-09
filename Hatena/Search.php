<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Search
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
 * Services_Hatena_Search
 *
 * Interface for Hatena::Search with OpernSerch.
 * This class make Search function from Hatena.
 * The arguments is keyword to get a search result from Hatena.
 *
 * @category   Services
 * @package    Services_Hatena
 * @author     Makoto Tanaka
 * @copyright  2006 Makoto Tanaka
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: 0.1.0
 */
class Services_Hatena_Search extends Services_Hatena
{
    /**
     * To store OpenSearch Description Document URI.
     *
     * @see Services_Hatena_Search
     */
    var $osxml_url = 'http://search.hatena.ne.jp/osxml';

    /**
     * To store OpenSearch object
     *
     * @see Services_Hatena_Search
     */
    var $os = null;

    /**
     * constructer
     *
     * create OpenSearch object
     */
    function Services_Hatena_Search()
    {
        require_once 'Services/OpenSearch.php';
        $this->os = new Services_OpenSearch($this->osxml_url);
    }

    /**
     * execute
     *
     * @param  string $keyword keyword
     * @return array search result
     */
    function execute($keyword)
    {
        /**
         * @note
         * I hope the keyword is encoded by "UTF-8".
         * $keyword = mb_convert_encoding($keyword, "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
         */
        return $this->os->search($keyword);
    }

    /**
     * To get number of search result
     *
     * @return  intger number of search result
     */
    function getTotalSearchResult()
    {
        return $this->os->getTotalResults();
    }

    /**
     * To set start page for search
     *
     * @param integer $p page number
     */
    function setStartPage($p)
    {
        $this->os->setStartPage($p);
    }

    /**
     * To get start page for search
     *
     * @return intger page number
     */
    function getStartPage()
    {
        return $this->os->getStartPage();
    }

    /**
     * Retrieves the currently set entry count of per page.
     *
     * @return intger entry count
     */
    function getCount()
    {
        return $this->os->getCount();
    }

    /**
     * To get previous page number
     *
     * @return page number
     */
    function getPrevPage()
    {
        $s = $this->getStartPage();
        $r = $this->getTotalSearchResult();
        $c = $this->getCount();
  
        if ($s >= 2) {
            return $s - 1;
        }
    }

    /**
     * To get next page number
     *
     * @return page number
     */
    function getNextPage()
    {
        $s = $this->getStartPage();
        $r = $this->getTotalSearchResult();
        $c = $this->getCount();
        $c = ( $c != 0 ) ? $c : 1;
  
        if (($r / $c) >= $s) {
            return $s + 1;
        }
    }
}
?>
