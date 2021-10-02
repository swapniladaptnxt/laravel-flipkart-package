<?php
namespace Adaptnxt\Flipkart;


use Illuminate\Support\Facades\Facade;

class FlipkartLocalFacade extends Facade {
    protected static function getFacadeAccessor()
    {
        return 'flipkart-local';
    }
}