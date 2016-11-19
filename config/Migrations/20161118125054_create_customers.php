<?php

use Phinx\Migration\AbstractMigration;

class CreateCustomers extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('customers');
        $table
            ->addColumn('shops_id', 'integer', [
                'default' => null,
                'null' => true
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true
            ])
            ->addColumn('first_name', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false
            ])
            ->addColumn('last_name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false
            ])
            ->addColumn('birthday', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true
            ])
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => true
            ])
            ->addColumn('telephone', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => true
            ])
            ->addColumn('avatar', 'integer', [
                'default' => null,
                'null' => true
            ])
            ->addColumn('favorite', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => true
            ])
            ->addColumn('last_visit', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true
            ])
            ->addColumn('last_service', 'integer', [
                'default' => null,
                'limit' => null,
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
        $this->dropTable('customers');
    }
}
