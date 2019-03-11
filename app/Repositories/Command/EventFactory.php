<?php

namespace App\Repositories\Command;

use Rest\Shared\DomainEvent;
use ReflectionClass;

/**
 * Class DomainEventFactory
 * @package App\Factories
 */
class EventFactory
{
    /**
     * @var DomainEvent[]
     */
    private $classMap = [];

    /**
     * @param string $eventName
     * @param string $className
     */
    public function addEvent(string $eventName, string $className): void
    {
        $this->classMap[$eventName] = $className;
    }

    /**
     * @param $eventName
     * @param $eventData
     * @return DomainEvent|null
     */
    public function makeEventFromName($eventName, $eventData): ?DomainEvent
    {
        if (array_key_exists($eventName, $this->classMap)) {
            $eventClassName = $this->classMap[$eventName];
            return $eventClassName::fromArray($eventData);
        }
        return null;
    }


    /**
     * @param string $path
     * @throws \ReflectionException
     */
    public function loadMoreFrom(string $path): void
    {
        $path = trim($path);
        $path = ltrim($path, DIRECTORY_SEPARATOR);
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        $filePath = base_path() . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . '*.php';

        foreach (glob($filePath) as $file) {
            $lastSlash = strrpos($file, DIRECTORY_SEPARATOR) + 1;
            $end = (strlen($file) - $lastSlash) - 4;
            $className = substr($file, $lastSlash, $end);

            $contents = explode("\n", file_get_contents($file));

            if (count($contents) > 0) {
                foreach ($contents as $content) {
                    if (strpos($content, 'namespace') !== false) {
                        $namespace = trim(str_replace(['namespace', ';'], '', $content)) . "\\";
                        $class = $namespace . $className;

                        $reflection = new ReflectionClass($class);
                        if ($reflection->isAbstract() === false && $reflection->hasMethod('staticEventName')) {
                            $this->addEvent($class::staticEventName(), $class);
                            break;
                        }
                    }
                }
            }
        }
    }


    /**
     * @param string $path
     * @return EventFactory|null
     * @throws \ReflectionException
     */
    public static function loadAllFrom(string $path): ?EventFactory
    {
        $self = new static();

        $path = trim($path);
        $path = ltrim($path, DIRECTORY_SEPARATOR);
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        $filePath = base_path() . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . '*.php';

        foreach (glob($filePath) as $file) {
            $lastSlash = strrpos($file, DIRECTORY_SEPARATOR) + 1;
            $end = (strlen($file) - $lastSlash) - 4;
            $className = substr($file, $lastSlash, $end);

            $contents = explode("\n", file_get_contents($file));

            if (count($contents) > 0) {
                foreach ($contents as $content) {
                    if (strpos($content, 'namespace') !== false) {
                        $namespace = trim(str_replace(['namespace', ';'], '', $content)) . "\\";
                        $class = $namespace . $className;

                        $reflection = new ReflectionClass($class);
                        if ($reflection->isAbstract() === false && $reflection->hasMethod('staticEventName')) {
                            $self->addEvent($class::staticEventName(), $class);
                            break;
                        }
                    }
                }
            }
        }
        return $self;
    }
}
