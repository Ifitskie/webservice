<?php
require_once "config.php";

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    header("Accept: application/json");
    $query = "SELECT * FROM book WHERE id = '" . $id . "'";
    $results = mysqli_query($connect, $query);
    $resultArray = [];
    $accept = $_SERVER["HTTP_ACCEPT"];

    if (mysqli_num_rows($results) > 0) {

        if ($accept == "application/json") {
            header("Content-Type: application/json");
            while ($row = mysqli_fetch_assoc($results)) {

                $links = array();

                $link = array();
                $link["rel"] = "self";
                $link["href"] = "https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/" . $row["id"];

                $linkcollection = array();
                $linkcollection["rel"] = "collection";
                $linkcollection["href"] = "https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/";

                array_push($links, $link);
                array_push($links, $linkcollection);

                $row["links"] = $links;

                echo json_encode($row);

                http_response_code(200);
                exit;
            }

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
                $xml .= "<href>https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/" . $book["id"] . "</href>";
                $xml .= "</link>";
                $xml .= "<link>";
                $xml .= "<rel>collection</rel>";
                $xml .= "<href>https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/</href>";
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
    } else {
        http_response_code(404);
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "PUT"){
    $content = $_SERVER["CONTENT_TYPE"];

    if ($content == "application/json") {
        $body = file_get_contents("php://input");
        $json = json_decode($body);

        $author =(isset($json->Author)) ? $json->Author : "";
        $title =(isset($json->Title)) ? $json->Title : "";
        $series =(isset($json->Series)) ? $json->Author : "";
        $haveBook =(isset($json->HaveBook)) ? $json->Author : "";


        if ($author == "" || $title == "" || $series == "") {
            http_response_code(403);
        } else {
           if ($haveBook == "") {
                $query = "UPDATE book SET Title ='".$title."', Author ='".$author."', Series ='".$series."' WHERE id='".$id."'";

            } else {
                $query = "UPDATE book SET Title ='".$title."', Author ='".$author."', Series ='".$series."', HaveBook ='".$haveBook."' WHERE id='".$id."'";
           }


//            echo $query;
            mysqli_query($connect, $query);
            http_response_code(200);
        }
        exit;
    } elseif ($content == "application/x-www-form-urlencoded") {
        if ($_POST["Title"] == null || $_POST["Author"] == null || $_POST["Series"] == null) {
            http_response_code(403);
        } else {

            if ($_POST["HaveBook"] == null) {
                $query = "UPDATE book SET Title ='". $_POST["Title"]."', Author ='".$_POST["Author"]."', Series ='".$_POST["Series"]."' WHERE id='".$id."'";
            } else {
                $query = "UPDATE book SET Title ='". $_POST["Title"]."', Author ='".$_POST["Author"]."', Series ='".$_POST["Series"]."', HaveBook ='".$_POST["HaveBook"]."' WHERE id='".$id."'";
            }


            mysqli_query($connect, $query);
            http_response_code(200);
        }
    } else {
        http_response_code(403);
    }
}
elseif ($_SERVER["REQUEST_METHOD"] == "DELETE"){
    $query = "DELETE FROM book WHERE id = '".$id."'";
    mysqli_query($connect, $query);
    http_response_code(204);
}
elseif ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    header("Allow: GET, PUT, DELETE, OPTIONS");
    exit;
} else {
    http_response_code(403);
}