<?php
include "config.php";

$connect = mysqli_connect($db_host, $db_user, $db_password, $db_database);

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $query = "SELECT id,Title,Author,Series FROM book";
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
        foreach ($results as $book){
            $xml .= "<book>";
                $xml .= "<Title>".$book["Title"]."</Title>";
                $xml .= "<Author>". $book["Author"]."</Author>";
                $xml .= "<Series>".$book["Series"]."</Series>";
            $xml.="</book>";
            $xml .= "<links>";
                $xml .= "<link>";
                    $xml .= "<rel>self</rel>";
                    $xml .= "<href>http://localhost/hro/webservice/books/" . $book["id"]."</href>";
                $xml .= "</link>";
                $xml .= "<link>";
                   $xml .= "<rel>Collection</rel>";
                   $xml .= "<href>http://localhost/hro/webservice/books/</href>";
                $xml .= "</link>";
            $xml .= "</links>";
        }
        $xml .= "</books>";
//        $xml .="<pagination>";
//            $xml .="<currentPage>1</currentPage>";
//            $xml .="<currentItems>15</currentItems>";
//            $xml .="<totalPages>1</totalPages>";
//            $xml .="<totalItems>103</totalItems>";
//            $xml .= "<links>";
//                $xml .= "<link>";
//                    $xml .= "<rel>first</rel>";
//                    $xml .= "<href>http://localhost/hro/webservice/books/</href>";
//                $xml .= "</link>";
//                $xml .= "<link>";
//                    $xml .= "<rel>last</rel>";
//                    $xml .= "<href>http://localhost/hro/webservice/books/</href>";
//                $xml .= "</link>";
//                $xml .= "<link>";
//                    $xml .= "<rel>previous</rel>";
//                    $xml .= "<href>http://localhost/hro/webservice/books/</href>";
//                $xml .= "</link>";
//                $xml .= "<link>";
//                    $xml .= "<rel>next</rel>";
//                    $xml .= "<href>http://localhost/hro/webservice/books/</href>";
//                $xml .= "</link>";
//            $xml .= "</links>";
//        $xml.= "</pagination>";

        echo $xml;
        http_response_code(200);
        exit;
    } else {
        http_response_code(403);
        exit;
    }

} elseif
($_SERVER["REQUEST_METHOD"] == "POST"
) {

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

            $query = "INSERT INTO book (Title, Author, Series, HaveBook) VALUES ('" . $json->Title . "','" . $json->Author . "','" . $json->Series . "','" . $json->HaveBook . "')";
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

            $query = "INSERT INTO book (Title, Author, Series, HaveBook) VALUES ('" . $_POST["Title"] . "','" . $_POST["Author"] . "','" . $_POST["Series"] . "','" . $_POST["HaveBook"] . "')";
            mysqli_query($connect, $query);
            http_response_code(201);
        }
    } else {
        http_response_code(403);
    }
} elseif
($_SERVER["REQUEST_METHOD"] == "OPTIONS"
) {
    header("Allow: GET, POST, OPTIONS");
    exit;
} else {
    http_response_code(403);
}