<?php

namespace nailfor\shazam\API\Exceptions;

use Exception;

class EmptyModelException extends Exception
{
    protected $message = 'Empty model';
}
