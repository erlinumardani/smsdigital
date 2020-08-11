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
class Migration_create_table_persons extends CI_Migration {


	protected $table = 'persons';


	public function up()
	{
		$fields = array(
			'id' => [
				'type' => 'BIGINT(20)',
				'auto_increment' => TRUE
			],
			'fullname' => [
				'type' => 'VARCHAR(50)',
			],
			'gender' => [
				'type' => 'ENUM("Male","Female")',
				'default' => 'Male'
			],
			'bdate' => [
				'type' => 'DATE',
				'null' => TRUE
			],
			'email' => [
				'type' => 'VARCHAR(50)',
			],
			'phone' => [
				'type' => 'VARCHAR(25)',
				'null' => TRUE
			],
			'photo' => [
				'type' => 'TEXT',
				'null' => TRUE
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
				'fullname'  => "Super Admin",
				'gender'  	=> "Male",
				'email'  	=> "sadmin@yopmail.com",
				'bdate'  	=> "1988-01-22",
				'phone'  	=> "08123456789",
				'photo'  	=> "assets/adminlte/dist/img/user2-160x160.jpg"
			),
			array(
				'id'  		=> 2,
				'fullname'  => "Admin",
				'gender'  	=> "Male",
				'email'  	=> "admin@yopmail.com",
				'bdate'  	=> null,
				'phone'  	=> null,
				'photo'  	=> null
			),
			array(
				'id'  		=> 3,
				'fullname'  => "Client",
				'gender'  	=> "Male",
				'email'  	=> "client@yopmail.com",
				'bdate'  	=> null,
				'phone'  	=> null,
				'photo'  	=> null
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
