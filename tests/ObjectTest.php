<?php

namespace TS\Json;

use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;


class ObjectTest extends TestCase
{


    public function testParse(): JsonObject
    {
        $json = '{"str":"string", "int":123, "float":0.75, "bool":true, "null": null, "arr": [1,2,3], "obj": {"foo":"bar"}}';
        $object = JsonObject::parseFromString($json);
        $this->assertNotNull($object);
        return $object;
    }


    /**
     * @depends testParse
     * @param JsonObject $object
     */
    public function testGetPropertyNames(JsonObject $object)
    {
        $names = $object->getPropertyNames();
        $this->assertSame(['str', 'int', 'float', 'bool', 'null', 'arr', 'obj'], $names);
    }



    /**
     * @depends testParse
     * @param JsonObject $object
     */
    public function testHas(JsonObject $object)
    {
        $this->assertTrue($object->has('str'));
        $this->assertFalse($object->has('xxx'));
        $this->assertFalse($object->has(1));
    }


    /**
     * @depends testParse
     * @param JsonObject $object
     */
    public function testGetString(JsonObject $object)
    {
        $val = $object->getString('str');
        $this->assertSame('string', $val);
    }


    /**
     * @depends testParse
     * @param JsonObject $object
     */
    public function testGetStringUndefined(JsonObject $object)
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Expected string value at xxx');
        $object->getString('xxx');
    }


    /**
     * @depends testParse
     * @param JsonObject $object
     */
    public function testGetNumber(JsonObject $object)
    {
        $val = $object->getNumber('int');
        $this->assertSame(123, $val);
    }


    /**
     * @depends testParse
     * @param JsonObject $object
     */
    public function testGetArray(JsonObject $object)
    {
        $val = $object->getArray('arr');
        $this->assertCount(3, $val);
    }


    public function testParseInvalidJson()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse JSON object');
        JsonObject::parseFromString('x');
    }


    public function testParseJsonArray()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse JSON object');
        JsonObject::parseFromString('["a"]');
    }


    public function testParseJsonScalar()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Unable to parse JSON object');
        JsonObject::parseFromString('true');
    }


}
