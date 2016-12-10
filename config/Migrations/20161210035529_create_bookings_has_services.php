<?php

use Phinx\Migration\AbstractMigration;

class CreateBookingsHasServices extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('bookings_has_services');
        $table
            ->addColumn('bookings_id', 'integer', [
                'null' => false
            ])
            ->addColumn('services_id', 'integer', [
                'null' => false
            ])
            ->addColumn('note', 'string', [
                'limit' => 150,
                'null' => true
            ])
            ->create();
    }
    public function down()
    {
        $this->dropTable('bookings_has_services');
    }
}
