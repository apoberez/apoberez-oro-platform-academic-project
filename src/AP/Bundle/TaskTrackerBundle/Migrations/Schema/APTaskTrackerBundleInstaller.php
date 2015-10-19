<?php

namespace AP\Bundle\TaskTrackerBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class APTaskTrackerBundleInstaller implements Installation
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
        $this->createApTaskTrackerIssueTable($schema);
        $this->createApTaskTrackerPriorityTable($schema);
        $this->createApTaskTrackerResolutionTable($schema);

        /** Foreign keys generation **/
        $this->addApTaskTrackerIssueForeignKeys($schema);
    }

    /**
     * Create ap_task_tracker_issue table
     *
     * @param Schema $schema
     */
    protected function createApTaskTrackerIssueTable(Schema $schema)
    {
        $table = $schema->createTable('ap_task_tracker_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('resolution_id', 'integer', ['notnull' => false]);
        $table->addColumn('priority_id', 'integer', ['notnull' => false]);
        $table->addColumn('summary', 'text', []);
        $table->addColumn('code', 'integer', []);
        $table->addColumn('createdAt', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['priority_id'], 'IDX_4FB39121497B19F9', []);
        $table->addIndex(['resolution_id'], 'IDX_4FB3912112A1C43A', []);
    }

    /**
     * Create ap_task_tracker_priority table
     *
     * @param Schema $schema
     */
    protected function createApTaskTrackerPriorityTable(Schema $schema)
    {
        $table = $schema->createTable('ap_task_tracker_priority');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('priority_order', 'integer', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create ap_task_tracker_resolution table
     *
     * @param Schema $schema
     */
    protected function createApTaskTrackerResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('ap_task_tracker_resolution');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('resolution_order', 'integer', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Add ap_task_tracker_issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addApTaskTrackerIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('ap_task_tracker_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('ap_task_tracker_resolution'),
            ['resolution_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('ap_task_tracker_priority'),
            ['priority_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }
}
