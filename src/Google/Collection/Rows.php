<?php

namespace Alastor141\Google\Collection;

use Alastor141\Google\Model\Row;
use Google\Service\Sheets\CreateDeveloperMetadataRequest as CreateDeveloperMetadataRequestAlias;
use Google\Service\Sheets\DeveloperMetadata;
use Google\Service\Sheets\DeveloperMetadataLocation;
use Google\Service\Sheets\DimensionRange;
use Google\Service\Sheets\GridRange;
use Google\Service\Sheets\UpdateCellsRequest;
use Google\Service\Sheets\Request;

/**
 * Class Rows
 * @package Alastor141\Google\Collection
 */
class Rows implements \ArrayAccess, \Iterator, \Countable
{
    protected $container = [];

    protected function getUpdateRequest(Row $row, $gridRange)
    {
        return new UpdateCellsRequest([
            'fields' => $row->getFields(),
            'rows' => [$row->getRow()],
            'range' => new GridRange($gridRange)
        ]);
    }

    /**
     * @return UpdateCellsRequest[]
     */
    public function getUpdateRequests()
    {
        $requests = [];
        foreach ($this->container as $row) {
            $params = $row->getParams();

            if ($row->isDeveloperMetadata()) {
                $developerMetadata = $row->getDeveloperMetadata();
                $params['startRowIndex'] = $developerMetadata->getLocation()->getDimensionRange()->getStartIndex();
                $params['endRowIndex'] = $developerMetadata->getLocation()->getDimensionRange()->getEndIndex();
            }

            $gridRange = [
                'sheetId' => $params['sheetId'],
                'startRowIndex' => $params['startRowIndex'],
                'endRowIndex' => $params['endRowIndex'],
                'startColumnIndex' => $params['startColumnIndex'],
            ];

            $request = new Request();
            $updateRequest = $this->getUpdateRequest($row, $gridRange);

            $request->setUpdateCells($updateRequest);

            $requests[] = $request;
        }

        return $requests;
    }

    /**
     * @return CreateDeveloperMetadataRequestAlias[]
     */
    public function getDeveloperMetadataRequest()
    {
        $requests = [];
        foreach ($this->container as $row) {
            $params = $row->getParams();

            if ($row->isDeveloperMetadata()) {
                /**
                 * @var \Google\Service\Sheets\DeveloperMetadata $rowDeveloperMetadata
                 */
                $developerMetadata = $row->getDeveloperMetadata();
                $params['sheetId'] = $developerMetadata->getLocation()->getDimensionRange()->getSheetId();
                $params['startRowIndex'] = $developerMetadata->getLocation()->getDimensionRange()->getStartIndex();
                $params['endRowIndex'] = $developerMetadata->getLocation()->getDimensionRange()->getEndIndex();
            }

            $developerMetadataParams = [
                'developerMetadata' => new DeveloperMetadata([
                    'metadataId' => $row->getUid(),
                    'metadataKey' => $params['metadataKey'],
                    'location' => new DeveloperMetadataLocation([
                        'dimensionRange' => new DimensionRange([
                            'sheetId' => $params['sheetId'],
                            'startIndex' => $params['startRowIndex'],
                            'endIndex' => $params['endRowIndex'],
                            'dimension' => 'ROWS'
                        ])
                    ]),
                    'visibility' => 'DOCUMENT'
                ])
            ];

            if (!$row->isDeveloperMetadata()) {
                $request = new Request();
                $request->setCreateDeveloperMetadata(new CreateDeveloperMetadataRequestAlias($developerMetadataParams));
                $requests[] = $request;
            }
        }

        return $requests;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    public function rewind() {
        reset($this->container);
    }

    public function current() {
        return current($this->container);
    }

    public function key() {
        return key($this->container);
    }

    public function next() {
        return next($this->container);
    }

    public function valid() {
        return $this->current() !== false;
    }

    public function count() {
        return count($this->container);
    }
}