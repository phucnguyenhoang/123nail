<?php

use Phinx\Migration\AbstractMigration;

class CreateUserSessions extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('user_sessions');
        $table
            ->addColumn('users_id', 'integer', [
                'null' => false
            ])
            ->addColumn('udid', 'string', [
                'limit' => 100,
                'null' => true
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
        $this->dropTable('user_sessions');
    }
}
