<?php

namespace App\Http\Controllers;

use App\Services\Upload\ImageUploadService;
use Illuminate\Http\Request;

class UploadController extends Controller
{

    public function __construct(
        private ImageUploadService $imageUploadService,
    ) {}

    public function upload(Request $request)
    {
        return $this->imageUploadService->handle($request);
    }
}
