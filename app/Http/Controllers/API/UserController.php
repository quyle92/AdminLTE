<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::orderBy('created_at', 'asc')->paginate(10);
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
        request()['password'] = Str::random(5);
        request()['type'] = 'user';

        $this->validate($request, [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:2',
        'type' => 'required',
        //'photo' => 'required|numeric|gt:0',
        ]);
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);

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
    public function show($id)
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
        $this->validate($request, [
        'name' => 'required',
        'email' => 'required','email', Rule::unique('users, email')->ignore($id),//(1)
        'type' => 'required',
        //'photo' => 'required|numeric|gt:0',
        ]);
        
        $user = User::findOrFail($id);
        $data = $request->all();
        $data['password'] = Hash::make($request->password);//dd($data);
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
        $user = User::find($id);
        $user->delete();
        
        return User::orderBy('created_at', 'desc')->paginate(3);
    }
}

/*
Note
 */
//(1)option 2 (shorter):
// 'email' => 'required|email|unique:users,email,' . $id;