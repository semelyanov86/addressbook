<?php

namespace App\Services;
use Illuminate\Support\Collection;

/**
 * Class DataService
 * @package App\Services
 */
class DataService
{
    private $sortby;
    private $sortdesc;
    private $limit;
    private $jsonItems;

    /**
     * DataService constructor.
     * @param $limit - number of limit entities
     * @param $sortby - field for sorting
     * @param $sortdesc - is descending order
     */
    public function __construct($limit = false, $sortby = false, $sortdesc = false)
    {
        $this->sortby = $sortby;
        $this->sortdesc = $sortdesc;
        $this->limit = $limit;
    }

    /**
     * Getting file path
     * @return \Illuminate\Config\Repository|mixed
     */
    private function getFile() : string
    {
        return config('app.jsonpath');
    }

    /**
     * Main function to get all filtered and sortable data
     * @param int $limit
     * @return array
     */
    public function getData($limit = 0) : array
    {
        if ($limit > 0) {
            $this->limit = $limit;
        }
        $items = $this->getJsonItems();
        if ($this->sortby) {
            if ($this->sortdesc) {
                $items = $items->sortByDesc($this->sortby);
            } else {
                $items = $items->sortBy($this->sortby);
            }
        }
        if ($this->limit) {
            $items = $items->take($this->limit);
        }
        $data = $items->values()->all();
        return $data;
    }

    /**
     * Calculates all data in json
     * @return int
     */
    public function getCount() : int
    {
        $items = $this->getJsonItems();
        return $items->count();
    }

    /**
     * Getting collection of items from json
     * @return \Illuminate\Support\Collection
     */
    public function getJsonItems() : Collection
    {
        if (!$this->jsonItems) {
            $jsonString = file_get_contents(base_path($this->getFile()));
            $jsonData = collect(json_decode($jsonString, true));
            $items = $jsonData->flatten(2)->filter(function($value) {
                return $value['isActive'];
            });
            $this->jsonItems = $items;
        }
        return $this->jsonItems;
    }
}
