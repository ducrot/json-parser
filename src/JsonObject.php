<?php


namespace TS\Json;


class JsonObject extends AbstractJsonValue
{


    public static function parseFromString($string): JsonObject
    {
        $payload = json_decode($string, false);
        if (!is_object($payload)) {
            throw new UnexpectedValueException('Unable to parse JSON object from string.');
        }
        return new JsonObject($payload, null, null);
    }


    /** @var object */
    private $data;


    /**
     * JsonObject constructor.
     * @param object $data
     * @param AbstractJsonValue|null $parent
     * @param string|int|null $parentKey
     */
    public function __construct(object $data, $parent, $parentKey)
    {
        parent::__construct($parent, $parentKey);
        $this->data = $data;
    }


    public function getPropertyNames(): array
    {
        return array_keys(get_object_vars($this->data));
    }


    /**
     * Does the JSON object have a value with the given key?
     *
     * This returns false for null.
     *
     * @param string $key
     * @return bool
     */
    public function has($key): bool
    {
        return !is_null($this->getAny($key));
    }

    /**
     * Does the JSON object have a property with the given key?
     *
     * This returns true for null.
     *
     * @param string $key
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return property_exists($this->data, $key);
    }


    protected function getAny($key)
    {
        if (!$this->hasKey($key)) {
            return null;
        }
        return $this->data->$key;
    }


}
