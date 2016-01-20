<?php
require_once "config.php";

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    header("Accept: application/json");
    $query = "SELECT * FROM book WHERE id = '".$id."'";
    $results = mysqli_query($connect, $query);
    $resultArray = [];

    header("Content-Type: application/json");
    while ($row = mysqli_fetch_assoc($results)) {

        $links = array();

        $link = array();
        $link["rel"] = "self";
        $link["href"] = "http://localhost/hro/webservice/books/" . $row["id"];

        $linkcollection = array();
        $linkcollection["rel"] = "collection";
        $linkcollection["href"] = "http://localhost/hro/webservice/books/";

        array_push($links, $link);
        array_push($links, $linkcollection);

        $row["links"] = $links;

        $resultArray[] = $row;
    }
    echo json_encode($resultArray);

    http_response_code(200);
    exit;

//    } elseif ($_SERVER["CONTENT_TYPE"] == "text/xml") {
//        header("Content-Type: text/xml");
//        echo "XML";
//        http_response_code(200);
//        exit;
//    } else {
//        http_response_code(403);
//    }

}
elseif ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    header("Allow: GET, PUT, DELETE, OPTIONS");
    exit;
}