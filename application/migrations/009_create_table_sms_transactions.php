<?php
/**
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_create_table_users
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_table_sms_transactions extends CI_Migration {


	protected $table = 'sms_transactions';


	public function up()
	{
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE
			],
			'type' => [
				'type' => 'ENUM("Quick","Bulk","Schedule","File")',
				'default' => "Quick"
			],
			'msisdn' => [
				'type' => 'VARCHAR(50)'
			],
			'message'  => [
				'type' => 'TEXT'
			],
			'phone_book_id' => [
				'type' => 'INT(11)',
			],
			'contact_id' => [
				'type' => 'INT(11)',
			],
			'schedule' => [
				'type' => 'DATETIME',
				'null' => TRUE
			],
			'status' => [
				'type' => 'VARCHAR(50)',
				'default' => "SENDING"
			],
			'updated_by' => [
				'type' => 'VARCHAR(20)',
				'null' => TRUE,
				'unsigned' => TRUE,
				'default' => 'System'
			],
			'created_at' => [
				'type' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP' 
			],
			'updated_at' => [
				'type' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
				'null' => TRUE
			]
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($this->table, TRUE);

	}


	public function down()
	{
		if ($this->db->table_exists($this->table))
		{
			$this->dbforge->drop_table($this->table);
		}
	}

}
