<?php

use Phinx\Migration\AbstractMigration;

class CreateCategories extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('categories');
        $table
            ->addColumn('shops_id', 'integer', [
                'null' => false
            ])
            ->addColumn('name', 'string', [
                'limit' => 150,
                'null' => false
            ])
            ->addColumn('active', 'boolean', [
                'default' => 1,
                'null' => true
            ])
            ->addColumn('created', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true
            ])
            ->create();
    }
    public function down()
    {
        $this->dropTable('categories');
    }
}
