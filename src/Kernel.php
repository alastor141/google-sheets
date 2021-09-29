<?php

namespace Alastor141;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class Kernel
 * @package Alastor141
 */
abstract class Kernel implements KernelInterface
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @var
     */
    protected $projectDir;

    /**
     * Kernel constructor.
     */
    public function __construct()
    {
        $this->container = new ContainerBuilder();
        $this->container->getParameterBag()->add([
            'kernel.project_dir' => realpath($this->getProjectDir()) ?: $this->getProjectDir(),
        ]);

        $this->configureContainer();
    }

    /**
     * @return ContainerBuilder
     */
    public function getContainer() :ContainerBuilder
    {
        return $this->container;
    }

    protected function configureContainer()
    {
        $this->container->setParameter('kernel.config_dir', $this->getProjectDir().'/config');
        $loader = new YamlFileLoader($this->container, new FileLocator($this->container->getParameter('kernel.config_dir')));
        $loader->load('services.yaml');
    }

    /**
     * @return string
     */
    public function getProjectDir() :string
    {
        if (null === $this->projectDir) {
            $r = new \ReflectionObject($this);
            if (!is_file($dir = $r->getFileName())) {
                throw new \LogicException(sprintf('Cannot auto-detect project dir for kernel of class "%s".', $r->name));
            }

            $dir = $rootDir = \dirname($dir);
            while (!is_file($dir.'/composer.json')) {
                if ($dir === \dirname($dir)) {

                    return $this->projectDir = $rootDir;
                }
                $dir = \dirname($dir);
            }
            $this->projectDir = $dir;
        }

        return $this->projectDir;
    }
}