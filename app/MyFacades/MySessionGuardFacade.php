<?php

namespace App\MyFacades;

use Illuminate\Support\Facades\Facade;

class MySessionGuardFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'MySessionGuardFacade';
    }
}
