<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class DiagnosisTransformer extends TransformerAbstract
{
  public function transform($diagnosis)
	{
    $response = [
      'slug' => $diagnosis->slug,
      'film' => $diagnosis->film,
      'film_url' => $diagnosis->film_url,
      'atelectasis' => $diagnosis->atelectasis,
      'cardiomegaly' => $diagnosis->cardiomegaly,
      'effusion' => $diagnosis->effusion,
      'infiltration' => $diagnosis->infiltration,
      'mass' => $diagnosis->mass,
      'nodule' => $diagnosis->nodule,
      'pneumonia' => $diagnosis->pneumonia,
      'consoliation' => $diagnosis->consolidation,
      'edema' => $diagnosis->edema,
      'emphysema' => $diagnosis->emphysema,
      'fibrosis' => $diagnosis->fibrosis,
      'pleural_thickening' => $diagnosis->pleural_thickening,
      'hernia' => $diagnosis->hernia,
      'date' => $diagnosis->created_at->toDayDateTimeString(),
    ];

		$etiology = [
			"pathology" => "", 
			"presence" => 0.0
		];

		foreach($response as $pathology => $presence) {
			if ($pathology != 'date' &&
					$pathology != 'slug' &&
					$pathology != 'film' &&
					$pathology != 'film_url' &&
					$etiology["presence"] < $presence) 
			{
				$etiology["pathology"] = ucfirst(str_replace('_' , ' ', $pathology));
				$etiology["presence"] = $presence;
			}
		}

		$response["diagnosis"] = $etiology;
		return $response;
  }
}
