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
				'privileges'=> '[1, 2, 8]'
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
