<?php

namespace AP\Bundle\BugTrackerBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class APBugTrackerBundleInstaller implements Installation
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
        $this->createApBugTrackerIssueTable($schema);
        $this->createApBugTrackerPriorityTable($schema);
        $this->createApBugTrackerResolutionTable($schema);

        /** Foreign keys generation **/
        $this->addApBugTrackerIssueForeignKeys($schema);
    }

    /**
     * Create ap_bug_tracker_issue table
     *
     * @param Schema $schema
     */
    protected function createApBugTrackerIssueTable(Schema $schema)
    {
        $table = $schema->createTable('ap_bug_tracker_issue');
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
     * Create ap_bug_tracker_priority table
     *
     * @param Schema $schema
     */
    protected function createApBugTrackerPriorityTable(Schema $schema)
    {
        $table = $schema->createTable('ap_bug_tracker_priority');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('priority_order', 'integer', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create ap_bug_tracker_resolution table
     *
     * @param Schema $schema
     */
    protected function createApBugTrackerResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('ap_bug_tracker_resolution');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('resolution_order', 'integer', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Add ap_bug_tracker_issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addApBugTrackerIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('ap_bug_tracker_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('ap_bug_tracker_resolution'),
            ['resolution_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('ap_bug_tracker_priority'),
            ['priority_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }
}
