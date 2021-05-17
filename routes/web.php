<?php
//dd(get_class(resolve('db')));//(1)
//dd(get_class(Password::getFacadeRoot()));//(1)

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/getUser', 'HomeController@getUser');
Route::delete('/delUser', 'HomeController@delUser');
Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/foo', function (){
	return view('home');
})->middleware('auth.basic');

Route::get('/bar', function (){

	return 'bar';
})->middleware('auth.basic.once');


Route::get('{slug}', 'HomeController@index')->where('path', '([A-z\d\/.\-_]+)?');

/*
Note
 */
//(1) this is to find the underlying class of a Facade based on IoC key or Facade name
