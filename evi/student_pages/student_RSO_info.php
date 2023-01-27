<?php

require('../db_connection.php');
session_start();

# If Student has not logged in yet, direct the user to the index/login page 
if (isset($_SESSION['student_login_successful']) == null && $_SESSION['student_login_successful'] == false) {

    echo "
        <script>
            alert('Please Login To Access This Page'); 
            window.location.href='../index.php'; 
        </script>
        ";
}


if (isset($_GET['name']) && isset($_GET['university']) && isset($_GET['rso'])) {

    $query = "SELECT * FROM `rso_events` WHERE `name`='$_GET[name]' AND `university`='$_GET[university]' AND `rso_name`='$_GET[rso]'";
    $result = mysqli_query($con, $query);

    if ($result) {

        $fetch_result = mysqli_fetch_assoc($result);

        $university = $fetch_result['university'];
        $rso_name = $fetch_result['rso_name'];
        $event_name = $fetch_result['name'];
        $event_date = $fetch_result['date'];
        $event_time_from = $fetch_result['time_from'];
        $event_time_to = $fetch_result['time_to'];
        $event_category = $fetch_result['category'];
        $event_description = $fetch_result['description'];
        $event_location = $fetch_result['location'];
        $event_email = $fetch_result['email'];
        $event_phone = $fetch_result['phone'];

        $new_event_location = str_replace(' ', '+', $event_location);

        $new_university = str_replace(' ', '+', $fetch_result['university']);
        $out_university = str_replace(' ', '%20', $fetch_result['university']);
        $new_rso_name = str_replace(' ', '+', $fetch_result['rso_name']);
        $out_rso_name = str_replace(' ', '%20', $fetch_result['rso_name']);
        $new_name = str_replace(' ', '+', $fetch_result['name']);
        $out_name = str_replace(' ', '%20', $fetch_result['name']);
        $new_date = str_replace('-', '', $fetch_result['date']);
        $new_time_from = str_replace(':', '', $fetch_result['time_from']);
        $new_time_to = str_replace(':', '', $fetch_result['time_to']);
        $new_category = str_replace(' ', '+', $fetch_result['category']);
        $out_category = str_replace(' ', '%20', $fetch_result['category']);
        $new_description = str_replace(' ', '+', $fetch_result['description']);
        $out_description = str_replace(' ', '%20', $fetch_result['description']);
        $new_location = str_replace(' ', '+', $fetch_result['location']);
        $out_location = str_replace(' ', '%20', $fetch_result['location']);
        $admin_email = $fetch_result['email'];
        $admin_phone = $fetch_result['phone'];
    }

   
}

$rate_query = "SELECT * FROM `event_rating` WHERE `event_type`='2' AND `event_name`='$event_name' AND `university_name`='$university' AND `username`='$_SESSION[username]'";
$result_rate_query = mysqli_query($con, $rate_query);

