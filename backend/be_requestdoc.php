<?php
$errorMessage = "";
try {
    session_start();
    include("../_conn/connection.php");

    if (isset($_POST['btnreqdoc'])) {

        $studentid = $_SESSION['studentId'];
        $studentname = $_SESSION['fullName'];
        $teachname = $_POST['teachName'];
        $schoolyear = $_POST['schoolYear'];
        $gradelev = $_POST['gradelv'];
        $docreq = $_POST['reqDoc'];
        $datereq = $_POST['reqDate'];

        $coecount = 0;
        $diplocount = 0;
        $form137count = 0;
        $form138count = 0;
        $goodmoralcount = 0;

        $getuserRequests = "SELECT 
                            COUNT(CASE WHEN reqDoc = 'Certificate of Enrollment' THEN 1 END) AS count_certificate_of_enrollment, 
                            COUNT(CASE WHEN reqDoc = 'Certificate of Good Moral' THEN 1 END) AS count_good_moral, 
                            COUNT(CASE WHEN reqDoc = 'Form 137' THEN 1 END) AS count_form_137, 
                            COUNT(CASE WHEN reqDoc = 'Form 138' THEN 1 END) AS count_form_138, 
                            COUNT(CASE WHEN reqDoc = 'Diploma' THEN 1 END) AS count_diploma FROM ezdrequesttbl WHERE studentLRN = " . $_SESSION['studentId'];

        $requestres = mysqli_query($conn, $getuserRequests);
        if ($requestres) {
            while ($rowrequest = mysqli_fetch_assoc($requestres)) {
                $coecount = $rowrequest['count_certificate_of_enrollment'];
                $diplocount = $rowrequest['count_diploma'];
                $form137count = $rowrequest['count_form_137'];
                $form138count = $rowrequest['count_form_138'];
                $goodmoralcount = $rowrequest['count_good_moral'];
            }
        }

        if (
            $requestres &&
            ($docreq == 'Diploma' && $diplocount == 0) ||
            ($docreq == 'Certificate of Good Moral' && $goodmoralcount == 0) ||
            ($docreq == 'Form 137' && $form137count == 0) ||
            ($docreq == 'Form 138' && $form138count == 0) ||
            ($docreq == 'Certificate of Enrollment')
        ) {
            mysqli_autocommit($conn, FALSE);

            $addRequestSql = "INSERT INTO ezdrequesttbl(studentLRN, fullName, teacherName, schoolYear, gradelvl, reqDoc, reqDate) VALUES('$studentid', '$studentname', '$teachname', $schoolyear, '$gradelev', '$docreq', '$datereq')";

            mysqli_query($conn, $addRequestSql);

            // $lastID = mysqli_insert_id($conn);

            // $requestHistorySql = "INSERT INTO requestHistory(reqID, reqHistoryDesc, dateCreated) VALUES('" . $lastID . "', 'Document request for " . $docreq . " is created.', '" . date("Y-m-d H:i:s") . "')";
            // $errorMessage = $requestHistorySql;

            // mysqli_query($conn, $requestHistorySql);

            if (!mysqli_commit($conn)) {
                header('Location: ../reqdocument.php?errorMsg=Something went wrong');
            } else {
                $_SESSION['success'] = "Document request successful";
                header('Location: ../index.php');
            }
        } else {
            header('Location: ../reqdocument.php?errorMsg=You cannot request more than 2 ' . $docreq . '.');
        }
        mysqli_close($conn);
    }
} catch (Exception $e) {
    //check for errors
    header("Location: ../reqdocument.php?errorMsg=" . $e->getMessage());
}
