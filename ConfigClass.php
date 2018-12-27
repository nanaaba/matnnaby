<?php

if (!isset($_SESSION)) {
    session_start();
}
require_once './databaseConnection.php';

class ConfigClass {

    //put your code here
    //put your code here
    public function adduserviewer() {
        $connection = new databaseConnection(); //i created a new object
        $conn = $connection->connectToDatabase(); // connected to the database

        $ipaddress = $this->getRealIpAddr();
        $result = $this->checkipexistence($ipaddress);
        $useragent=$this->isMobile();

        if ($result == 0) {
            mysqli_query($conn, "INSERT INTO site_viewers(ipaddress,username,totalviews) VALUES ('" . trim($ipaddress) . "','".$useragent."',1)");
        } else {
            $user_views = $this->getuserviews($ipaddress) + 1;
            $current_time = date("Y-m-d h:i:s");
            mysqli_query($conn, "UPDATE site_viewers SET totalviews='" . $user_views . "',date_last='" . $current_time . "'    WHERE ipaddress='" . mysqli_real_escape_string($conn, $ipaddress) . "'");
        }
    }

    public function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
    public function getuserviews($ipaddress) {
        $connection = new databaseConnection();
        $conn = $connection->connectToDatabase();


        $query = mysqli_query($conn, "SELECT totalviews from site_viewers WHERE ipaddress = '$ipaddress' ");

        if ($query) {
            $row = mysqli_fetch_assoc($query);
            $num = $row['totalviews'];
        } else {
            echo mysqli_error($conn);
        }

        return $num;
    }

    private function checkipexistence($ipaddress) {
        $connection = new databaseConnection(); //i created a new object
        $conn = $connection->connectToDatabase(); // connected to the database
        $query = mysqli_query($conn, "SELECT * FROM site_viewers WHERE ipaddress LIKE '%" . $ipaddress . "%'");
        return mysqli_num_rows($query);
    }

    public function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

}
