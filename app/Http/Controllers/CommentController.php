<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function replyComment(Request $request)
    {
        try {
            $post = Post::where('id', '=', $request->post_id)->first();
            if($request->get('depth')){
                $depth = $request->get('depth')+1;
            }
            else{
                $depth = 1;
            }
            $post->addComment([
                'name' => $request->name,
                'comment' => $request->content,
                'parent_id' => $request->get('parent_id', null),
                'depth' => $depth
                ]);

            return response()->json(['error'=>false,'data'=>$post->getThreadedComments()]);

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }
}
