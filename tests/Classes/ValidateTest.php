<?php

use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase
{
    private $v;
    private $json1;
    private $json2;
    private $rule;

    protected function setUp() : void
    {
        $this->v = new Validate();
        $this->json1 = '{"foo": "123.000", "bar": "asdqwertyu", "baz": "8 (950) 288-56-23", "test":"true", "test2":"test2"}';
        $this->json2 = 'test';
        $this->rule = '{"type":"object", "items":["foo","bar","baz"], "rule":{"foo":{"type":"int"}, "bar":{"type":"string", "max_length":"10"}, "baz":{"type":"phone"}}}';
    }

    protected function tearDown() : void
    {

    }

    public function testIsJsonCorrectJson(){
        $this->assertIsBool($this->v->isJson($this->json1));
    }

    public function testIsJsonUnCorrectJson(){
        $this->assertIsBool($this->v->isJson($this->json2));
    }

    public function testVfJsonCorrectJson(){
        $data = $this->v->vfJson($this->json1, $this->rule);
        $this->assertObjectHasAttribute('code',json_decode($data));
        $this->assertObjectHasAttribute('pattern_msg',json_decode($data));
        $this->assertObjectHasAttribute('data',json_decode($data));
    }
    public function testVfJsonUnCorrectJson(){
        $data = $this->v->vfJson($this->json2, $this->rule);
        $this->assertObjectHasAttribute('code',json_decode($data));
        $this->assertObjectHasAttribute('error_msg',json_decode($data));
        $this->assertObjectHasAttribute('error_type',json_decode($data));
        $this->assertObjectHasAttribute('error_items',json_decode($data));
        $this->assertObjectHasAttribute('error_rule',json_decode($data));
    }
}