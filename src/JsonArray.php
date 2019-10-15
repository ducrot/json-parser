<?php


namespace TS\Json;


use Countable;


class JsonArray extends AbstractJsonValue implements Countable
{


    public static function parseFromString($string): JsonArray
    {
        $payload = json_decode($string, false);
        if (!is_array($payload)) {
            throw new UnexpectedValueException('Unable to parse JSON array from string.');
        }
        return new JsonArray($payload, null, null);
    }


    /** @var array */
    private $data;


    /**
     * JsonArray constructor.
     * @param array $data
     * @param AbstractJsonValue|null $parent
     * @param string|int|null $parentKey
     */
    public function __construct(array $data, $parent, $parentKey)
    {
        parent::__construct($parent, $parentKey);
        $this->data = $data;
    }


    public function count(): int
    {
        return count($this->data);
    }


    public function has($key): bool
    {
        return array_key_exists($key, $this->data);
    }


    protected function getAny($key)
    {
        if (!$this->has($key)) {
            return null;
        }
        return $this->data[$key];
    }


}
