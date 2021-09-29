<?php


namespace Alastor141\Service\Sheets;

use Alastor141\Google\Api\Sheets;
use Google\Service\Sheets\DeveloperMetadata;
use Google\Service\Sheets\Spreadsheet;

abstract class Provider implements ProviderInterface
{
    /**
     * @var Sheets
     */
    protected $sheets;

    /**
     * @var String
     */
    protected $table;

    /**
     * @var Spreadsheet
     */
    protected $spreadsheets;

    /**
     * @var DeveloperMetadata[]
     */
    protected $developerMetadata;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $beforeAdditionalRequest = [];

    /**
     * @var array
     */
    protected $afterAdditionalRequest = [];

    /**
     * Provider constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getAfterAdditionalRequest() : array
    {
        return $this->afterAdditionalRequest;
    }

    /**
     * @return array
     */
    public function getBeforeAdditionalRequest() : array
    {
        return $this->beforeAdditionalRequest;
    }

    /**
     * @param Sheets $sheets
     * @return $this
     */
    public function setSheets(Sheets $sheets) : Provider
    {
        $this->sheets = $sheets;
        return $this;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function setTable(string $table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param DeveloperMetadata[] $developerMetadata
     * @return Provider
     */
    public function setDeveloperMetadata(array $developerMetadata)
    {
        $this->developerMetadata = $developerMetadata;
        return $this;
    }

    /**
     * @param Spreadsheet $spreadsheets
     * @return Provider
     */
    public function setSpreadsheets(Spreadsheet $spreadsheets)
    {
        $this->spreadsheets = $spreadsheets;
        return $this;
    }

    /**
     * @return DeveloperMetadata[]
     */
    public function getDeveloperMetadata()
    {
        return $this->developerMetadata;
    }
}