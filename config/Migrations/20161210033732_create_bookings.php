<?php

use Phinx\Migration\AbstractMigration;

class CreateBookings extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('bookings');
        $table
            ->addColumn('customers_id', 'integer', [
                'null' => false
            ])
            ->addColumn('date', 'date', [
                'limit' => null,
                'null' => true
            ])
            ->addColumn('start_time', 'time', [
                'limit' => null,
                'null' => true
            ])
            ->addColumn('end_time', 'time', [
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
        $this->dropTable('bookings');
    }
}
