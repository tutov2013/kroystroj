<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

class angerro_yadelivery_db {

    var $db;

    function angerro_yadelivery_db(){
        global $DB;
        $this->db = $DB;
    }

    /*
     * ФУНКЦИИ ДЛЯ АДМИНКИ
     */

    public function install_main_table()
    {
        $this->db->Query("CREATE TABLE IF NOT EXISTS angerro_yadelivery(
        id INTEGER NOT NULL auto_increment,
        name VARCHAR(256) NOT NULL,
        data mediumtext NOT NULL,
        PRIMARY KEY(id))
        ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;");
    }

    public function add_demo_data()
    {
        $example_data = '[{""map"":{""zoom"":12,""center"":[55.80283961247519,49.15166281709875],""map_id"":""'.GetMessage("DEF_MAP_NAME").'""},""delivery_areas"":[{""area_coordinates"":[[[55.82274457823194,49.075979447288],[55.827189612628125,49.15416630547058],[55.81752584352005,49.177512252736214],[55.80399251663948,49.18403538506044],[55.79529002524011,49.16755589287294],[55.79316976947244,49.11527060518467],[55.800905836785454,49.06480216036047],[55.82274457823194,49.075979447288]]],""settings"":{""title"":""'.GetMessage("DEF_MAP_ZONE").'"",""color"":""#8000ff""}}]}]';
        $this->db->Query('INSERT INTO angerro_yadelivery (name, data) values("'.GetMessage("DEF_MAP_NAME").'", "' . $example_data . '")');
    }

    public function unistall_main_table()
    {
        $this->db->Query("DROP TABLE IF EXISTS angerro_yadelivery");
    }

    public function get_map_list()
    {
        $ret = array();
        $result = $this->db->Query("SELECT id, name FROM angerro_yadelivery");
        while ($data = $result->GetNext()){
            $ret[] = $data;
        }
        return $ret;
    }

    public function get_data_by_map_id($id)
    {
        $ret = array();
        $ret = $this->db->Query('SELECT data FROM angerro_yadelivery WHERE id='.$id)->GetNext();
        return $ret['~data'];
    }

}
?>