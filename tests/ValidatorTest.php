<?php

use PHPUnit\Framework\TestCase;
use App\Validator;

class ValidatorTest extends TestCase
{
    private $validator;

    protected function setUp() : void
    {
        $schema          = '{"foo":{"type":"integer"}, "bar":{"type":"string", "max":"10"}, "baz":{"type":"phone"}}';
        $this->validator = new Validator(json_decode($schema, true));
    }

    public function testIsValid(){
        $json = '{"foo": 123, "bar": "asdqwertyu", "baz": "8 (950) 288-56-23"}';
        $this->validator->validate(json_decode($json, true));
        $this->assertTrue(true);
    }

    public function testInvalidInteger(){
        $json = '{"foo": "123ddd", "bar": "asdqwertyu", "baz": "8 (950) 288-56-23"}';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Wrong type specified for `foo`. Expected integer, string given.');
        $this->validator->validate(json_decode($json, true));
    }

    public function testInvalidString(){
        $json = '{"foo": 123, "bar": 123.9, "baz": "8 (950) 288-56-23"}';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Wrong type specified for `bar`. Expected string, double given.');
        $this->validator->validate(json_decode($json, true));
    }

    public function testInvalidPhone(){
        $json = '{"foo": 123, "bar": "asdqwertyu", "baz": "260557"}';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Parameter baz not a valid phone. 260557 given');
        $this->validator->validate(json_decode($json, true));
    }
}
