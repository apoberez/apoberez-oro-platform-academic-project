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
        $this->createIssuesCollaboratorsTable($schema);
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
        $table->addColumn('workflow_step_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_item_id', 'integer', ['notnull' => false]);
        $table->addColumn('resolution_id', 'integer', ['notnull' => false]);
        $table->addColumn('priority_id', 'integer', ['notnull' => false]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('assignee', 'integer', ['notnull' => false]);
        $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('summary', 'text', []);
        $table->addColumn('code', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('createdAt', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);
        $table->addColumn('description', 'text', ['notnull' => false]);
        $table->addColumn('issue_type', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['code'], 'UNIQ_F84404F377153098');
        $table->addUniqueIndex(['workflow_item_id'], 'UNIQ_F84404F31023C4EE');
        $table->addIndex(['priority_id'], 'IDX_4FB39121497B19F9', []);
        $table->addIndex(['resolution_id'], 'IDX_4FB3912112A1C43A', []);
        $table->addIndex(['parent_id'], 'IDX_F84404F3727ACA70', []);
        $table->addIndex(['assignee'], 'IDX_F84404F37C9DFC0C', []);
        $table->addIndex(['reporter_id'], 'IDX_F84404F3E1CFE6F5', []);
        $table->addIndex(['workflow_step_id'], 'IDX_F84404F371FE882C', []);
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
        $table->addColumn('label', 'string', ['notnull' => false, 'length' => 255]);
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
        $table->addColumn('label', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('resolution_order', 'integer', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create issues_collaborators table
     *
     * @param Schema $schema
     */
    protected function createIssuesCollaboratorsTable(Schema $schema)
    {
        $table = $schema->createTable('issues_collaborators');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
        $table->addIndex(['issue_id'], 'IDX_D80E8CD75E7AA58C', []);
        $table->addIndex(['user_id'], 'IDX_D80E8CD7A76ED395', []);
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
            $schema->getTable('oro_workflow_step'),
            ['workflow_step_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_item'),
            ['workflow_item_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
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
        $table->addForeignKeyConstraint(
            $schema->getTable('ap_bug_tracker_issue'),
            ['parent_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['assignee'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['reporter_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }

    /**
     * Add issues_collaborators foreign keys.
     *
     * @param Schema $schema
     */
    protected function addIssuesCollaboratorsForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('issues_collaborators');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('ap_bug_tracker_issue'),
            ['issue_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }
}

