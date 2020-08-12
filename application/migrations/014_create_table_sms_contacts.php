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
class Migration_create_table_sms_contacts extends CI_Migration {


	protected $table = 'sms_contacts';


	public function up()
	{
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE
			],
			'phonebook_id'  => [
				'type' => 'INT(11)'
			],
			'phone'  => [
				'type' => 'VARCHAR(20)'
			],
			'first_name'  => [
				'type' => 'VARCHAR(50)'
			],
			'last_name'  => [
				'type' => 'VARCHAR(50)'
			],
			'email'  => [
				'type' => 'VARCHAR(100)'
			],
			'company'  => [
				'type' => 'VARCHAR(100)'
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

		//db seed
		$data = array(
			array(
				'id'  			=> 1,
				'phonebook_id'  => 1,
				'phone'  		=> "628179931080",
				'first_name'  	=> "BPI",
				'last_name'  	=> "Chrisdyanto",
			)
		);
		$this->db->insert_batch($this->table, $data);

	}


	public function down()
	{
		if ($this->db->table_exists($this->table))
		{
			$this->dbforge->drop_table($this->table);
		}
	}

}
