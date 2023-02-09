<?php
// import Config File
require_once('../config/config.php');

if (!isset($_SESSION)) {
    session_start();
}

// Processing form data when form is submitted                
if (isset($_POST['status'])) {

    $status = $_POST['status'];

    // return error if both are empty
    if (empty(trim($_POST["status"]))) {
        $entries_err = '<div class="alert alert-danger text-center mx-auto">
                            Please enter a status.</div>';
    }

    if (!empty(trim($_POST["status"]))) {
        $sql = "SELECT visits.*, properties.title AS title, properties.property_description AS property_description, properties.price AS price, properties.property_image AS property_image  FROM visits JOIN properties ON visits.property = properties.id WHERE status='" . $status . "' ORDER BY visits.reg_date DESC";
        $sql = "SELECT visits.*, properties.id AS property_id, properties.title AS title, properties.is_taken AS property_is_taken, properties.property_description AS property_description, properties.price AS price, properties.property_image AS property_image, managers.firstname AS firstname_manager , managers.lastname AS lastname_manager, users.firstname AS firstname_user , users.lastname AS lastname_user  FROM visits JOIN properties ON visits.property = properties.id JOIN managers ON visits.manager = managers.id JOIN users ON visits.user = users.id WHERE status='" . $status . "' ORDER BY visits.reg_date DESC";

        $list = $conn->query($sql);

        if ($list->num_rows > 0) {
            while ($row = $list->fetch_assoc()) {
                $title = mb_convert_case($row["title"], MB_CASE_TITLE, "UTF-8"); // Change title to title case
                $id = $row["id"];
                // description should not be more than 120 characters
                if (strlen($row["property_description"]) > 110) {
                    $description = substr($row["property_description"], 0, 110);
                    $description = $description . "...";
                } else {
                    $description = $row["property_description"];
                }
                $price = $row["price"];
                $imageData = $row['property_image'];
                $imageData = base64_encode($imageData);
                $datePosted = $row["reg_date"];
                $firstname_user = $row["firstname_user"];
                $lastname_user = $row["lastname_user"];
                $firstname_manager = $row["firstname_manager"];
                $lastname_manager = $row["lastname_manager"];
                $visit_date = $row["visit_date"];
                $visit_time = $row["visit_time"];
                $visit_status = $row["status"];
                if (strlen($row["note"]) > 100) {
                    $note = substr($row["note"], 0, 100);
                    $note = $note . "...";
                } else {
                    $note = $row["note"];
                }
                $property_id = $row["property_id"];
                $property_is_taken = $row["property_is_taken"];

                echo '<div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 p-1">
                            <div class="card">
                                <span style="background-image: url(data:image/jpeg;base64,' . $imageData . ');
                                    background-size: cover;
                                    background-repeat: no-repeat;
                                    background-position: center center;
                                    width: 100%;
                                    border-radius: 3px 3px 0px 0px;
                                    min-height: 200px;">           
                                </span>';

                if ($property_is_taken === "1") {
                    echo '<img src="./images/sold.svg" alt="Sold SVG Image" class="left-0 position-absolute">';
                }

                if ($visit_status === "pending") {
                    echo '<div class="right-0 position-absolute p-1"><span class="badge bg-primary">Pending</span></div>';
                } else if ($visit_status == "rejected") {
                    echo '<div class="right-0 position-absolute p-1"><span class="badge bg-danger">Rejected</span></div>';
                } else if ($visit_status == "cancelled") {
                    echo '<div class="right-0 position-absolute p-1"><span class="badge bg-warning">Cancelled</span></div>';
                } else if ($visit_status == "completed") {
                    echo '<div class="right-0 position-absolute p-1"><span class="badge bg-success">Completed</span></div>';
                }
                ;

                echo '<div class="card-body">
                                    <p class="text_muted"><small><i>Posted : ' . date("F j, Y, g:i a", strtotime($datePosted)) . '</i></small></p>
                                    <h5 class="card-title">' . $title . '</h5>
                                    <h6>Price: USD ' . number_format($price) . '</h6>
                                    <p class="card-text">
                                        ' . $description . '
                                    </p><h6>Manager : ' . $firstname_manager . ' ' . $lastname_manager . '</h6><hr />
                                    <h6>Client : ' . $firstname_user . ' ' . $lastname_user . '</h6>
                                    <h6>Scheduled Date : ' . date("F jS, Y", strtotime($visit_date)) . '</h6><h6>Scheduled Time : ' . date("h:i a", strtotime($visit_time)) . '</h6><p class="card-text"> ' . $note . '</p>';
                if ($visit_status === "pending" && $property_is_taken === "0") {
                    echo '<button class="btn btn-danger w-100 rejectButton" value="' . $row["id"] . '" data-currentStatus="' . $status . '" data-manager="' . $_SESSION['id'] . '" >Reject Visit</button>';
                }

                echo '</div> </div> </div>';
            }
        } else {
            echo "<div class='w-100 text-center py-5 display-1'>No Results found</div>";
        }

    } else {
        echo "<div class='w-100 text-center py-5 display-1'>Error in status</div>";
    }
}


?>