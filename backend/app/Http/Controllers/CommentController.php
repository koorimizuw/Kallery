<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Archieve;
use App\Models\Comment;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getComment']]);
    }

    /**
     * Delete tag
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
            'text' => 'required|string|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $archieve = Archieve::getArchieveById($request->id);
        if (!$archieve) {
            return response()->json("File not exists.", 404);
        }

        $new_comment = Comment::create(array(
            "archieve_id" => $archieve->id,
            "uid" => auth()->user()->id,
            "text" => $request->text,
        ));

        return response()->json("Add comment succeeded.");
    }

    /**
     * Delete tag
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComment(Request $request)
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

        $comments = Comment::where('archieve_id', $archieve->id)
            ->join('users', "comments.uid", "=", 'users.id')
            ->select('comments.uid', 'users.name', 'comments.text')
            ->get();

        return response()->json($comments);
    }
}
