<?php

namespace App\Subscribers;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Kdyby\Events\Subscriber;

class TablePrefixSubscriber implements Subscriber
{
    /** @var string */
    protected $prefix = '';

    /**
     * Constructor
     * @param string $prefix
     */
    public function __construct($prefix)
    {
        $this->prefix = (string)$prefix;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array('loadClassMetadata');
    }

    /**
     * @param LoadClassMetadataEventArgs $args
     * @return void
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $classMetadata = $args->getClassMetadata();

        // Only add the prefixes to our own entities.
        // if (FALSE !== (strpos($classMetadata->namespace, 'App\Model\Entities'))) {
            // Do not re-apply the prefix when the table is already prefixed
            if (false === strpos($classMetadata->getTableName(), $this->prefix)) {
                $tableName = $this->prefix . $classMetadata->getTableName();
                $classMetadata->setPrimaryTable(['name' => $tableName]);
            }

            foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
                if ($mapping['type'] === ClassMetadataInfo::MANY_TO_MANY && $mapping['isOwningSide'] === true) {
                    $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];

                    // Do not re-apply the prefix when the association is already prefixed
                    if (false !== strpos($mappedTableName, $this->prefix)) {
                        continue;
                    }

                    $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
                }
            }
        // }
    }
}