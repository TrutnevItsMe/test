<?php
namespace Intervolga\Custom\Search;

use Bitrix\Main\Loader;
use CSearch;

class SearchSubString
{
    /**
     * @var bool
     */
    protected $isSearchBySubstring = false;

    /**
     * @var string
     */
    protected $originalQuery = '';
    /**
     * @var string
     */
    protected $queryForLikeRequest = '';

    /** @var SearchSubstring[] */
    private static $instances;

    private function __construct($query)
    {
        $this->setOriginalQuery($query);
    }
    private function __clone() {}
    private function __wakeup() {}

    /**
     * @return SearchSubstring
     */
    public static function getInstance($query)
    {
        $q = trim(htmlspecialchars_decode($query), " \t\n\r\0\x0B\"");
        $hash = md5($q);
        if (!static::$instances) {
            static::$instances[$hash] = new static($q);
        }

        return static::$instances[$hash];
    }

    /**
     * @return bool
     */
    public function isSearchBySubString()
    {
        return $this->isSearchBySubstring;
    }

    /**
     * @return false|string
     */
    public function getQueryForRequestBySubString()
    {
        $q = $this->getOriginalQuery();
        if (strlen($q) > 0) {
            $this->setQueryForLikeRequest("\"$q\"");
            $this->isSearchBySubstring = true;
            return $this->getQueryForLikeRequest();
        } else {
            return false;
        }
    }

    /**
     * @param string $originalQuery
     */
    public function setOriginalQuery($originalQuery)
    {
        $this->originalQuery = $originalQuery;
        return $this;
    }
    /**
     * @return string
     */
    public function getOriginalQuery()
    {
        return $this->originalQuery;
    }

    /**
     * @param string $queryForLikeRequest
     */
    public function setQueryForLikeRequest($queryForLikeRequest)
    {
        $this->queryForLikeRequest = $queryForLikeRequest;
        return $this;
    }

    /**
     * @return string
     */
    public function getQueryForLikeRequest()
    {
        return $this->queryForLikeRequest;
    }
}