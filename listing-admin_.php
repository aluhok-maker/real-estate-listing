<?php
// import Config File
require('./config/config.php');

?>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- CSS LINKS -->
    <link rel="stylesheet" href="./css/style.css" />

    <!-- BOOTSTRAP CSS LINKS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

    <!-- BOOTSTRAP JS LINKS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <!-- JQUERY LINK -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>Equitable Property Group</title>

    <script>
        $(document).ready(function () {
            $("#search-form").submit(function (e) {
                e.preventDefault();
                var property_name = $("property_name").val();
                var location_id = $('#location_id:selected').val()
                $.ajax({
                    url: "./utils/search_property_admin.php",
                    type: "post",
                    data: $('#search-form').serialize(),
                    success: function (response) {
                        $("#results").html(response);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // handle error
                        console.log({ jqXHR, textStatus, errorThrown });
                    }

                })
            });
        });
    </script>


</head>

<body>
    <div class="position-relative">

        <!-- import the header section-->
        <?php include './components/header.php'; ?>

        <div class="position-relative">

            <div class="pb-5 mb-5">
                <!-- start seacrh section -->
                <div class="container pt-5">
                    <form class="row gx-3 gy-2 align-items-center" method="post" id="search-form">
                        <div class="col-sm-5">
                            <!-- <label for="specificSizeInputName">Search by Name</label> -->
                            <input type="text" class="form-control" id="property_name" name="property_name"
                                placeholder="Search by Name" />
                        </div>
                        <div class="col-sm-5">
                            <!-- <label for="specificSizeInputName">Search by Location</label> -->
                            <select name="location_id" id="location_id" class="form-control <?php if (!empty($location_err))
                                echo "border-danger"; ?>">
                                <option value="">Select Property Location</option>
                                <?php


                                $table = "locations";
                                $check_locations_list = $conn->query("SELECT * FROM $table");
                                if ($check_locations_list->num_rows > 0) {
                                    while ($row = $check_locations_list->fetch_assoc()) {
                                        $name = mb_convert_case($row["name"], MB_CASE_TITLE, "UTF-8");
                                        $id = $row["id"];
                                        echo "<option value=" . $id . ">" . $name . "</option>";
                                    }
                                } else {
                                    echo "No Locations found";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="submit" class="btn btn-primary w-100" name="submit" value="Submit" />
                        </div>
                    </form>
                </div>
                <!-- end search section -->

                <!-- start listing section -->
                <div class="container d-flex flex-wrap  pb-5 mb-5" id="results">

                    <?php
                    // redirect to user login if session is expired
                    if (!$_SESSION['auth_active']) {
                        echo '<script>alert("Your Session has expired please login")</script>';
                        echo '<script>setTimeout(function(){
                        window.location.href = "./auth/admin-login.php";
                    }, 1000);</script>';
                    }

                    ?>

                    <?php
                    $table = "properties";
                    $sql = "SELECT properties.*, locations.name AS location_name, locations.id AS location_id, managers.firstname AS manager_firstname,managers.lastname AS manager_lastname FROM properties JOIN locations ON properties.property_location = locations.id JOIN managers ON properties.manager = managers.id ORDER BY reg_date DESC";
                    $check_properties_list = $conn->query($sql);
                    if ($check_properties_list->num_rows > 0) {
                        while ($row = $check_properties_list->fetch_assoc()) {
                            $title = mb_convert_case($row["title"], MB_CASE_TITLE, "UTF-8");
                            $id = $row["id"];
                            $description = $row["property_description"];
                            $price = $row["price"];
                            $location = $row["location_name"];
                            $imageData = $row['property_image'];
                            $imageData = base64_encode($imageData);
                            $datePosted = $row["reg_date"];

                            $manager_firstname = mb_convert_case($row["manager_firstname"], MB_CASE_TITLE, "UTF-8");
                            $manager_lastname = mb_convert_case($row["manager_lastname"], MB_CASE_TITLE, "UTF-8");


                            echo '<div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 p-1">
                            <div class="card">
                                <span style="background-image: url(data:image/jpeg;base64,' . $imageData . ');
                                    background-size: cover;
                                    background-repeat: no-repeat;
                                    background-position: center center;
                                    width: 100%;
                                    border-radius: 3px 3px 0px 0px;
                                    min-height: 200px;">
                                </span>
                                <div class="card-body">
                                    <p class="card-title"><small><i>Posted : ' . date("F j, Y, g:i a", strtotime($datePosted)) . '</i></small></p>
                                    <h5 class="card-title">' . $title . '</h5>
                                    <h6>Location: ' . $location . '</h6>
                                    <h6>Price: USD ' . number_format($price) . '</h6>
                                    <p class="card-text">
                                        ' . $description . '
                                    </p>
                                    <h6>Manager: ' . $manager_firstname . ' ' . $manager_lastname . '</h6>
                                    <form method="post" action="./utils/delete_property.php">
                                        <input type="hidden" name="id" value=' . $id . '>
                                        <input type="submit" name="delete" id="delete" value="DELETE" class="btn btn-danger w-100"/><br/>
                                    </form>
                                </div>
                            </div>
                        </div>';
                        }
                    } else {
                        echo "No Locations found";
                    }

                    //  close the connection
                    $conn->close();
                    ?>

                </div>
                <!-- end lisitng section -->
                
            </div>

        </div>

        <!-- import the footer section-->
        <?php
        include './components/footer.php';
        ?>

    </div>
</body>

</html>