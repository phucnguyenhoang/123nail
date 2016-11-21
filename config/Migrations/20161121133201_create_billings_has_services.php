<?php

use Phinx\Migration\AbstractMigration;

class CreateBillingsHasServices extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('billings_has_services');
        $table
            ->addColumn('billings_id', 'integer', [
                'null' => false
            ])
            ->addColumn('services_id', 'integer', [
                'null' => false
            ])
            ->addColumn('employees_id', 'integer', [
                'null' => false
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
            ->create();
    }
    public function down()
    {
        $this->dropTable('billings_has_services');
    }
}
