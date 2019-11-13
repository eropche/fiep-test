<?
require 'vendor/autoload.php';
$test_json = '{"foo": "123.000", "bar": "asdqwertyu", "baz": "8 (950) 288-56-23", "test":"true", "test2":"test2"}';
//$test_json = ["foo" => "123.000", "bar" => "asdqwertyu", "baz" => "8 (950) 288-56-23", "test"=>"true", "test2"=>"test2"];

$json_schema = '{"type":"object", "items":["foo","bar","baz"], "rule":{"foo":{"type":"int"}, "bar":{"type":"string", "max_length":"10"}, "baz":{"type":"phone"}}}';

$v = new Validate();

if (is_array($test_json)){
    print_r($test_json);
    echo "</br>";
    echo($v->isJson($test_json) ? " JSON is Valid" : " JSON is Not Valid");
    echo "Result for json_schema: " . $json_schema;
    echo($v->isJson($json_schema) ? " JSON is Valid" : " JSON is Not Valid");
    echo "</br>";
    echo "<pre>" . json_encode(json_decode($v->vfJson($test_json, $json_schema)), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "</pre>";
} else {
    echo "Result for test_json: " . $test_json;
    echo($v->isJson($test_json) ? " JSON is Valid" : " JSON is Not Valid");
    echo "</br>";
    echo "<pre>" . json_encode(json_decode($test_json), JSON_PRETTY_PRINT) . "</pre>";
    echo "</br>";
    echo "Result for json_schema: " . $json_schema;
    echo($v->isJson($json_schema) ? " JSON is Valid" : " JSON is Not Valid");
    echo "</br>";
    echo "<pre>" . json_encode(json_decode($json_schema), JSON_PRETTY_PRINT) . "</pre>";
    echo "</br>";
    echo "<pre>" . json_encode(json_decode($v->vfJson($test_json, $json_schema)), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "</pre>";
}



