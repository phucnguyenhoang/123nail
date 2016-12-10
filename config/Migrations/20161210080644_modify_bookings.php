<?php

use Phinx\Migration\AbstractMigration;

class ModifyBookings extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('bookings');
        $table->addColumn('status', 'integer', ['limit' => 1, 'default' => 0, 'null' => true])
              ->addColumn('note', 'string', ['limit' => 150, 'null' => true])
              ->update();
    }
}
