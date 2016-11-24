<?php

use Phinx\Migration\AbstractMigration;

class CreateSalaries extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('salaries');
        $table
            ->addColumn('employees_id', 'integer', [
                'null' => false
            ])
            ->addColumn('from_date', 'datetime', [
                'limit' => null,
                'null' => true
            ])
            ->addColumn('to_date', 'datetime', [
                'limit' => null,
                'null' => true
            ])
            ->addColumn('price', 'float', [
                'limit' => null,
                'null' => true
            ])
            ->addColumn('shop_fee', 'float', [
                'limit' => null,
                'null' => true
            ])
            ->addColumn('discount', 'float', [
                'limit' => null,
                'null' => true
            ])
            ->addColumn('tips', 'float', [
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
        $this->dropTable('salaries');
    }
}
