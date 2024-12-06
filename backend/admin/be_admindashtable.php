<?php
include_once("../_includes/styles.php");
include_once("../_includes/scripts.php");

try {
    include("../_conn/connection.php");

    $sql = "SELECT E.id, E.studentLRN, E.fullName, E.teacherName, E.gradelvl, E.schoolYear, E.reqDoc, E.reqDate, E.status, S.parent, S.phoneNumber, S.emailAddress 
            FROM `ezdrequesttbl` E 
            JOIN student_tbl S 
            ON E.studentLRN = S.studentId 
            WHERE E.status != 'claimed'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<table class="table" id="documentTableStudent">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">LRN</th>
                        <th scope="col">Student Name</th>
                        <th scope="col">Parent/Guardian</th>
                        <th scope="col">Teacher/Adviser</th>
                        <th scope="col">Grade Level</th>
                        <th scope="col">School Year</th>
                        <th scope="col">Document Name</th>
                        <th scope="col">Date Requested</th>
                        <th scope="col">Suggested Claim Date</th>
                        <th scope="col">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            $reqDate = date('Y-m-d', strtotime($row['reqDate']));
            $claimDate = date('Y-m-d', strtotime($row['reqDate'] . ' +7 days'));
            $id = $row['id'];

            echo '<tr>
                <td class="align-middle">' . htmlspecialchars($row['id']) . '</td>
                <td class="align-middle">' . htmlspecialchars($row['studentLRN']) . '</td>
                <td class="align-middle">' . htmlspecialchars($row['fullName']) . '</td>
                <td class="align-middle">' . htmlspecialchars($row['parent']) . '</td>
                <td class="align-middle">' . htmlspecialchars($row['teacherName']) . '</td>
                <td class="align-middle">' . htmlspecialchars($row['gradelvl']) . '</td>
                <td class="align-middle">' . htmlspecialchars($row['schoolYear']) . '</td>
                <td class="align-middle">' . htmlspecialchars($row['reqDoc']) . '</td>
                <td class="align-middle">' . htmlspecialchars($reqDate) . '</td>
                <td class="align-middle">' . htmlspecialchars($claimDate) . '</td>
                <td class="align-middle status-' . strtolower($row['status']) . '">
                    <select class="status-dropdown" data-id="' . $id . '">
                        <option value="pending" ' . ($row['status'] == 'pending' ? 'selected' : '') . '>Pending</option>
                        <option value="processing" ' . ($row['status'] == 'processing' ? 'selected' : '') . '>Processing</option>
                        <option value="ready" ' . ($row['status'] == 'ready' ? 'selected' : '') . '>Ready</option>
                        <option value="claimed" ' . ($row['status'] == 'claimed' ? 'selected' : '') . '>Claimed</option>
                    </select>
                </td>
                <td>
                    ' . ($row['status'] == 'ready'
                    ? '<a href="admin_msgreq.php?emailAddress=' . $row['emailAddress'] . '">Message</a>'
                    : '<span style="color: gray; cursor: not-allowed;">Message</span>') . ' |
                    <a href="admineditreq.php?studentId=' . $row['studentLRN'] . '">Edit</a> |
                    <a class="delete-link" href="../backend/admin/be_requestdelete.php?studentLRN=' . $row['studentLRN'] . '" id="btnDelete" data-id="' . $row['studentLRN'] . '">Delete</a>
                </td>
            </tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<p>No requests found.</p>';
    }

    mysqli_free_result($result);
    mysqli_close($conn);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
