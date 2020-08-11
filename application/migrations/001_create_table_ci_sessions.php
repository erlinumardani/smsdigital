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
class Migration_create_table_ci_sessions extends CI_Migration {

	protected $table = 'sessions';
	
	public function up(){
	
		$fields = array(
			'id' => array(
				'type'			=> 'VARCHAR',
				'constraint'	=> '40',
				'default'		=> '0'
			),
			'ip_address' => array(
				'type'			=> 'VARCHAR',
				'constraint'	=> '45',
				'default'		=> '0'
			),
			'timestamp'	=> array(
				'type'			=> 'INT',
				'constraint'	=> '10',
				'default'		=> '0',
				'unsigned'		=> true
			),
			'data' => array(
				'type'			=> 'TEXT'
			)
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('session_id', TRUE);
		$this->dbforge->create_table($this->table, TRUE);
		$this->db->query('ALTER TABLE `' . $this->table . '` ADD KEY `timestamp_idx` (`timestamp`)');
	}
	
	public function down()
	{
		if ($this->db->table_exists($this->table))
		{
			$this->dbforge->drop_table($this->table);
		}
	}
}