<?php


class databaseConnection //create a class for make connection
 {

   
    var $host = "localhost";
    var $username = "root";    // specify the sever details for mysql
    var $password="nana@1991";
    var $database = "ourwedding";//vso
    var $myconn;








    public function connectToDatabase() { // create a function for connect database
        $conn = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        mysqli_query($conn, "set @@session.tx_isolation='READ-UNCOMMITTED");

        // conn.commit(True);
        return $conn;
    }

    function selectDatabase($conn) { // selecting the database.
        return $conn;
    }

    function closeConnection($conn) { // close the connection
        mysqli_close($conn);
    }



    

   }
?>
