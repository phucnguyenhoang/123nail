<?php
use Migrations\AbstractMigration;

class AddReturnsToBillings extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('billings');
        $table->addColumn('returns', 'float', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
