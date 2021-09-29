<?php


namespace Alastor141\Service\Sheets;


use Alastor141\Google\Api\Sheets;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\DataFilter;
use Google\Service\Sheets\DeveloperMetadata;
use Google\Service\Sheets\DeveloperMetadataLookup;
use Google\Service\Sheets\SearchDeveloperMetadataRequest;
use Google\Service\Sheets\Spreadsheet;

/**
 * Class Export
 * @package App\Service\Sheets
 */
abstract class Export implements ExportInterface
{
    protected $table;

    /**
     * @var \Google\Client
     */
    protected $client;

    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @var DeveloperMetadata[]
     */
    protected $developerMetadata = [];

    /**
     * @var Spreadsheet
     */
    protected $spreadsheets;

    /**
     * Export constructor.
     * @param Sheets $sheets
     * @param Provider $provider
     * @param string $table
     */
    public function __construct(Sheets $sheets, Provider $provider, string $table)
    {
        $this->table = $table;
        $this->client = $sheets;
        $this->provider = $provider;
        $this->provider->setTable($table);
        $this->provider->setSheets($sheets);
        $this->loadSpreadsheets();
        $this->loadDeveloperMetadata();
    }

    /**
     * @return ProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    protected function loadSpreadsheets()
    {
        $this->spreadsheets = $this->client->getSheets()->spreadsheets->get($this->table);
        $this->provider->setSpreadsheets($this->spreadsheets);
    }

    protected function loadDeveloperMetadata()
    {
        $search = new SearchDeveloperMetadataRequest();
        $search->setDataFilters(new DataFilter([
            'developerMetadataLookup' => new DeveloperMetadataLookup([
                'metadataKey' => $this->provider->getMetadataKey(),
                'visibility' => 'DOCUMENT'
            ])
        ]));

        $request = $this->client->getSheets()->spreadsheets_developerMetadata->search($this->table, $search);

        /**
         * @var \Google\Service\Sheets\MatchedDeveloperMetadata $developerMetadata
         */
        foreach ($request->getMatchedDeveloperMetadata() as $matchedDeveloperMetadata) {
            $developerMetadata = $matchedDeveloperMetadata->getDeveloperMetadata();
            $this->developerMetadata[$developerMetadata->getMetadataId()] = $developerMetadata;
        }

        $this->provider->setDeveloperMetadata($this->developerMetadata);
    }

    public function export()
    {
        $collection = $this->provider->execution();
        $afterAdditionalRequest = $this->provider->getAfterAdditionalRequest();
        $beforeAdditionalRequest = $this->provider->getBeforeAdditionalRequest();

        if ($collection) {
            $requests = [];

            if ($afterAdditionalRequest) {
                $requests[] = $afterAdditionalRequest;
            }

            $updateRequest = $collection->getUpdateRequests();
            if ($updateRequest) {
                $requests[] = $updateRequest;
            }

            $requestDeveloperMetadata = $collection->getDeveloperMetadataRequest();

            if ($requestDeveloperMetadata) {
                $requests[] = $requestDeveloperMetadata;
            }

            if ($beforeAdditionalRequest) {
                $requests[] = $beforeAdditionalRequest;
            }

            $requests = array_merge(...$requests);

            $batchRequest = new BatchUpdateSpreadsheetRequest();
            $batchRequest->setRequests($requests);
            $response = $this->client->getSheets()->spreadsheets->batchUpdate($this->table, $batchRequest);
            dump($response);
        }
    }
}