if ($result_rate_query) {

    $fetch_rate = mysqli_fetch_assoc($result_rate_query);

    $user_rating = $fetch_rate['rate'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSO Event Information</title>
    <link rel="stylesheet" href="css/student_RSO_info.css" />
</head>

<body>

    <!-- For Posting to Facebook -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v13.0" nonce="WhjTk4Tv"></script>

    <!-- For Posting to Twitter -->
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

    <header>
        <div class="header">
            <button onClick="location.href = '../logout.php'"><img src="../images/logout.png"></button>

            <div class="title">
                <h1>RSO Event Information</h1>
                <h2>For <?php echo $event_name ?></h2>
                <h2>at <?php echo $university ?></h2>
            </div>

            <button onClick="location.href = 'student_home.php'"><img src="../images/home.png"></button>
        </div>
    </header>

    <div class="event_info">
        <div class="basic">
            <label for="event_name">Event Name</label>
            <h4 id="event_name">
                <?php echo $event_name ?>
            </h4>

            <label for="event_date">Event Date</label>
            <h4 id="event_date">
                <?php echo $event_date ?>
            </h4>

            <label for="event_time">Event Time</label>
            <h4 id="event_time">
                <?php
                echo $event_time_from;
                echo "-";
                echo $event_time_to;
                ?>
            </h4>

            <label for="event_category">Event Category</label>
            <h4 id="event_category">
                <?php echo $event_category ?>
            </h4>

            <label for="event_location">Event Location</label>
            <h4 id="event_location">
                <?php echo $event_location ?>
            </h4>

            <label for="open_maps">Event Location Maps</label>
            <a id="open_maps" href="https://www.google.com/maps/search/?api=1&query=<?php echo $new_event_location ?>" target="_blank" rel="noopener noreferrer">
                Open Google Maps
            </a>

            <label for="event_email">Contact Email</label>
            <h4 id="event_email">
                <?php echo $event_email ?>
            </h4>

            <label for="event_phone">Contact Phone</label>
            <h4 id="event_phone">
                <?php echo $event_phone ?>
            </h4>

        </div>

        <div class="advanced">
            <div class="describe">
                <div class="description">
                    <label for="event_description">Description</label>
                    <h3 id="event_description" rows="4" cols="50">
                        <?php echo $event_description ?>
                    </h3>
                </div>

                <div class="add_event">
                    <a class="add_google" href="https://www.google.com/calendar/render?action=TEMPLATE&text=<?php echo $new_name; ?>+with+<?php echo $new_rso_name; ?>+:+<?php echo $new_category; ?>&dates=<?php echo $new_date; ?>T<?php echo $new_time_from; ?>/<?php echo $new_date; ?>T<?php echo $new_time_to; ?>&details=<?php echo $new_description; ?>.+For+details,+please+contact+at:+Email:+<?php echo $admin_email; ?>+Phone:+<?php echo $admin_phone; ?>+at+<?php echo $new_university; ?>&location=<?php echo $new_location; ?>&sf=true&output=xml" target="_blank" rel="noopener noreferrer">
                        Add To Google Calendar
                    </a>
                    <a class="add_outlook" href="https://outlook.office.com/calendar/0/deeplink/compose?&subject=<?php echo $out_name; ?>%20with%20<?php echo $out_rso_name; ?>%20%3A%20<?php echo $out_category; ?>&body=<?php echo $out_description; ?>%2e%20For%20details%20please%20contact%3A%20<?php echo $admin_email; ?>%20or%20<?php echo $admin_phone; ?>%20at%20<?php echo $out_university; ?>&startdt=<?php echo $event_date; ?>T<?php echo $event_time_from; ?>&enddt=<?php echo $event_date; ?>T<?php echo $event_time_to; ?>&location=<?php echo $out_location; ?>&path=%2Fcalendar%2Faction%2Fcompose&rru=addevent" target="_blank" rel="noopener noreferrer">
                        Add To Outlook Calendar
                    </a>
                </div>
                <div class="social_network">
                    <div class="twitter_share">
                        <a class="twitter-share-button" href="https://twitter.com/intent/tweet" data-size="large" data-text="<?php echo $event_name; ?> (<?php echo $rso_name; ?>) on <?php echo $event_date; ?> at <?php echo $university; ?>." data-url="https://www.brandonspangler.com/evi" data-hashtags="EVI,EventPlanner">
                            Tweet
                        </a>
                    </div>

                    <div class="fb-share-button" data-href="https://www.brandonspangler.com/evi" data-layout="button" data-size="large">
                        <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.brandonspangler.com%2Fevi&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">
                            Share
                        </a>
                    </div>
                </div>
            </div>

            <div class="rating">
                <div class="user_rate">
                    <label for="rate">Rate</label>
                    <h4 id="rate">
                        <?php echo $user_rating ?> Stars
                    </h4>
                    <h5>*****</h5>
                </div>

                <div class="update_rate">
                    <form style="text-align: center;" method="POST">
                        <h3>Update Rate</h3>
                        <input type="text" placeholder="1 - 5 Stars" name="stars" pattern="[1-5]{1}" required />
                        <input type="submit" value="UPDATE" />
                        <h5>Please Refresh After UPDATE</h5>
                    </form>

                    <?php

                    if (isset($_POST['stars'])) {

                        $updated_rate = $_POST['stars'];

                        $user_rate = "SELECT * FROM `event_rating` WHERE `event_type`='2' AND `event_name`='$event_name' AND `university_name`='$university' AND `username`='$_SESSION[username]'";
                        if (mysqli_query($con, $user_rate)) {

                            if (mysqli_num_rows(mysqli_query($con, $user_rate)) == 0) {

                                $add_rate = "INSERT INTO `event_rating`(`event_type`, `event_name`, `university_name`, `username`, `rate`, `status`) VALUES ('2','$event_name','$university','$_SESSION[username]','$updated_rate','1')";

                                if (mysqli_query($con, $add_rate)) {
                                } else {
                                    echo "
                                        <script>
                                            alert('The Server Is Down. Please Try Again'); 
                                            window.location.href='SA_home.php'; 
                                        </script>";
                                }
                            } else {

                                $update_rate_query = "UPDATE `event_rating` SET `rate`='$updated_rate' WHERE `event_type`='2' AND `event_name`='$event_name' AND `university_name`='$university' AND `username`='$_SESSION[username]'";
                                if (mysqli_query($con, $update_rate_query)) {
                                } else {

                                    echo "
                                        <script>
                                            alert('The Server Is Down. Please Try Again'); 
                                            window.location.href='SA_home.php'; 
                                        </script>";
                                }
                            }
                        } else {

                            echo "
                                <script>
                                    alert('The Server Is Down. Please Try Again'); 
                                    window.location.href='SA_home.php'; 
                                </script>";
                        }
                    }
                    ?>
                </div>

                <div class="add_comment">
                    <form style="text-align: center;" method="POST">
                        <h3>Add Comment</h3>
                        <input type="text" placeholder="User Comment" name="comment" required />
                        <input type="submit" value="ADD" />
                        <h5> 255 Max Characters Please. If Not, Comment Will Not Be Made </h5>
                    </form>

                    <?php

                    if (isset($_POST['comment'])) {

                        $comment = mysqli_real_escape_string($con, $_POST['comment']);

                        $comment_status = "SELECT * FROM `event_comments` WHERE `comment`='$comment' AND `event_type`='2' AND `event_name`='$event_name' AND `university_name`='$university' AND `username`='$_SESSION[username]'";

                        if (mysqli_query($con, $comment_status)) {

                            # Same comment exists. Can't add duplicate comments 
                            if (mysqli_num_rows(mysqli_query($con, $comment_status)) > 0) {

                                echo "
                                    <script>
                                        alert('Duplicate Comments Cannot Be Made'); 
                                    </script>";
                            } else {

                                $add_comment = "INSERT INTO `event_comments`(`event_type`, `university_name`, `event_name`, `username`, `comment`) VALUES ('2','$university','$event_name','$_SESSION[username]','$comment')";
                                if (mysqli_query($con, $add_comment)) {
                                } else {

                                    echo "
                                        <script>
                                            alert('The Server Is Down. Please Try Again'); 
                                            window.location.href='SA_home.php'; 
                                        </script>";
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="comment_actions">
                <div class="comments">
                    <h3>User Comments</h3>

                    <div class="lists">
                        <table border="2px">
                            <?php

                            $query = "SELECT * FROM `event_comments` WHERE `event_type`='2' AND `event_name`='$event_name' AND `university_name`='$university' AND `username`='$_SESSION[username]'";
                            $result = mysqli_query($con, $query);

                            if ($result) {

                                # RSO Events Exist
                                if (mysqli_num_rows($result) > 0) {

                                    # Fetch the details of the User Comments 
                                    while ($name = mysqli_fetch_assoc($result)) {
                            ?>
                                        <tr class="names">
                                            <td>
                                                <div class="user_comment">
                                                    <?php
                                                    echo $name['comment'];
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </table>
                    </div>
                </div>

                <div class="comment_buttons">
                    <a href="update_rso_comment.php?type=2&university=<?php echo $university; ?>&name=<?php echo $event_name; ?>">UPDATE</a>
                    <a href="delete_rso_comment.php?type=2&university=<?php echo $university; ?>&name=<?php echo $event_name; ?>">REMOVE</a>
                </div>
            </div>


        </div>
    </div>

</body>

</html>