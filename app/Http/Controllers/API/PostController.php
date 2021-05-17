<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class PostController extends Controller
{   
    public function __construct()
    {   
         //$this->middleware('auth.basic.once');
         //$this->middleware('auth:api');
         //$this->authorizeResource(Post::class, 'post');(1)
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return Post::orderBy('created_at', 'desc')->paginate(5);
         //if( empty(request()->term)  ){
         //    return Post::orderBy('created_at', 'desc')->paginate(5);
         //}
         //else{
         //    $this->search();//(2)
         //}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->authorize('create', Post::class);
        $request['title'] = Str::random(15);
        $request['user_id'] = Auth::user()->id;

        $this->validate($request, [
        'title' => 'required',
        ]);

        $data = $request->all();
        $post = Post::create($data);

        return response()->json([
            'post' => $post
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {       
            $post = Post::find($id);
            $this->authorize('update',  $post);
            $request['title'] = Str::random(15);
            $request['user_id'] = Auth::user()->id;

            $this->validate($request, [
            'title' => 'required',
            ]);

            
            $data = $request->all();
            $post->update($data);

            return response()->json([
                'post' => $post
            ]);

            //}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $post = Post::find($id);
        $this->authorize('delete',  $post);
        
        $post->delete();
        
        return Post::orderBy('created_at', 'desc')->paginate(3);
    }

    public function search(){
        
        $term = request()->term;//dd($term);
        $post = Post::where('title', 'like', "%$term%")->paginate(5);
        //dd($post);
        return $post;

    }
}

/*Note*/
//(1) cách 2( Authorizing Resource Controllers ).Ref: https://laravel.com/docs/5.8/authorization (Authorizing Resource Controllers)
//(2): you cannot send a response from a method that is not the controller method. Ref: https://stackoverflow.com/questions/58993077/is-possible-to-send-a-response-from-a-return-chain-in-laravel-api-without-usin,
//https://stackoverflow.com/questions/38554809/how-to-send-a-response-from-a-method-that-is-not-the-controller-method