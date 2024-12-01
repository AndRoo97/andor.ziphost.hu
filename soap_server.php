<?php
require 'config.php';

class SoapServices {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function getData() {
        $response = [];

        $result = $this->db->query("SELECT * FROM belepes");
        $response['belepes'] = $result->fetch_all(MYSQLI_ASSOC);

        $result = $this->db->query("SELECT * FROM meccs");
        $response['meccs'] = $result->fetch_all(MYSQLI_ASSOC);

        $result = $this->db->query("SELECT * FROM nezo");
        $response['nezo'] = $result->fetch_all(MYSQLI_ASSOC);

        return $response;
    }
}

ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);

$server = new SoapServer(null, [
    'uri' => "http://andor.ziphost.hu/soap_server.php"
]);
$server->setClass('SoapServices');
$server->handle();
?>
