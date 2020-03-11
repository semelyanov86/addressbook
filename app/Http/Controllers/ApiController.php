<?php

namespace App\Http\Controllers;

use App\Http\Resources\DataCountResource;
use App\Http\Resources\DataIndexResource;
use App\Services\DataService;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Generates collection all filtered data
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $count = (new DataService(false, false, false))->getCount();
        return DataIndexResource::collection((new DataService(request()->limit, request()->sortby, request()->sortdesc))->getData())->additional(['count'=>$count]);
    }

    /**
     * Generates and passes all available data
     * @return DataCountResource
     */
    public function countEntries()
    {
        return new DataCountResource((new DataService(false, false, false)));
    }

    /**
     * Generates and passes all headers for table
     * @return array
     */
    public function getHeaders()
    {
        return array(
            [
                'text' => 'Age',
                'value' => 'age'
            ],
            [
                'text' => 'Eye Color',
                'value' => 'eyeColor'
            ],
            [
                'text' => 'Name',
                'value' => 'name'
            ],
            [
                'text' => 'Gender',
                'value' => 'gender'
            ],
            [
                'text' => 'Company',
                'value' => 'company'
            ],
            [
                'text' => 'Email',
                'value' => 'email'
            ],
            [
                'text' => 'Phone',
                'value' => 'phone'
            ],
            [
                'text' => 'Address',
                'value' => 'address'
            ]
        );

    }
}
