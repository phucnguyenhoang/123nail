<?php

use Phinx\Migration\AbstractMigration;

class CreateShops extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('shops');
        $table
            ->addColumn('account', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
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
        $this->dropTable('shops');
    }
}
