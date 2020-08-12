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
class Migration_create_table_configs extends CI_Migration {


	protected $table = 'configs';


	public function up()
	{
		$fields = array(
			'id' => [
				'type' => 'INT(11)',
				'auto_increment' => TRUE
			],
			'name' => [
				'type' => 'VARCHAR(50)'
			],
			'value' => [
				'type' => 'VARCHAR(50)'
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
			],
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table($this->table, TRUE);

		//db seed
		$data = array(
			array(
				'name' => "title",
				'value' => "SMS Digital"
			),
			array(
				'name' => "logo",
				'value' => "assets/images/logo.png"
			),
			array(
				'name' => "icon",
				'value' => "assets/adminlte/dist/img/favicon.ico"
			),
			array(
				'name' => "bg_login",
				'value' => "assets/images/bg_login.jpg"
			),
			array(
				'name' => "smtp_user",
				'value' => "fasso01"
			),
			array(
				'name' => "smtp_pass",
				'value' => "1nfomedi@"
			),
			array(
				'name' => "smtp_port",
				'value' => "465"
			),
			array(
				'name' => "smtp_server",
				'value' => "smtp.infomedia.co.id"
			),
			array(
				'name' => "smtp_crypto",
				'value' => "ssl"
			),
			array(
				'name' => "smtp_sender",
				'value' => "helpdesk.fasso@infomedia.co.id"
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
