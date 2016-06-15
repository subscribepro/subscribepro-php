<?php

namespace SubscribePro\Service;

class DataObject implements DataInterface
{
    /**
     * @var string
     */
    protected $idField = 'id';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->importData($data);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function importData(array $data = [])
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return !(bool)$this->getId();
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->getData($this->idField);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    protected function setData($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    protected function getData($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * @param string $field
     * @param string|null $format
     * @return string
     */
    protected function getDateData($field, $format = null)
    {
        $date = $this->getData($field);
        return $format && $date ? $this->formatDate($date, $format, 'Y-m-d') : $date;
    }

    /**
     * @param string $field
     * @param string|null $format
     * @return string
     */
    protected function getDatetimeData($field, $format = null)
    {
        $date = $this->getData($field);
        return $format && $date ? $this->formatDate($date, $format, \DateTime::ISO8601) : $date;
    }

    /**
     * @param string $date
     * @param string $outputFormat
     * @param string $inputFormat
     * @return string
     */
    protected function formatDate($date, $outputFormat, $inputFormat)
    {
        $dateTime = \DateTime::createFromFormat($inputFormat, $date);
        return $dateTime ? $dateTime->format($outputFormat) : $date;
    }

    /**
     * @param  array $fields
     * @return bool
     */
    protected function checkRequiredFields(array $fields)
    {
        foreach ($fields as $field => $isRequired) {
            if ($isRequired && null === $this->getData($field)) {
                return false;
            }
        }
        return true;
    }
}
