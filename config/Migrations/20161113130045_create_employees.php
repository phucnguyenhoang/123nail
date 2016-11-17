<?php

use Phinx\Migration\AbstractMigration;

class CreateEmployees extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('employees');
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
            ->addColumn('salary_type', 'integer', [
                'default' => null,
                'limit' => 4,
                'null' => true
            ])
            ->addColumn('percent', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true
            ])
            ->addColumn('hourly_rate', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true
            ])
            ->addColumn('working_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true
            ])
            ->addColumn('leaving_date', 'date', [
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
        $this->dropTable('employees');
    }
}
