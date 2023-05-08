<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_code extends CI_Migration
{
    private $version = 20230506212341;
    private $table_use_list = array();

    public function __construct()
    {
        $this->table_total_list = $this->config->config['table_info_list'];
        if(count($this->table_use_list) === 0){
            $this->table_use_list = array_keys($this->table_total_list);
        }
    }

    public function up()
    {
        foreach ($this->table_use_list as $table_name) {
            if ($this->db->table_exists($table_name) )
            {
                // table exists
            }
            else
            {
                // table does not exist
                $this->add_table($table_name);
            }
        }
    }

    public function down()
    {
        foreach ($this->table_use_list as $table_name) {
            $this->dbforge->drop_table($table_name);
        }
    }

    protected function add_table($table_name)
    {
        $table_info = $this->get_table_info($table_name);

        if($table_info) {
            $this->dbforge->add_field($table_info['fields']);
            $this->dbforge->add_key($table_info['primary_keys'], true);
            $this->dbforge->create_table($table_name);
        }
    }

    protected function get_table_info($table_name)
    {
        if(is_scalar($table_name) && array_key_exists($table_name, $this->table_total_list)){
            return $this->table_total_list[$table_name];
        }else{
            return null;
        }
    }
}