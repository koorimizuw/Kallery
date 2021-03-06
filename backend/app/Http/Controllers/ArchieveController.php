<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Archieve;
use Image;

class ArchieveController extends Controller
{
    /**
     * Create a new AuthController instance.
     * Email and password are required (data source users table)
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['file', 'all', 'detail']]);
    }

    /**
     * Add new post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'file' => 'required|image|mimes:jpg,jpeg,png,gif,svg',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->user();

        $file = $request->file('file');
        $resizePath = storage_path('app/public/resize');
        if (!file_exists($resizePath)) {
            mkdir($resizePath, 666, true);
        }
        $originalPath = "/public/original";

        // Create resized image file
        $imgFile = Image::make($file->getRealPath());
        $imgFile->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio();
        })->save($resizePath . '/' . $file->hashName());

        $storage = Archieve::create(array(
            "uid" => $user->id,
            "title" => $request->title,
            "description" => $request->description,
            "mime_type" => $file->getMimeType(),
            "archieve_name" => $file->hashName(),
            "extension" => $file->extension(),
        ));

        Storage::disk('local')->put($originalPath . '/' . $file->hashName(), $file->getContent());

        return response()->json($storage);
    }

    /**
     * Add new post
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function file(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
            'original' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $archieve = Archieve::getArchieveById($request->id);
        if (!$archieve) {
            return response()->json("File not exists.", 404);
        }

        $target = $request->original == 1 ? 'original/' : 'resize/';
        $path = storage_path('app/public/' . $target . $archieve->archieve_name);
        if (!file_exists($path)) {
            return response()->json("File not exist.", 404);
        }

        return response()->download($path);
    }

    /**
     * Get all file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        $list = Archieve::select('id', 'title')->get();
        return response()->json($list);
    }

    /**
     * Get detail
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $archieve = Archieve::getArchieveById($request->id);
        if (!$archieve) {
            return response()->json("File not exists.", 404);
        }

        return response()->json(array(
            "id" => $archieve->id,
            "title" => $archieve->title,
            "description" => $archieve->description,
        ));
    }
}
