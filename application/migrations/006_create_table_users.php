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
class Migration_create_table_users extends CI_Migration {


	protected $table = 'users';


	public function up()
	{
		$fields = array(
			'id'=> [
				'type' => 'BIGINT(20)',
				'auto_increment' => TRUE
			],
			'person_id' => [
				'type'   => 'BIGINT(20)'
			],
			'group_id' => [
				'type'   => 'INT(11)',
				'null' => TRUE
			],
			'company_code' => [
				'type'   => 'INT(11)',
				'null' => TRUE,
				'default' => 1001
			],
			'sms_limit' => [
				'type'   => 'INT(11)',
				'null' => TRUE
			],
			'role_id'  => [
				'type' => 'INT(11)',
				'null' => TRUE
			],
			'username'      => [
				'type'   => 'VARCHAR(50)'
			],
			'password'  => [
				'type' => 'TEXT'
			],
			'password_api'  => [
				'type' => 'TEXT'
			],
			'tenant_id' => [
				'type'   => 'INT(11)',
				'default' => 1
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
				'person_id'  	=> 1,
				'username'  	=> "sadmin@yopmail.com",
				'password'  	=> password_hash('infonusa', PASSWORD_BCRYPT, ['cost' => 10]),
				'password_api'  => password_hash('infonusa', PASSWORD_BCRYPT, ['cost' => 10]),
				'sms_limit'  	=> 0,
				'role_id'  		=> 1,
			),
			array(
				'person_id'  	=> 2,
				'username'  	=> "admin@yopmail.com",
				'password'  	=> password_hash('infonusa', PASSWORD_BCRYPT, ['cost' => 10]),
				'password_api'  => password_hash('infonusa', PASSWORD_BCRYPT, ['cost' => 10]),
				'sms_limit'  	=> 1000,
				'role_id'  		=> 2,
			),
			array(
				'person_id'  	=> 3,
				'username'  	=> "client@yopmail.com",
				'password'  	=> password_hash('infonusa', PASSWORD_BCRYPT, ['cost' => 10]),
				'password_api'  => password_hash('infonusa', PASSWORD_BCRYPT, ['cost' => 10]),
				'sms_limit'  	=> 100,
				'role_id'  		=> 3,
			)
			
		);
		$this->db->insert_batch($this->table, $data);

		//create view
		$this->db->query(
			"CREATE OR REPLACE VIEW `v_users` AS 
			SELECT
				`b`.`id` AS `user_id`,
				`b`.`person_id`,
				`b`.`username`,
				`b`.`role_id`,
				`b`.`group_id`,
				`b`.`sms_limit`,
				`b`.`tenant_id`,
				`c`.`name` AS `role_name`,
				a.*
			FROM
				persons AS a
				LEFT JOIN `users` AS `b` ON `a`.id = `b`.`person_id`
				LEFT JOIN `roles` AS `c` ON `b`.`role_id` = `c`.`id`" 
		);

	}


	public function down()
	{
		if ($this->db->table_exists($this->table))
		{
			$this->dbforge->drop_table($this->table);
		}
	}

}
