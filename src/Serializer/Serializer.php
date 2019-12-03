<?php

namespace App\Serializer;

class Serializer
{
public function __invoke($object)
{
return $object->id;
}
}