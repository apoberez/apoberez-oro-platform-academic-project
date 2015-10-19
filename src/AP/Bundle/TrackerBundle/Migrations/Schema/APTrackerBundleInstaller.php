<?php

namespace AP\Bundle\TrackerBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class APTrackerBundleInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createApTrackerIssueTable($schema);
        $this->createApTrackerPriorityTable($schema);
        $this->createApTrackerResolutionTable($schema);

        /** Foreign keys generation **/
        $this->addApTrackerIssueForeignKeys($schema);
    }

    /**
     * Create ap_tracker_issue table
     *
     * @param Schema $schema
     */
    protected function createApTrackerIssueTable(Schema $schema)
    {
        $table = $schema->createTable('ap_tracker_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('resolution_id', 'integer', ['notnull' => false]);
        $table->addColumn('priority_id', 'integer', ['notnull' => false]);
        $table->addColumn('summary', 'text', []);
        $table->addColumn('code', 'integer', []);
        $table->addColumn('createdAt', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['priority_id'], 'IDX_17A13D3497B19F9', []);
        $table->addIndex(['resolution_id'], 'IDX_17A13D312A1C43A', []);
    }

    /**
     * Create ap_tracker_priority table
     *
     * @param Schema $schema
     */
    protected function createApTrackerPriorityTable(Schema $schema)
    {
        $table = $schema->createTable('ap_tracker_priority');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('order', 'integer', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create ap_tracker_resolution table
     *
     * @param Schema $schema
     */
    protected function createApTrackerResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('ap_tracker_resolution');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('order', 'integer', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Add ap_tracker_issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addApTrackerIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('ap_tracker_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('ap_tracker_resolution'),
            ['resolution_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('ap_tracker_priority'),
            ['priority_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }
}
