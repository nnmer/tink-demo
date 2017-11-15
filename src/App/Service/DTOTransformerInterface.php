<?php

namespace App\Service;

interface DTOTransformerInterface
{
    /**
     * Transform Array Data to Object
     *
     * @param $data
     * @param $object
     * @return mixed
     */
    public function transform($data, $object);

    /**
     * Transform Object to Array Data
     * @return array
     */
    public function reverse($object);
}