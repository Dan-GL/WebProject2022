<?php
    session_start();
    require("scripts/functions.php");
    //require("scripts/ajax.php");
    $conn = connectDatabase(true);


    $courseID = $_POST["courseID"];
    $sql = "SELECT courseName FROM courses WHERE courseID = '$courseID'";
    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_array($result)) {
        $courseName = $row["courseName"];
    }

  

    $sql = "SELECT username FROM studentCourses WHERE courseID = '$courseID'";
    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_array($result)) {
        $userRow[] = $row["username"];
    }
    
    
    try {
        $sql = "SELECT * FROM users WHERE username IN(" . implode(',', $userRow) . ")";
    }
    catch (TypeError $e){
        consoleLog($e);
    }

    $result = mysqli_query($conn, $sql);
    $numrows = mysqli_num_rows($result);

    echo "<table id='courseTable'><caption>$courseName Students</caption>";

    if ($numrows >= 1){
        echo "<th>Name</th><th>Student ID</th>";
        while($row = mysqli_fetch_array($result)){
            echo '<tr><td>'. htmlspecialchars($row['forename']) .' '.htmlspecialchars($row['surname']).'</td>
            <td>'. htmlspecialchars($row['username']) . '</td></tr>';
        }
    }
    else {
        echo "<tr><td>There are no students on this course.</td></tr>";
    }
    echo "<tr><td><input class='backButton' type='button' onclick='window.location.reload();' value='Go back'></td><td><input type='number' placeholder='Student ID' id='studentID'><input type='button' value='Add Student' onclick='addStudent(". $courseID . ")'></td></tr></table>";
    mysqli_close($conn);
?>