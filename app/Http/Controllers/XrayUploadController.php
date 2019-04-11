<?php

namespace App\Http\Controllers;

Use File;
use Image;
use Uploadcare;
use App\Model\Diagnosis;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

class XrayUploadController extends Controller
{
  public function store (Request $request) {
    if (!$request->ajax()) {
      abort(404);
    }
    
    $receiver = new FileReceiver('image', $request, HandlerFactory::classFromRequest($request));

    if ($receiver->isUploaded() === false) {
      throw new UploadMissingFileException();
    }

    $saver = $receiver->receive();

    if ($saver->isFinished()) {
      $image = $saver->getFile();
      $filename = $image->getClientOriginalName();
      $image->move(public_path() . '/uploads', $filename);            
      Image::make(public_path() . '/uploads/' . $filename)->fit(1024, 1024, function ($c) { $c->upsize(); })->save();
    }

    $handler = $saver->handler();
    return response()->json(['done' => $handler->getPercentageDone(), 'status' => true]);
  }

  public function analyze (Request $request) {
    $process = new Process(public_path() . '/uploads/chexnet.py ' . $request->xray);
    $process->run();

    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
    }

    $results = json_decode($process->getOutput());
    $uploadcare = new Uploadcare\Api(env('UPLOADCARE_PUBLIC_KEY'), env('UPLOADCARE_SECRET_KEY'));
    $file = $uploadcare->uploader->fromPath(public_path() . '/uploads/' . $request->xray);
    $file->store();
    File::delete(public_path() . '/uploads/' . $request->xray);
    
    $data = [];
    foreach ($results->pathologies as $pathology => $diagnosis) {
      $data[str_replace('-', '_', str_slug($pathology))] = $diagnosis->presence;
    }
    $data["slug"] = uniqid(true);
    $data["film"] = $request->xray;
    $data["film_url"] = $file->getUrl();
    $request->user()->diagnoses()->create($data);
    $results->film = $request->xray;
    $results->film_url = $file->getUrl();
    return response()->json($results);
  }
}
