<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $posts = Post::all();
            $posts->each(function($post) {
                $post['comments'] = $post->getThreadedComments();
            });
            return response()->json(['error'=>false,'data'=>$posts]);
        }catch(\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:200',
                'content' => 'required|max:2000'
                ]);
            if($validator->fails()){
                return response()->json(['error'=>true,'data'=>$validator->errors(),'msg'=>'Validation errors.']);
            }else{
                $post = Post::create(['name' => $request->name,'content' => $request->content]);
                $post['comments'] = $post->getThreadedComments();
                return response()->json(['error'=>false,'data'=>$post]);
            }
        }catch(\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

}
