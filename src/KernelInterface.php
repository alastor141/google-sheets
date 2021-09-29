<?php


namespace Alastor141;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Interface KernelInterface
 * @package Alastor141
 */
interface KernelInterface
{
    public function getContainer() :ContainerBuilder;

    public function getProjectDir() :string;
}