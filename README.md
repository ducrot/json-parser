JSON parser
===========

Simple API to read JSON objects. Not a serializer.

No serializer available? Tired of writing verbose code like below?

````php
// json: '{"name":"hello", "tagIds":[1,2,3], "assetId": 559}'

$payload = json_decode($request->getContent(), true);
if (!is_array($payload)) {
    throw new BadRequestHttpException('Unable to parse JSON from request body.');
}

if (!array_key_exists('name', $payload)) {
    throw new \OutOfBoundsException();
}
if (!is_string($payload['name'])) {
    throw new \UnexpectedValueException();
}
$name = $payload['name'];

if (!array_key_exists('tagIds', $payload)) {
    throw new \OutOfBoundsException();
}
if (!is_array($payload['tagIds'])) {
    throw new \UnexpectedValueException();
}
$tagIds = $payload['tagIds'];

if (!array_key_exists('assetId', $payload)) {
    throw new \OutOfBoundsException();
}
if (!is_int($payload['assetId'])) {
    throw new \UnexpectedValueException();
}
$assetId = $payload['assetId'];
````


Use the slightly less terrible API provided by this library:

````php
// json: '{"name":"hello", "tagIds":[1,2,3], "assetId": 559}'

$payload = JsonObject::parseFromString($request->getContent());
$name = $payload->getString('name');
$tagIds = $payload->getIntArray('tagIds');
$assetId = $payload->getInt('assetId');
````

Nested objects work as well:

````php
// json: '{ "type":"parent", "children":[{"name":"peter"}] }'

$children = $payload->getArray('children');
$firstChild = $children->getObject(0);

// raises exception "Expected int value at children[0].age, but property is undefined"
$firstChild->getInt('age');
````

