<?php

namespace App\Http\Controllers;

use File;
use Image;
use Uploadcare;
use Illuminate\Http\Request;
use App\Http\Requests\ImageUploadRequest;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;

class ImageUploadController extends Controller
{
  public function store (Request $request) {
    if (!$request->ajax()) {
      abort(404);
    }

    $receiver = new FileReceiver('image', $request, HandlerFactory::classFromRequest($request));
    $uploadcare = new Uploadcare\Api(env('UPLOADCARE_PUBLIC_KEY'), env('UPLOADCARE_SECRET_KEY'));

    if ($receiver->isUploaded() === false) {
      throw new UploadMissingFileException();
    }

    $saver = $receiver->receive();

    if ($saver->isFinished()) {
      $image = $saver->getFile();
      $filename = $image->getClientOriginalName();
      $image->move(public_path() . '/uploads', $filename);
      Image::make(public_path() . '/uploads/' . $filename)->fit(150, 150, function ($c) { $c->upsize(); })->save();

      $file = $uploadcare->uploader->fromPath(public_path() . '/uploads/' . $filename);
      $file->store();
      File::delete(public_path() . '/uploads/' . $filename);

      $img_url = $file->getUrl();
      $request->user()->image = $img_url;
      $request->user()->save();
      return response()->json(['url' => $img_url, 'status' => 'Profile image updated.'], 200);
    }

    $handler = $saver->handler();
    return response()->json(['done' => $handler->getPercentageDone(), 'status' => true]);
  }
}
