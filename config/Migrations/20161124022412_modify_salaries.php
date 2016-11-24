<?php

use Phinx\Migration\AbstractMigration;

class ModifySalaries extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('salaries');
        $table->addColumn('salary_type', 'integer', ['default' => 1, 'null' => true])
              ->addColumn('percent', 'float', ['default' => null, 'null' => true])
              ->update();
    }
}
