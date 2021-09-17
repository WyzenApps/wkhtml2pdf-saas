<?php

declare(strict_types=1);

namespace App\Dto\ServiceScope;

use App\Traits\GetterSetter;
use ArrayAccess;
use Exception;
use JsonSerializable;
use Wyzen\Php\Helper;

class ResponseService implements JsonSerializable, ArrayAccess
{
    use GetterSetter;

    /** @var int */
    private $statusCode;
    /** @var array */
    private $data = [];
    /** @var array */
    private $error = [];

    public function __construct(?string $content = '')
    {

        $this->hydrate($content);
    }

    /**
     * Hydrate le ResponseService
     *
     * @param String|null $content
     * @return ResponseService
     * @throws Exception
     */
    public function hydrate(?string $content): ResponseService
    {
        if (is_null($content) || is_null($contentDecoded = json_decode($content, true))) {
            throw new \Exception('msg', 500);
        }

        $this->statusCode = Helper::getValueEx($contentDecoded, 'statusCode', 500);
        $this->data       = Helper::getValueEx($contentDecoded, 'data', []);
        $this->error      = Helper::getValueEx($contentDecoded, 'error', []);


        if ($this->hasError()) {
            $this->data = $contentDecoded;
            return $this;
            throw new \Exception($this->getErrorMessage(), $this->statusCode);
        }
        return $this;
    }
    /**
     * Retourne les datas
     *
     * @return
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function hasError(): bool
    {
        return $this->statusCode !== 200;
    }

    public function getErrorType(): string
    {
        if (isset($this->error['type'])) {
            return $this->error['type'];
        }
    }

    public function getErrorMessage(): string
    {
        return (array_key_exists('description', $this->error)) ? $this->error['description'] : 'error';
    }

    public function jsonSerialize(): array
    {
        return [
            'statusCode' => $this->statusCode,
            'data' => $this->data,
            'error' => $this->error
        ];
    }

    /**
     * Test si l'offset existe
     *
     * @param [type] $offset
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return (property_exists($this, (string) $offset));
    }

    public function dataSet($key, $val): void
    {
        $this->data[$key] = $val;
    }

    /**
     * Recupère les info d'indice $offset
     *
     * @param  $offset
     * @return void
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->$offset;
        }
        return null;
    }

    /**
     * Mise à jour d'une propriété
     *
     * @param [type] $offset
     * @param [type] $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if ($this->offsetExists($offset)) {
            $this->$offset = $value;
        }
    }

    public function offsetUnset($offset): void
    {
    }

    /**
     * set Data
     *
     * @param mixed $data
     * @return ResponseService
     */
    public function setData($data): ResponseService
    {
        $this->data = $data;
        return $this;
    }
}
