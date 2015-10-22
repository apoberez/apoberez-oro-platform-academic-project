<?php

namespace AP\Bundle\BugTrackerBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtension;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class APBugTrackerBundleInstaller implements Installation, NoteExtensionAwareInterface, ActivityExtensionAwareInterface
{
    /**
     * @var NoteExtension
     */
    protected $noteExtension;

    /**
     * @var ActivityExtension
     */
    protected $activityExtension;

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

        $this->noteExtension->addNoteAssociation($schema, 'ap_bug_tracker_issue');
        $this->activityExtension->addActivityAssociation($schema, 'oro_email', 'ap_bug_tracker_issue');
    }

    /**
     * Sets the NoteExtension
     *
     * @param NoteExtension $noteExtension
     */
    public function setNoteExtension(NoteExtension $noteExtension)
    {
        $this->noteExtension = $noteExtension;
    }

    /**
     * Sets the ActivityExtension
     *
     * @param ActivityExtension $activityExtension
     */
    public function setActivityExtension(ActivityExtension $activityExtension)
    {
        $this->activityExtension = $activityExtension;
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
        $table->addColumn('priority_id', 'integer', ['notnull' => false]);
        $table->addColumn('resolution_id', 'integer', ['notnull' => false]);
        $table->addColumn('summary', 'text', []);
        $table->addColumn('description', 'text', []);
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
            $schema->getTable('ap_bug_tracker_priority'),
            ['priority_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('ap_bug_tracker_resolution'),
            ['resolution_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }
}
