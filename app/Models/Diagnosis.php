<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
  protected $fillable = [
    "slug",
    "film",
    "atelectasis",
    "cardiomegaly",
    "effusion",
    "infiltration",
    "mass",
    "nodule",
    "pneumonia",
    "consolidation",
    "edema",
    "emphysema",
    "fibrosis",
    "pleural_thickening",
    "hernia"
  ];

  public function user () {
    return $this->belongsTo(User::class);
  }

  public function scopelatestFirst ($query)  {
    return $query->orderBy("created_at", "DESC");
  }
}
