<?php
/**
 * @author Percipio Global Ltd. <support@percipio.london>
 * @since 1.0.0
 */

namespace percipiolondon\typesense\services;

use craft\base\MemoizableArray;
use craft\db\Query;

use percipiolondon\typesense\models\CollectionModel as Collection;

use percipiolondon\typesense\Typesense;
use Throwable;
use yii\base\Component;

class CollectionService extends Component
{
    /**
     * @var string
     */
    public const CONFIG_COLLECTIONS_KEY = 'collections';

    public function getCollectionByCollectionRetrieve(string $indexName): ?array
    {
        $collections = Typesense::$plugin->getClient()->client()->collections->retrieve();
        $alliases = Typesense::$plugin->getClient()->client()->aliases->retrieve()['aliases'];
        $retrievedCollection = [];

        foreach ($collections as $collection) {
            if ($collection['name'] === $indexName) {
                $retrievedCollection = $collection;
            }
        }

        if (empty($retrievedCollection)) {
            foreach ($alliases as $alias) {
                if ($alias['name'] === $indexName) {
                    $retrievedCollection = $alias;
                }
            }
        }

        return $retrievedCollection;
    }

    public function saveCollections(): void
    {
        $indexes = Typesense::$plugin->getSettings()->collections;

        foreach ($indexes as $index) {
            if (!$this->getCollectionByCollectionRetrieve($index->indexName)) {
                Typesense::$plugin->getClient()->client()->collections->create($index->schema);
            }
        }
    }
}
