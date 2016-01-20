<?php
require_once "config.php";

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    header("Accept: application/json");
    $query = "SELECT * FROM book WHERE id = '" . $id . "'";
    $results = mysqli_query($connect, $query);
    $resultArray = [];
    $accept = $_SERVER["HTTP_ACCEPT"];

    if ($accept == "application/json") {
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

    } elseif ($accept == "application/xml") {
        header("Content-Type: application/xml");
        $xml = "<?xml version='1.0' encoding='UTF-8'?> <books>";
        foreach ($results as $book) {
            $xml .= "<book>";
            $xml .= "<Title>" . $book["Title"] . "</Title>";
            $xml .= "<Author>" . $book["Author"] . "</Author>";
            $xml .= "<Series>" . $book["Series"] . "</Series>";
            $xml .= "</book>";
            $xml .= "<links>";
            $xml .= "<link>";
            $xml .= "<rel>self</rel>";
            $xml .= "<href>http://localhost/hro/webservice/books/" . $book["id"] . "</href>";
            $xml .= "</link>";
            $xml .= "<link>";
            $xml .= "<rel>Collection</rel>";
            $xml .= "<href>http://localhost/hro/webservice/books/</href>";
            $xml .= "</link>";
            $xml .= "</links>";
        }
        $xml .= "</books>";

        echo $xml;
        http_response_code(200);
        exit;

    } else {
        http_response_code(403);
    }
}elseif ($_SERVER["REQUEST_METHOD"] == "PUT"){
    $content = $_SERVER["CONTENT_TYPE"];

    if ($content == "application/json") {
        $body = file_get_contents("php://input");
        $json = json_decode($body);

        if ($json->Title || $json->Author == null) {
            http_response_code(403);
        } else {
            if ($json->Series == "") {
                echo "Toegevoegd: ", $json->Title, " van ", $json->Author;
            } else {
                echo "Toegevoegd: ", $json->Title, " van ", $json->Author, " is deel van ", $json->Series, " status boek: ", $json->HaveBook;
            }

            $query = "UPDATE book SET Title ='".$json->Title."', Author ='".$json->Author."', Series ='".$json->Series."', HaveBook ='".$json->HaveBook."' WHERE id='".$id."'";
            mysqli_query($connect, $query);
            http_response_code(201);
        }
        exit;
    } elseif ($content == "application/x-www-form-urlencoded") {
        if ($_POST["Title"] == null ) {
            http_response_code(403);
        } elseif ($_POST["Author"] == null){
            http_response_code(403);
        } else {

            if ($_POST["Series"] == null) {
                echo "Toegevoegd: ", $_POST["Title"], " van ", $_POST["Author"];
            } else {
                echo "Toegevoegd: ", $_POST["Title"], " van ", $_POST["Author"], " is deel van ", $_POST["Series"], " status boek: ", $_POST["HaveBook"];
            }

            $query = "UPDATE book SET Title ='". $_POST["Title"]."', Author ='".$_POST["Author"]."', Series ='".$_POST["Series"]."', HaveBook ='".$_POST["HaveBook"]."' WHERE id='".$id."'";
            mysqli_query($connect, $query);
            http_response_code(201);
        }
    } else {
        http_response_code(403);
    }
}
elseif ($_SERVER["REQUEST_METHOD"] == "DELETE"){}
elseif ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    header("Allow: GET, PUT, DELETE, OPTIONS");
    exit;
} else {
    http_response_code(403);
}