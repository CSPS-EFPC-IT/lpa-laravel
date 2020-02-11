<?php

namespace App\Http\Controllers;

use App\Http\Requests\LearningProductCodeFormRequest;
use App\Models\LearningProduct\LearningProductCode;
use Illuminate\Http\Request;

class LearningProductCodeController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respond(
            LearningProductCode::with('updatedBy', 'createdBy')->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', LearningProductCode::class);

        return $this->respondNoContent();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  LearningProductCodeFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(LearningProductCodeFormRequest $request)
    {
        // Retrieve only the necessary attributes from the request.
        $data = $request->only([
            'code',
            'active',
            'comments',
        ]);

        // Add audit information.
        $audit = [
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ];

        return $this->respond(
            LearningProductCode::create(array_merge($data, $audit))
        );
    }
    /**
     * Display the specified resource.
     *
     * @param  LearningProductCode  $learningProduct
     * @return \Illuminate\Http\Response
     */
    public function show(LearningProductCode $learningProductCode)
    {
        return $this->respond(
            $learningProductCode->load(['createdBy', 'updatedBy'])
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  LearningProductCode  $learningProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(LearningProductCode $learningProductCode)
    {
        $this->authorize('update', $learningProductCode);

        return $this->respondNoContent();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  LearningProductCodeFormRequest $request
     * @param  LearningProductCode $learningProductCode
     * @return \Illuminate\Http\Response
     */
    public function update(LearningProductCodeFormRequest $request, $int)
    {
        // Retrieve only the necessary attributes from the request.
        $data = $request->only([
            'code',
            'active',
            'comments',
        ]);

        $learningProductCode = LearningProductCode::findOrFail($int);
        $learningProductCode->update($data);
        $learningProductCode->updateAudit();

        return $this->respond(
            $learningProductCode
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Project $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(LearningProductCode $learningProductCode)
    {
        $this->authorize('delete', $learningProductCode);

        return $this->respond([
            'deleted' => $learningProductCode->delete(),
        ]);
    }
}
