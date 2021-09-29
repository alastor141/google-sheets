<?php

namespace Alastor141\Google\Model;

use Google\Service\Sheets\CellData;
use Google\Service\Sheets\CellFormat;
use Google\Service\Sheets\ExtendedValue;
use Google\Service\Sheets\RowData;

class Row implements RowInterface
{
    protected $row;

    protected $uid;

    protected $fields = 'userEnteredValue';

    protected $values = [];

    protected $params = [];

    protected $formatCells = [];

    protected $textFormatRuns = [];

    protected $developerMetadata = null;

    public function __construct($uid, $values = [], $formatCells = [], $textFormatRuns = [])
    {
        $this->uid = $uid;
        $this->formatCells = $formatCells;
        $this->textFormatRuns = $textFormatRuns;
        if ($values) {
            $this->values = $values;
            $this->generate();
        }
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    protected function getCellData($value, $key)
    {
        return new CellData([
            'userEnteredValue' => new ExtendedValue([
                'stringValue' => $value
            ]),
            'userEnteredFormat' => new CellFormat([
                'wrapStrategy' => 'WRAP'
            ])
        ]);
    }

    protected function generate()
    {
        $values = [];
        foreach ($this->values as $key => $value) {
            $values[] = $this->getCellData($value, $key);
        }

        $this->row = new RowData([
            'values' => $values
        ]);
    }

    public function isDeveloperMetadata()
    {
        return $this->developerMetadata ? true : false;
    }

    public function getDeveloperMetadata()
    {
        return $this->developerMetadata;
    }

    public function setDeveloperMetadata($developerMetadata)
    {
        $this->developerMetadata = $developerMetadata;
        return $this;
    }

    public function getRow()
    {
        return $this->row;
    }

    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }
}