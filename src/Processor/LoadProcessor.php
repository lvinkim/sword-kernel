<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 24/09/2018
 * Time: 9:04 PM
 */

namespace Lvinkim\SwordKernel\Processor;


use Lvinkim\SwordKernel\Utility\DirectoryScanner;
use HaydenPierce\ClassFinder\ClassFinder;
use Symfony\Component\DependencyInjection\Container;

class LoadProcessor
{
    private $container;
    private $settings;

    /**
     * LoadProcessor constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $settings
     */
    public function onEvent($settings)
    {
        $this->settings = $settings;

        foreach ($this->getAllServiceClasses() as $serviceClass) {
            $serviceObject = new $serviceClass($this->container, $settings);
            $this->container->set($serviceClass, $serviceObject);
        }

        // Action 依赖 Service ，需要后置实例化
        foreach ($this->getAllActionClasses() as $actionClass) {
            $actionObject = new $actionClass($this->container);
            $this->container->set($actionClass, $actionObject);
        }

    }

    /**
     * @return array
     */
    private function getAllServiceClasses()
    {
        $projectDir = $this->settings["projectDir"];
        $serviceDir = $projectDir . "/src/Service";
        $namespace = $this->settings["namespace"] . "\Service";
        $classes = $this->getClassesRecursion($serviceDir, $namespace);

        return $classes;
    }

    /**
     * @return array
     */
    private function getAllActionClasses()
    {
        $projectDir = $this->settings["projectDir"];
        $actionDir = $projectDir . "/src/Action";
        $namespace = $this->settings["namespace"] . "\Action";
        $classes = $this->getClassesRecursion($actionDir, $namespace);

        return $classes;
    }

    /**
     * @param $directory
     * @param $namespace
     * @return array
     */
    private function getClassesRecursion($directory, $namespace)
    {
        try {
            $classes = ClassFinder::getClassesInNamespace($namespace);

            $subDirectories = DirectoryScanner::scanChildNamespaces($directory);
            foreach ($subDirectories as $subDirectory) {
                $subClasses = ClassFinder::getClassesInNamespace($namespace . $subDirectory);
                $classes = array_merge($classes, $subClasses);
            }
        } catch (\Exception $exception) {
            $classes = [];
        }

        return $classes;
    }

}