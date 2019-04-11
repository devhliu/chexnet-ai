<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiagnosesTable extends Migration
{
  public function up()
  {
    Schema::create('diagnoses', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->string("slug");
      $table->string('film');
      $table->string('film_url')->url()->default('https://ucarecdn.com/640e7ab5-e3c6-46e6-9b77-b803a9bc4ff1/');
      $table->float('atelectasis', 2, 1);
      $table->float('cardiomegaly', 2, 1);
      $table->float('effusion', 2, 1);
      $table->float('infiltration', 2, 1);
      $table->float('mass', 2, 1);
      $table->float('nodule', 2, 1);
      $table->float('pneumonia', 2, 1);
      $table->float('consolidation', 2, 1);
      $table->float('edema', 2, 1);
      $table->float('emphysema', 2, 1);
      $table->float('fibrosis', 2, 1);
      $table->float('pleural_thickening', 2, 1);
      $table->float('hernia', 2, 1);
      $table->timestamps();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('diagnoses');
  }
}
