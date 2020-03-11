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

    /**
     * DataService constructor.
     * @param $limit - number of limit entities
     * @param $sortby - field for sorting
     * @param $sortdesc - is descending order
     */
    public function __construct($limit, $sortby, $sortdesc)
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
     * @return array
     */
    public function getData() : array
    {
        $items = $this->getJsonItems();
        if ($this->sortby) {
            if ($this->sortdesc) {
                $items = $items->sortByDesc($this->sortby);
            } else {
                $items = $items->sortBy($this->sortby);
            }
        }
        $data = $items->take($this->limit)->values()->all();
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
        $jsonString = file_get_contents(base_path($this->getFile()));
        $jsonData = collect(json_decode($jsonString, true));
        $items = $jsonData->flatten(2)->filter(function($value) {
            return $value['isActive'];
        });
        return $items;
    }
}
