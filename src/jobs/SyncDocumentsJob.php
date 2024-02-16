<?php
/**
 * Typesense plugin for Craft CMS 4.x
 *
 * Craft Plugin that synchronises with Typesense
 *
 * @link      https://percipio.london
 * @copyright Copyright (c) 2022 percipiolondon
 */

namespace percipiolondon\typesense\jobs;

use Craft;
use craft\base\Batchable;
use craft\db\QueryBatcher;
use craft\queue\BaseBatchedJob;

use percipiolondon\typesense\helpers\CollectionHelper;
use percipiolondon\typesense\Typesense;

/**
 * TypesenseTask job
 *
 * Upserts the documents in a collection
 *
 * use percipiolondon\typesense\jobs\SyncDocumentsTask;
 *
 * Queue::push(new SyncDocumentsTask([
 *   'criteria' => [
 *      'index' => 'index',
 *      'isNew' => true
 *   ]
 * ]));
 *
 * @author    percipiolondon
 * @package   Typesense
 * @since     1.0.0
 */
class SyncDocumentsJob extends BaseBatchedJob
{
    // Public Properties
    // =========================================================================
    public array $criteria = [];
    public int $batchIndex = 0;
    public int $batchSize = 5000;
    private $collection;
    private $client;
    private $transformed = [];

    public function execute($queue): void
    {
        $this->client = Typesense::$plugin->getClient()->client();
        if (!$this->client) {
            throw new \Exception('Typesense client not found');
        }

        $this->collection = CollectionHelper::getCollection($this->criteria['index']);
        if (is_null($this->collection)) {
            throw new \Exception('Collection not found');
        }

        $typesenseCollection = Typesense::$plugin->getCollections()->getCollectionByCollectionRetrieve($this->criteria['index']);

        if ($this->criteria['type'] === 'Flush') {
            $typesenseCollection = $this->flushCollection($typesenseCollection);
            // To avoid reflushing the collection when handle subsequent batched syncs we override the type
            $this->criteria['type'] = 'Sync';
        }

        if (!$typesenseCollection) {
            $typesenseCollection = $this->client->collections->create($this->collection->schema);
        }

        if (!$typesenseCollection) {
            throw new \Exception('Collection not found.');
        }

        parent::execute($queue);

        $this->client->collections[$this->criteria['index']]->documents->import($this->transformed, ['action' => 'create']);
    }

    // Protected Methods
    // =========================================================================
    protected function loadData(): Batchable
    {
        $collection = CollectionHelper::getCollection($this->criteria['index']);
        return new QueryBatcher($collection->criteria);
    }

    protected function processItem(mixed $item): void
    {
        $resolver = $this->collection->schema['resolver']($item);

        if ($resolver) {
            $this->transformed[] = $resolver;
        }
    }

    protected function defaultDescription(): string
    {
        $indexName = $this->criteria['index'];
        return Craft::t('app', "[{indexName}] Syncing documents", [ 'indexName' => $indexName ]);
    }

    // Private Methods
    // =========================================================================
    private function flushCollection($collectionTypesense)
    {
        if ($collectionTypesense !== []) {
            $this->client?->collections[$this->criteria['index']]->delete();
        }

        return;
    }
}
