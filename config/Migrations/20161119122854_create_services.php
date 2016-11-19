<?php

use Phinx\Migration\AbstractMigration;

class CreateServices extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('services');
        $table
            ->addColumn('categories_id', 'integer', [
                'null' => false
            ])
            ->addColumn('name', 'string', [
                'limit' => 150,
                'null' => false
            ])
            ->addColumn('price', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true
            ])
            ->addColumn('shop_fee', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true
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
        $this->dropTable('services');
    }
}
