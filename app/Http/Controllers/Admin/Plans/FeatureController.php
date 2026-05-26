<?php

// app/Http/Controllers/UsaMarry/Api/Admin/Plans/FeatureController.php
namespace App\Http\Controllers\Admin\Plans;

use Illuminate\Http\Request;
use App\Models\Plan\PlanFeature;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Plans\AdminFeatureStoreRequest;

class FeatureController extends Controller
{
    public function index()
    {
        return response()->json(PlanFeature::all());
    }

    public function store(AdminFeatureStoreRequest $request)
    {
        $validated = $request->validated();

        $feature = PlanFeature::create($validated);
        return response()->json($feature, 201);
    }

    public function show($id)
    {
        return response()->json(PlanFeature::findOrFail($id));
    }

    public function update(AdminFeatureStoreRequest $request, $id)
    {
        $feature = PlanFeature::findOrFail($id);

        $validated = $request->validated();

        $feature->update($validated);
        return response()->json($feature);
    }

    public function destroy($id)
    {
        $feature = PlanFeature::findOrFail($id);
        $feature->delete();

        return response()->json(['message' => 'Feature deleted']);
    }


   public function templateInputList()
{
    $features = PlanFeature::all();

    $response = $features->map(function ($feature) {
        preg_match_all('/:(\w+)/', $feature->title_template, $matches);
        $placeholders = $matches[1] ?? [];

        $inputs = collect($placeholders)->map(function ($key) use ($feature) {
            return [
                'name' => $key,
                'type' => 'text',
                'label' => ucfirst(str_replace('_', ' ', $key)),
                'placeholder' => "Enter the " . str_replace('_', ' ', $key) . " for " . str_replace('_', ' ', $feature->key),
            ];
        })->toArray();

        return [
            'key' => $feature->key,
            'title_template' => $feature->title_template,
            'inputs' => $inputs
        ];
    });

    return response()->json($response);
}






}
