<?php


namespace Alastor141\Service\Sheets;

use Alastor141\Google\Collection\Rows;

/**
 * Interface ProviderInterface
 * @package App\Service\Sheets
 */
interface ProviderInterface
{
    /**
     * @return string
     */
    public function getMetadataKey(): string;

    /**
     * @return Rows
     */
    public function execution(): Rows;

    /**
     * @return array
     */
    public function getAfterAdditionalRequest(): array;

    /**
     * @return array
     */
    public function getBeforeAdditionalRequest() : array;
}