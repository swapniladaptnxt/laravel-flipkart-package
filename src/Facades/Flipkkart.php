<?php

// namespace JohnDoe\BlogPackage\Facades;

use Illuminate\Support\Facades\Facade;

class Flipkart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'flipkart';
    }
}