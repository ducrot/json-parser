<?php

namespace TS\Json;

use PHPUnit\Framework\TestCase;


class ExampleTest extends TestCase
{


    public function testParse()
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


}
