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
class Migration_create_table_menus extends CI_Migration {


	protected $table = 'menus';


	public function up()
	{
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE
			],
			'sequence' => [
				'type' => 'INT(11)',
				'default' => 1
			],
			'type' => [
				'type' => 'ENUM("Main","Sub","Single")',
				'default' => "Main"
			],
			'main_id' => [
				'type' => 'INT(11)',
				'null' => TRUE
			],
			'name' => [
				'type' => 'VARCHAR(50)'
			],
			'url' => [
				'type' => 'TEXT',
				'null' => TRUE
			],
			'icon' => [
				'type' => 'TEXT',
				'null' => TRUE
			],
			'privileges' => [
				'type' => 'JSON',
				'null' => TRUE
			],
			'status' => [
				'type' => 'ENUM("Active","Inactive")',
				'default' => "Active",
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
				'id'  		=> 1,
				'sequence'  => 1,
				'type'  	=> "Single",
				'main_id'  	=> NULL,
				'name'  	=> "Dashboard",
				'url'  		=> "dashboard/data",
				'icon'  	=> 'icon-chart',
				'privileges'=> '[1, 2, 3]'
			),
			array(
				'id'  		=> 2,
				'sequence'  => 2,
				'type'  	=> "Main",
				'main_id'  	=> NULL,
				'name'  	=> "Master Setting",
				'url'  		=> "#",
				'icon'  	=> 'icon-settings',
				'privileges'=> '[1]'
			),
			array(
				'id'  		=> 3,
				'sequence'  => 1,
				'type'  	=> "Sub",
				'main_id'  	=> 2,
				'name'  	=> "User Management",
				'url'  		=> "users/data",
				'icon'  	=> 'icon-user',
				'privileges'=> '[1]'
			),
			array(
				'id'  		=> 4,
				'sequence'  => 2,
				'type'  	=> "Sub",
				'main_id'  	=> 2,
				'name'  	=> "Menu Management",
				'url'  		=> "menus/data",
				'icon'  	=> 'icon-menu',
				'privileges'=> '[1]'
			),
			array(
				'id'  		=> 5,
				'sequence'  => 3,
				'type'  	=> "Sub",
				'main_id'  	=> 2,
				'name'  	=> "Roles",
				'url'  		=> "roles/data",
				'icon'  	=> 'icon-people',
				'privileges'=> '[1]'
			),
			array(
				'id'  		=> 6,
				'sequence'  => 4,
				'type'  	=> "Single",
				'main_id'  	=> NULL,
				'name'  	=> "User Guide",
				'url'  		=> "guidance/data",
				'icon'  	=> 'question-circle',
				'privileges'=> '[11]'
			),
			array(
				'id'  		=> 7,
				'sequence'  => 5,
				'type'  	=> "Single",
				'main_id'  	=> NULL,
				'name'  	=> "Document Standard",
				'url'  		=> "docsop/data",
				'icon'  	=> 'question-circle',
				'privileges'=> '[11]'
			),
			array(
				'id'  		=> 8,
				'sequence'  => 2,
				'type'  	=> "Sub",
				'main_id'  	=> 1,
				'name'  	=> "Data Report",
				'url'  		=> "dashboard/data/report",
				'icon'  	=> 'clipboard',
				'privileges'=> '[1, 2, 8]'
			),
			array(
				'id'  		=> 9,
				'sequence'  => 1,
				'type'  	=> "Sub",
				'main_id'  	=> 1,
				'name'  	=> "Quick Search",
				'url'  		=> "search/data/search",
				'icon'  	=> 'search',
				'privileges'=> '[1, 2, 8]'
			),
			array(
				'id'  		=> 10,
				'sequence'  => 10,
				'type'  	=> "Main",
				'main_id'  	=> NULL,
				'name'  	=> "Bulk SMS",
				'url'  		=> "#",
				'icon'  	=> 'icon-envelope',
				'privileges'=> '[1, 2, 3]'
			),
			array(
				'id'  		=> 11,
				'sequence'  => 11,
				'type'  	=> "Sub",
				'main_id'  	=> 10,
				'name'  	=> "Send Quick SMS",
				'url'  		=> "sms/data/quick",
				'icon'  	=> 'icon-rocket',
				'privileges'=> '[1, 2, 3]'
			),
			array(
				'id'  		=> 12,
				'sequence'  => 12,
				'type'  	=> "Sub",
				'main_id'  	=> 10,
				'name'  	=> "Send Bulk SMS",
				'url'  		=> "sms/data/bulk",
				'icon'  	=> 'icon-paper-plane',
				'privileges'=> '[1, 2, 3]'
			),
			array(
				'id'  		=> 13,
				'sequence'  => 13,
				'type'  	=> "Sub",
				'main_id'  	=> 10,
				'name'  	=> "Send Schedule SMS",
				'url'  		=> "sms/data/schedule",
				'icon'  	=> 'icon-calendar',
				'privileges'=> '[1, 2, 3]'
			),
			array(
				'id'  		=> 14,
				'sequence'  => 14,
				'type'  	=> "Sub",
				'main_id'  	=> 10,
				'name'  	=> "Send SMS From File",
				'url'  		=> "sms/data/file",
				'icon'  	=> 'icon-paper-clip',
				'privileges'=> '[1, 2, 3]'
			),
			array(
				'id'  		=> 15,
				'sequence'  => 15,
				'type'  	=> "Sub",
				'main_id'  	=> 10,
				'name'  	=> "SMS History",
				'url'  		=> "sms/data/history",
				'icon'  	=> 'icon-clock',
				'privileges'=> '[1, 2, 3]'
			),
			array(
				'id'  		=> 16,
				'sequence'  => 16,
				'type'  	=> "Sub",
				'main_id'  	=> 10,
				'name'  	=> "SMS Otomatis",
				'url'  		=> "sms/data/otomatis",
				'icon'  	=> 'icon-loop',
				'privileges'=> '[1, 2, 3]'
			),
			array(
				'id'  		=> 17,
				'sequence'  => 1,
				'type'  	=> "Main",
				'main_id'  	=> 0,
				'name'  	=> "Clients",
				'url'  		=> "#",
				'icon'  	=> 'icon-people',
				'privileges'=> '[1, 2]'
			),
			array(
				'id'  		=> 18,
				'sequence'  => 2,
				'type'  	=> "Sub",
				'main_id'  	=> 17,
				'name'  	=> "Groups",
				'url'  		=> "groups/data",
				'icon'  	=> 'icon-people',
				'privileges'=> '[1, 2]'
			),
			array(
				'id'  		=> 19,
				'sequence'  => 3,
				'type'  	=> "Sub",
				'main_id'  	=> 17,
				'name'  	=> "All Clients",
				'url'  		=> "clients/data",
				'icon'  	=> 'icon-user',
				'privileges'=> '[1, 2]'
			),
			array(
				'id'  		=> 20,
				'sequence'  => 4,
				'type'  	=> "Main",
				'main_id'  	=> 0,
				'name'  	=> "Contacts",
				'url'  		=> "#",
				'icon'  	=> 'icon-notebook',
				'privileges'=> '[1, 2]'
			),
			array(
				'id'  		=> 21,
				'sequence'  => 5,
				'type'  	=> "Sub",
				'main_id'  	=> 20,
				'name'  	=> "Phonebooks",
				'url'  		=> "phonebooks/data",
				'icon'  	=> 'icon-notebook',
				'privileges'=> '[1, 2]'
			),
			array(
				'id'  		=> 22,
				'sequence'  => 6,
				'type'  	=> "Sub",
				'main_id'  	=> 20,
				'name'  	=> "Contacts",
				'url'  		=> "contacts/data",
				'icon'  	=> 'icon-loop',
				'privileges'=> '[1, 2]'
			),
			array(
				'id'  		=> 23,
				'sequence'  => 7,
				'type'  	=> "Sub",
				'main_id'  	=> 20,
				'name'  	=> "Blacklist",
				'url'  		=> "blacklist/data",
				'icon'  	=> 'icon-shield',
				'privileges'=> '[1, 2]'
			),
			array(
				'id'  		=> 24,
				'sequence'  => 8,
				'type'  	=> "Sub",
				'main_id'  	=> 20,
				'name'  	=> "Spam Words",
				'url'  		=> "spamwords/data",
				'icon'  	=> 'icon-speech',
				'privileges'=> '[1, 2]'
			),
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
