<?php 
namespace App\MyFacades;
use Illuminate\Support\Facades\Auth;

class MySessionGuard {

       	public function myOnceBasic($field = 'name', $extraConditions = [])
	    {	
	        Auth::myOnceBasic($field = 'name', $extraConditions = []);
	    }

}