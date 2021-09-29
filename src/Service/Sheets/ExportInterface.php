<?php


namespace Alastor141\Service\Sheets;


use Alastor141\Google\Api\Sheets;

/**
 * Interface ExportInterface
 * @package App\Service\Sheets
 */
interface ExportInterface
{
    /**
     * ExportInterface constructor.
     * @param Sheets $sheets
     * @param Provider $provider
     * @param string $table
     */
    public function __construct(Sheets $sheets, Provider $provider, string $table);
}