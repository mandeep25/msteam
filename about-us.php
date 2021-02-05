<?php

include("include/config.php");

include("include/functions.php");

if (isset($_POST['submitzip']) && $_POST['submitzip'] == 'Submit') {

    $zip = $_POST['zip'];

    $zipstore = $obj->query("select z.storeid,z.state,z.city from tbl_store_zip z left join stores s on z.storeid=s.storeid where z.zip='$zip' and s.sectionid like '%1%' and z.zone_status=1 and z.store_status=1 and z.status=1 and s.status=1");

    $record = $obj->numRows($zipstore);

    if ($record > 0) {

        $ziprec = $obj->fetchNextObject($zipstore);

        setcookie('postalcode', $zip, time() + (86400 * 30 * 12), "/"); // 86400 = 1 day

        zip_redirect($ziprec->city, $ziprec->state);

    } else {

        setcookie('meal-kit-zone', '1', time() + (86400 * 30 * 12), "/"); // 86400 = 1 day
		setcookie('postalcode', $zip, time() + (86400 * 30 * 12), "/"); // 86400 = 1 day
		header("Location:" . SITE_URL . "indian-meal-kit-delivery");

        exit;

    }

}

?>

<!DOCTYPE html>

<html lang="en" class=" js no-touch localstorage">

    <head>



        <?php include("metas.php"); ?>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>



        <link rel="stylesheet" type="text/css" href="css/stylesheet/bootstrap.min.css">

        <link rel="stylesheet" type="text/css" href="css/stylesheet/font-awesome.css">

        <link rel="stylesheet" type="text/css" href="css/stylesheet/style.css?ver=1.1">

        <?php include("common-head.php"); ?>

        <?php include("css.php"); ?>

    </head>

    <body>

        <?php include("header.php"); ?>

        <?php include("cart.php"); ?>

        <div id="searchhide">

            <div class="page-heading" style="background:url(images/aboutusbannerimg.jpeg) 100%; background-repeat:no-repeat; background-size:cover">

                <div class="container">

                    <div class="row">

                        <div class="col-xs-12">

                            <div class="page-title">

                                <br><br><br>

                                <h1>About Us</h1>

                            </div>

                        </div>

                        <!--col-xs-12-->

                    </div>

                    <!--row-->

                </div>

                <!--container-->

            </div>

            <!-- BEGIN Main Container -->           

            <?php echo getContent('What is MyValue 365 ?'); ?>

            <!--main-container-->

            <!--col1-layout-->

            <div class="clearfix"></div><br>

            <div class="container">

                <div id="about-us-img">              

                    <div class="col-lg-4 col-md-4 col-sm-12"> <img src="images/aboutus-1.png" class="img-responsive" alt="Online grocery store in Chicago">  </div>

                    <div class="col-lg-4 col-md-4 col-sm-12"> <img src="images/aboutus-2.png" class="img-responsive" alt="same day grocery delivery"> </div> 

                    <div class="col-lg-4 col-md-4 col-sm-12"> <img src="images/aboutus-3.png" class="img-responsive" alt="Free grocery delivery in chicago "> </div>      

                </div>          

                <!--main-container-->        

            </div> <!--col1-layout-->

        </div>

        <div class="clearfix"></div><br>

        <?php include("footer.php"); ?>

        <?php include("js.php"); ?>

        <script type="text/javascript" src="<?php echo getFileVer('js/cart.js'); ?>" ></script>

        <?php

        if (!isset($_COOKIE['postalcode'])) {

            ?>

            <script>

                $(document).ready(function () {

                    $("#subscribe").toggleClass('show')

                });

            </script>

            <?php

        }

        ?>

    </body>

</html>