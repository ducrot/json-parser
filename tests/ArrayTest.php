<?php

namespace TS\Json;

use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;


class ArrayTest extends TestCase
{


    public function testParse(): JsonArray
    {
        $json = '["a", 123, true, ["x", "y"], {"foo":"bar"}]';
        $array = JsonArray::parseFromString($json);
        $this->assertNotNull($array);
        return $array;
    }

    /**
     * @depends testParse
     * @param JsonArray $array
     */
    public function testCount(JsonArray $array)
    {
        $this->assertCount(5, $array);
    }

    /**
     * @depends testParse
     * @param JsonArray $array
     */
    public function testHas(JsonArray $array)
    {
        $this->assertTrue($array->has(0));
        $this->assertFalse($array->has(99));
        $this->assertFalse($array->has("xx"));
    }


    /**
     * @depends testParse
     * @param JsonArray $array
     */
    public function testGetString(JsonArray $array)
    {
        $val = $array->getString(0);
        $this->assertSame('a', $val);
    }


    /**
     * @depends testParse
     * @param JsonArray $array
     */
    public function testGetStringUndefined(JsonArray $array)
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Expected string value at 99');
        $array->getString(99);
    }


    /**
     * @depends testParse
     * @param JsonArray $array
     */
    public function testGetNumber(JsonArray $array)
    {
        $val = $array->getNumber(1);
        $this->assertSame(123, $val);
    }


    /**
     * @depends testParse
     * @param JsonArray $array
     */
    public function testGetArray(JsonArray $array)
    {
        $val = $array->getArray(3);
        $this->assertCount(2, $val);
    }


    public function testGetStringArray()
    {
        $json = '{"arr":["a", "b", "c"]}';
        $object = JsonObject::parseFromString($json);
        $arr = $object->getStringArray('arr');
        $this->assertIsArray($arr);
    }


    public function testGetNumberArray()
    {
        $json = '{"arr":[1, 5.6]}';
        $object = JsonObject::parseFromString($json);
        $arr = $object->getNumberArray('arr');
        $this->assertIsArray($arr);
    }


    /**
     * @depends testParse
     * @param JsonArray $array
     */
    public function testGetObject(JsonArray $array)
    {
        $val = $array->getObject(4);
        $this->assertCount(1, $val->getPropertyNames());
    }


    public function testParseInvalidJson()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse JSON array');
        JsonArray::parseFromString('x');
    }


    public function testParseJsonArray()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse JSON array');
        JsonArray::parseFromString('{}');
    }


    public function testParseJsonScalar()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse JSON array');
        JsonArray::parseFromString('true');
    }


}
