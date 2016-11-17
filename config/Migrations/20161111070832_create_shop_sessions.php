<?php

use Phinx\Migration\AbstractMigration;

class CreateShopSessions extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('shop_sessions');
        $table
            ->addColumn('shops_id', 'integer', [
                'null' => false
            ])
            ->addColumn('udid', 'string', [
                'limit' => 100,
                'null' => false
            ])
            ->addColumn('login_date', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false
            ])
            ->addColumn('logout_date', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true
            ])
            ->create();
    }
    public function down()
    {
        $this->dropTable('shop_sessions');
    }
}
