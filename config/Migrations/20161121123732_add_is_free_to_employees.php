<?php

use Phinx\Migration\AbstractMigration;

class AddIsFreeToEmployees extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('employees');
        $table->addColumn('is_free', 'boolean', ['default' => 1, 'null' => true])
              ->update();
    }
}
