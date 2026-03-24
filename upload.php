<?php
$host = "fdb1032.awardspace.net"; $user = "4676457_montana"; $pass = "FdgO%Ct]4[kmV7T["; $dbname = "4676457_montana";
$conn = new mysqli($host, $user, $pass, $dbname);


session_start();

// Check if images were uploaded
if (isset($_FILES['image1']) && isset($_FILES['image2'])) {
    $image1 = $_FILES['image1']['tmp_name'];
    $image2 = $_FILES['image2']['tmp_name'];
    $user_id = $_SESSION['id'];


    $image1Data = addslashes(file_get_contents($image1));
    $image2Data = addslashes(file_get_contents($image2));

    $sql = "INSERT INTO images (user_id, image1, image2) VALUES ('$image1Data', '$image2Data', '$user_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Images uploaded successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
