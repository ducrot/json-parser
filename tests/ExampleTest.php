<?php

namespace TS\Json;

use PHPUnit\Framework\TestCase;


class ExampleTest extends TestCase
{


    public function testFirst()
    {
        $json = '{"name":"hello", "tagIds":[1,2,3], "assetId": 559}';

        $payload = JsonObject::parseFromString($json);

        $name = $payload->getString('name');
        $tagIds = $payload->getIntArray('tagIds');
        $assetId = $payload->getInt('assetId');

        $this->assertSame('hello', $name);
        $this->assertSame([1, 2, 3], $tagIds);
        $this->assertSame(559, $assetId);
    }


    public function testSecond()
    {
        $json = '{ "type":"parent", "children":[{"name":"peter"}] }';

        $payload = JsonObject::parseFromString($json);
        $children = $payload->getArray('children');
        $firstChild = $children->getObject(0);

        $this->expectExceptionMessage('Expected int value at children[0].age, but property is undefined');
        $firstChild->getInt('age');
    }


}
