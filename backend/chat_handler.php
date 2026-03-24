<?php

session_start();
$host = "fdb1032.awardspace.net"; $user = "4676457_montana"; $pass = "FdgO%Ct]4[kmV7T["; $dbname = "4676457_montana";
$conn = new mysqli($host, $user, $pass, $dbname);

// Security: Must be logged in as user or admin
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) { exit("Unauthorized"); }

$action = $_GET['action'] ?? '';

// ACTION: SEND MESSAGE
if ($action == 'send') {
    $user_id = $_POST['user_id']; // The customer's ID
    $sender = $_POST['sender'];   // 'user' or 'admin'
    $text = mysqli_real_escape_string($conn, $_POST['message']);

    if (!empty($text)) {
        $conn->query("INSERT INTO messages (user_id, sender, message_text) VALUES ('$user_id', '$sender', '$text')");
    }
}

if ($action == 'fetch') {
    $user_id = $_GET['user_id'];
    $result = $conn->query("SELECT * FROM messages WHERE user_id = '$user_id' ORDER BY created_at ASC");
    
    while ($row = $result->fetch_assoc()) {
        // Determine the class and name based on the sender
        if ($row['sender'] == 'admin') {
            $class = "msg-admin";
            $displayName = "Support Agent";
        } else {
            $class = "msg-user";
            $displayName = "You";
        }

        echo "
        <div class='msg $class'>
            <span class='sender-name'>$displayName</span>
            <p style='margin:0;'>{$row['message_text']}</p>
            <span class='time'>" . date('h:i A', strtotime($row['created_at'])) . "</span>
        </div>";
    }
}

// // ACTION: FETCH MESSAGES
// if ($action == 'fetch') {
//     $user_id = $_GET['user_id'];
//     $result = $conn->query("SELECT * FROM messages WHERE user_id = '$user_id' ORDER BY created_at ASC");
    
//     while ($row = $result->fetch_assoc()) {
//         $class = ($row['sender'] == 'admin') ? 'msg-admin' : 'msg-user';
//         echo "<div class='message $class'>
//                 <p>{$row['message_text']}</p>
//                 <small>" . date('H:i', strtotime($row['created_at'])) . "</small>
//               </div>";
//     }
// }
?>