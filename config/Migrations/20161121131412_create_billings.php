<?php

use Phinx\Migration\AbstractMigration;

class CreateBillings extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('billings');
        $table
            ->addColumn('customers_id', 'integer', [
                'null' => false
            ])
            ->addColumn('payment_type', 'integer', [
                'limit' => 3,
                'null' => true
            ])
            ->addColumn('receive', 'float', [
                'limit' => null,
                'null' => true
            ])
            ->addColumn('return', 'float', [
                'limit' => null,
                'null' => true
            ])
            ->addColumn('note', 'string', [
                'limit' => 250,
                'null' => true
            ])
            ->addColumn('done', 'boolean', [
                'default' => 0,
                'null' => true
            ])
            ->addColumn('billing_date', 'datetime', [
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
        $this->dropTable('billings');
    }
}
