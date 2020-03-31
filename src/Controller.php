<?php
declare(strict_types = 1);

namespace App;

class Controller
{
    public function index()
    {
        // test data
        $json   = '{"foo": 123, "bar": "asdqwertyu", "baz": "8 (950) 288-56-23"}';
        $schema = '{"foo":{"type":"integer"}, "bar":{"type":"string", "max":"10"}, "baz":{"type":"phone"}}';

        $resultArr = json_decode($json, true);

        $validator = new Validator(json_decode($schema, true));
        $validator->validate($resultArr);

        return json_decode($json);

    }
}
