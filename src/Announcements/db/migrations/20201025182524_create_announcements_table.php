<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreateAnnouncementsTable extends AbstractMigration
{

    const TABLENAME = "announcements";

    public function change()
    {
        $table = $this->table(self::TABLENAME);
        if ($table->exists()) {
            return;
        }
        $table
            ->addColumn("title", "string")
            ->addColumn("thumbnail", "string", ["null" => true])
            ->addColumn("pinned", "boolean", ["default" => false])
            ->addColumn("content", "text", ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn("published", "string")
            ->addTimestamps()
            ->create();
    }
}
