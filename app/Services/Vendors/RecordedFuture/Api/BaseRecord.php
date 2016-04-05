<?php

namespace App\Services\Vendors\RecordedFuture\Api;

abstract class BaseRecord
{
    /**
     * @var array
     */
    protected $record;

    public function __construct(array $record)
    {
        $this->record = $record;
    }

    protected function getFieldAsString($field): string
    {
        return (string) trim(array_get($this->record, $field, ''));
    }

    protected function getFieldAsFloat($field): float
    {
        return (float) array_get($this->record, $field, 0.0);
    }

    protected function getFieldAsBool($field): bool
    {
        return (bool) array_get($this->record, $field, false);
    }

    protected function getFieldAsInt($field): int
    {
        return (int) array_get($this->record, $field, 0);
    }

    protected function getFieldAsArray($field): array
    {
        return array_get($this->record, $field, []);
    }

    /**
     * List of acceptable options: JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_NUMERIC_CHECK,
     * JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_UNESCAPED_UNICODE
     *
     * @param int $options
     * @return string
     */
    public function asJson(int $options = 0)
    {
        return json_encode($this->record, $options);
    }

    public function __toString()
    {
        return json_encode($this->record, JSON_PRETTY_PRINT);
    }
}
