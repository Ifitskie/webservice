<?php
include "config.php";

$connect = mysqli_connect($db_host, $db_user, $db_password, $db_database);

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $query = "SELECT id,Title,Author,Series FROM book";
    $results = mysqli_query($connect, $query);
    $resultArray = [];
    $accept = $_SERVER["HTTP_ACCEPT"];

//get start
    if (isset($_GET['start'])) {
        if ($_GET['start'] > 1) {
            $start = $_GET['start'] - 1;
        } else {
            $start = 0;
        }
    } else {
        $start = 0;
    }


//get limit
    if (isset($_GET['limit'])) {
        $limit = (int)$_GET['limit'];
    } else {
        $limit = 10;
    }

//count total entries
    $countQuery = "SELECT * FROM book";
    $totalBooks = mysqli_query($connect, $countQuery);
    $total = (int)mysqli_num_rows($totalBooks);


//get entries with start and limit the amount
    $limitQuery = "SELECT id,Title,Author,Series FROM book LIMIT " . $start . ", " . $limit . "";
    $limitResults = mysqli_query($connect, $limitQuery);

//amount of pages
    $pages = ceil($total / $limit);
    if ($accept == "application/json") {
        header("Content-Type: application/json");

        $items = array();

        while ($row = mysqli_fetch_assoc($limitResults)) {

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

            $items[] = $row;
        }
        $pagination = array();

        $pagelinks = array();

            $firstpage = array();
            $firstpage["rel"] = "first";
            $firstpage["page"] = 1;
            $firstpage["href"] = "https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/";
            array_push($pagelinks, $firstpage);

            $lastpage = array();
            $lastpage["rel"] = "last";
            $lastpage["page"] = $pages;
            $lastpage["href"] = "https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/";
            array_push($pagelinks, $lastpage);

            $previouspage = array();
            $previouspage["rel"] = "previous";
            $previouspage["page"] = 1;
            $previouspage["href"] = "https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/";
            array_push($pagelinks, $previouspage);

            $nextpage = array();
            $nextpage["rel"] = "next";
            $nextpage["page"] = 1;
            $nextpage["href"] = "https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/";
            array_push($pagelinks, $nextpage);

        $pagination["currentPage"] = 1;
        $pagination["currentItems"] = $limit;
        $pagination["totalPages"] = $pages;
        $pagination["totalItems"] = $total;
        $pagination["links"] = $pagelinks;

        $links = array();

        $linkcollection = array();
        $linkcollection["rel"] = "self";
        $linkcollection["href"] = "https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/";

        array_push($links,$linkcollection);

        $resultArray["items"] = $items;
        $resultArray["pagination"] = $pagination;
        $resultArray["links"] = $links;

        echo json_encode($resultArray);

        http_response_code(200);
        exit;

    } elseif ($accept == "application/xml") {
        header("Content-Type: application/xml");

        $xml = "<?xml version='1.0' encoding='UTF-8'?> <books>";
        foreach ($results as $book) {
            $xml .= "<book>";
            $xml .= "<id>" . $book["id"] . "</id>";
            $xml .= "<Title>" . $book["Title"] . "</Title>";
            $xml .= "<Author>" . $book["Author"] . "</Author>";
            $xml .= "<Series>" . $book["Series"] . "</Series>";
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
            $xml .= "</book>";
        }
        $xml .= "</books>";

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

        //
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
        if ($_POST["Title"] == null) {
            http_response_code(403);
        } elseif ($_POST["Author"] == null) {
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



//        $xml .="<pagination>";
//            $xml .="<currentPage>1</currentPage>";
//            $xml .="<currentItems>15</currentItems>";
//            $xml .="<totalPages>1</totalPages>";
//            $xml .="<totalItems>103</totalItems>";
//            $xml .= "<links>";
//                $xml .= "<link>";
//                    $xml .= "<rel>first</rel>";
//                    $xml .= "<href>https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/</href>";
//                $xml .= "</link>";
//                $xml .= "<link>";
//                    $xml .= "<rel>last</rel>";
//                    $xml .= "<href>https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/</href>";
//                $xml .= "</link>";
//                $xml .= "<link>";
//                    $xml .= "<rel>previous</rel>";
//                    $xml .= "<href>https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/</href>";
//                $xml .= "</link>";
//                $xml .= "<link>";
//                    $xml .= "<rel>next</rel>";
//                    $xml .= "<href>https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/</href>";
//                $xml .= "</link>";
//            $xml .= "</links>";
//        $xml.= "</pagination>";

//if ($accept == "application/json") {
//    header("Content-Type: application/json");
//    while ($row = mysqli_fetch_assoc($results)) {
//
//        $links = array();
//
//        $link = array();
//        $link["rel"] = "self";
//        $link["href"] = "https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/" . $row["id"];
//
//        $linkcollection = array();
//        $linkcollection["rel"] = "collection";
//        $linkcollection["href"] = "https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/";
//
//        array_push($links, $link);
//        array_push($links, $linkcollection);
//
//        $row["links"] = $links;
//
//        $resultArray[] = $row;
//    }
//    echo json_encode($resultArray);
//
//    http_response_code(200);
//    exit;
//
//} elseif ($accept == "application/xml") {
//    header("Content-Type: application/xml");
//
//    $xml = "<?xml version='1.0' encoding='UTF-8' <books>";
//    foreach ($results as $book){
//        $xml .= "<book>";
//          $xml .= "<id>".$book["id"]."</id>"
//          $xml .= "<Title>".$book["Title"]."</Title>";
//          $xml .= "<Author>". $book["Author"]."</Author>";
//          $xml .= "<Series>".$book["Series"]."</Series>";
//          $xml .= "<links>";
//            $xml .= "<link>";
//                $xml .= "<rel>self</rel>";
//                $xml .= "<href>https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/" . $book["id"]."</href>";
//            $xml .= "</link>";
//            $xml .= "<link>";
//                $xml .= "<rel>Collection</rel>";
//                $xml .= "<href>https://stud.hosted.hr.nl/0892682/jaar2/webservice/books/</href>";
//            $xml .= "</link>";
//          $xml .= "</links>";
//        $xml.="</book>";
//    }
//   $xml .= "</books>";
//
//    echo $xml;
//    http_response_code(200);
//    exit;
//} else {
//    http_response_code(403);
//    exit;
//}