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
class Migration_create_table_sms_curl_log extends CI_Migration {


	protected $table = 'sms_curl_log';


	public function up()
	{
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE
			],
			'uri' => [
				'type' => 'TEXT'
			],
			'method'  => [
				'type' => 'VARCHAR(50)'
			],
			'params'  => [
				'type' => 'TEXT'
			],
			'response'  => [
				'type' => 'TEXT'
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

		//create api logs table
		$this->db->query(
			"CREATE TABLE `sms_api_log` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`uri` VARCHAR(255) NOT NULL,
				`method` VARCHAR(6) NOT NULL,
				`params` TEXT DEFAULT NULL,
				`api_key` VARCHAR(40) NOT NULL,
				`ip_address` VARCHAR(45) NOT NULL,
				`time` INT(11) NOT NULL,
				`rtime` FLOAT DEFAULT NULL,
				`authorized` VARCHAR(1) NOT NULL,
				`response_code` smallint(3) DEFAULT '0',
					  `response` TEXT DEFAULT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;" 
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
