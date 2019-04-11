<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use Illuminate\Http\Request;
use App\Transformers\DiagnosisTransformer;

class DiagnosisController extends Controller
{
  public function history (Request $request) {
    if (!$request->ajax()) {
      abort(404);
    }

    $diagnoses = $request->user()->diagnoses()->latestFirst()->get();

    return response()->json(fractal()->collection($diagnoses)
      ->transformWith(new DiagnosisTransformer)
      ->toArray()
    );
  }

  public function delete (Request $request) {
    if (!$request->ajax()) {
      abort(404);
    }
    $diagnosis = Diagnosis::where('slug', $request->slug)->firstOrFail();
    $diagnosis->delete();
    return response()->json(['status' => 'Diagnosis removed.']);
  }
}
