<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Archieve;
use App\Models\Tag;
use App\Models\ArchieveTag;

class TagController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getTag']]);
    }

    /**
     * Add new tag
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTag(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
            'name' => 'string|min:2|max:12'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $archieve = Archieve::getArchieveById($request->id);
        if (!$archieve) {
            return response()->json("File not exists.", 404);
        }

        $tag_id = Tag::getTagId($request->name);
        $archieve_tag = ArchieveTag::createArchieveTag($archieve->id, $tag_id, auth()->user()->id);

        return response()->json($archieve_tag);
    }

    /**
     * Delete tag
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTag(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
            'name' => 'string|min:2|max:12'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $archieve = Archieve::getArchieveById($request->id);
        if (!$archieve) {
            return response()->json("File not exists.", 404);
        }

        $tag_id = Tag::getTagId($request->name);
        $archieve_tag = ArchieveTag::checkTag($archieve->id, $tag_id);
        if (!$archieve_tag) {
            return response()->json("Tag not exists.", 404);
        }
        if ($archieve_tag->uid != auth()->user()->id) {
            return response()->json("Permission denied.", 403);
        }

        $archieve_tag->delete();
        return response()->json("Remove tag succeeded.");
    }

    /**
     * Delete tag
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTag(Request $request)
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

        $tags = ArchieveTag::where('archieve_id', $archieve->id)
            ->join('tags', "archieve_tags.tag_id", "=", 'tags.id')
            ->select('tags.id', 'tags.name')
            ->get();

        return response()->json($tags);
    }
}
