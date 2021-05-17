<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserController extends Controller
{   
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   
         //$this->middleware('auth.basic.once');
         $this->middleware('auth:api');
         $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return User::with('role')->orderBy('created_at', 'asc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $request['name'] = Str::random(5);
        request()['email'] = $request->name . '@gmail.com';
        request()['password'] = '123';
        request()['role_id'] = intval($request->role_id);
       
        $this->validate($request, [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:2',
        'role_id' => 'required|numeric|gt:0',
        ]);
        $data = $request->all();  
        $data['password'] = Hash::make($request->password);//dd( $data );
        $user = User::create($data);
        $user['roleName'] = $user->role->roleName;

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProfile()
    {
        return auth('api')->user();
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
        $this->validate( $request, [
            'name' => 'required',
            'email' => 'required','email', Rule::unique('users, email')->ignore($id),//(1)
            'role_id' => 'required',
        ]);
        

        $user = User::find($id);
        $data = $request->all();// dd($data);
        $user->update([
            'name'=> $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);
        $user['roleName'] = $user->role->roleName;
        return response()->json([
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {  //dd($request->all());
        $this->validate( $request, [
            'name' => 'required',
            'email' => 'required','email', Rule::unique('users, email')->ignore( auth('api')->user()->id ),//(1)
            'role_id' => 'required',
        ]);
        
        //nếu có user upload photo mới thì update ko thì thôi
        $currentPhoto = auth('api')->user()->photo;
        if( request()->photo !== $currentPhoto )
        {    
            File::delete(  public_path() . '/uploads/' .$currentPhoto );   
            $this->validate( $request, [
                'photo' =>  'image|mimes:jpeg,png,jpg,gif,svg|max:2048',//(2)
            ]);
            $picName = $request->photo->getClientOriginalName();
            $request->photo->move( public_path('uploads'), $picName ); 
        }

        $user = auth('api')->user();
        $data = $request->all();
        $data['photo'] = $picName ?? $currentPhoto;
        $user->update($data);

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        if (Gate::allows('delete')) {
        $user = User::find($id);
        $user->delete();
        
        return User::orderBy('created_at', 'desc')->paginate(3);
        }
    }
}

/*
Note
 */
//(1)option 2 (shorter):
// 'email' => 'required|email|unique:users,email,' . $id;
// (2): if having error while validating image, install "composer require symfony/mime 5.1". Ref: https://github.com/laravel/framework/issues/35417

