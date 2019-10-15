<?php


namespace TS\Json;


use LogicException;


abstract class AbstractJsonValue
{


    /** @var AbstractJsonValue|null */
    private $parent;

    /** @var string|int|null */
    private $thisKey;


    /**
     * @param AbstractJsonValue|null $parent
     * @param string|int|null $thisKey
     */
    public function __construct($parent, $thisKey)
    {
        $this->parent = $parent;
        $this->thisKey = $thisKey;
    }


    abstract public function has($key): bool;


    protected function makePath($key = null): string
    {
        $path = $this->parent ? $this->parent->makePath($this->thisKey) : '';
        if (!is_null($key) && $this instanceof JsonArray) {
            $path .= sprintf('[%s]', $key);
        }
        if (!is_null($key) && $this instanceof JsonObject) {
            if (strlen($path) > 0) {
                $path .= '.';
            }
            $path .= $key;
        }
        return $path;
    }


    abstract protected function getAny($key);


    private function expect($key, $type)
    {
        if (!$this->has($key)) {
            $msg = sprintf('Expected %s value at %s, but property is undefined.', $type, $this->makePath($key));
            throw new OutOfBoundsException($msg);
        }

        $value = $this->getAny($key);

        if (!$this->isType($value, $type)) {
            $msg = sprintf('Expected %s value at %s, but got %s.', $type, $this->makePath($key), gettype($value));
            throw new UnexpectedValueException($msg);
        }

        return $value;
    }


    private function isType($value, $type): bool
    {
        switch ($type) {
            case 'bool':
                return is_bool($value);
            case 'string':
                return is_string($value);
            case 'int':
                return is_int($value);
            case 'float':
                return is_float($value);
            case 'number':
                return is_float($value) || is_int($value);
            case 'array':
                return is_array($value);
            case 'string[]':
                if (!is_array($value)) {
                    return false;
                }
                foreach (array_values($value) as $v) {
                    if (!is_string($v)) {
                        return false;
                    }
                }
                return true;
            case 'bool[]':
                if (!is_array($value)) {
                    return false;
                }
                foreach (array_values($value) as $v) {
                    if (!is_bool($v)) {
                        return false;
                    }
                }
                return true;
            case 'int[]':
                if (!is_array($value)) {
                    return false;
                }
                foreach (array_values($value) as $v) {
                    if (!is_int($v)) {
                        return false;
                    }
                }
                return true;
            case 'float[]':
                if (!is_array($value)) {
                    return false;
                }
                foreach (array_values($value) as $v) {
                    if (!is_float($v)) {
                        return false;
                    }
                }
                return true;
            case 'number[]':
                if (!is_array($value)) {
                    return false;
                }
                foreach (array_values($value) as $v) {
                    if (!is_int($v) && !is_float($v)) {
                        return false;
                    }
                }
                return true;
            case 'object':
                return is_object($value);
            case 'null':
                return is_null($value);
            default:
                throw new LogicException();
        }
    }


    public function getString($key): string
    {
        return $this->expect($key, 'string');
    }

    public function getBool($key): bool
    {
        return $this->expect($key, 'bool');
    }

    public function getInt($key): int
    {
        return $this->expect($key, 'int');
    }

    public function getFloat($key): float
    {
        return $this->expect($key, 'float');
    }

    /**
     * @param $key
     * @return int|float
     */
    public function getNumber($key)
    {
        return $this->expect($key, 'number');
    }

    public function getArray($key): JsonArray
    {
        $arr = $this->expect($key, 'array');
        return new JsonArray($arr, $this, $key);
    }

    /**
     * @param int|string $key
     * @return string[]
     */
    public function getStringArray($key): array
    {
        return $this->expect($key, 'string[]');
    }


    /**
     * @param int|string $key
     * @return bool[]
     */
    public function getBoolArray($key): array
    {
        return $this->expect($key, 'bool[]');
    }


    /**
     * @param int|string $key
     * @return int[]
     */
    public function getIntArray($key): array
    {
        return $this->expect($key, 'int[]');
    }


    /**
     * @param int|string $key
     * @return float[]
     */
    public function getFloatArray($key): array
    {
        return $this->expect($key, 'float[]');
    }

    /**
     * @param int|string $key
     * @return int[]|float[]
     */
    public function getNumberArray($key): array
    {
        return $this->expect($key, 'number[]');
    }


    public
    function getObject($key): JsonObject
    {
        $arr = $this->expect($key, 'object');
        return new JsonObject($arr, $this, $key);
    }


}
