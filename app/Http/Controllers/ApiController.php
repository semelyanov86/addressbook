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
        $dataModel = new DataService(request()->limit, request()->sortby, request()->sortdesc);
        $count = $dataModel->getCount();
        return DataIndexResource::collection($dataModel->getData())->additional(['count'=>$count]);
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
