<?php

namespace App\Http\Controllers\web;

use App\Models\ExercisesImages;
use Illuminate\Http\Request;

class ExercisesImagesController extends BaseController
{

    public function index($exerciseId)
    {
        $images = ExercisesImages::where('exerciseId', $exerciseId)
            ->where(function ($query) {
                $query->whereNull('userId')
                    ->orWhere('availability', 'public');
            })->get();

        return $this->responseJson($images);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
