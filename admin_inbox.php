<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: admin_login.html"); exit(); }
$conn = new mysqli("localhost", "root", "", "montana");

// Fetch unique users who have messaged
$users = $conn->query("SELECT DISTINCT u.id, u.fname, u.lname FROM users u JOIN messages m ON u.id = m.user_id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Inbox</title>
    <style>
        body { display: flex; margin: 0; font-family: sans-serif; background: #f4f7f6; height: 100vh; }
        .user-list { width: 300px; background: #fff; border-right: 1px solid #eee; overflow-y: auto; }
        .user-item { padding: 20px; border-bottom: 1px solid #f9f9f9; cursor: pointer; }
        .user-item:hover { background: #f0fdf4; }
        .chat-area { flex: 1; display: flex; flex-direction: column; }
        #admin-chat-window { flex: 1; padding: 20px; overflow-y: auto; background: #fff; margin: 20px; border-radius: 12px; }
        .reply-box { padding: 20px; background: #fff; display: flex; gap: 10px; }

        #chat-history {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 20px;
        }

        /* Base Message Style */
        .msg {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 15px;
            position: relative;
            font-size: 0.95rem;
            line-height: 1.4;
        }

        /* User Messages (Sent by Customer) - Right Aligned */
        .msg-user {
            align-self: flex-end;
            background-color: #0d3b36; /* Your primary dark green */
            color: white;
            border-bottom-right-radius: 2px;
        }

        /* Admin Messages (Sent by Support) - Left Aligned */
        .msg-admin {
            align-self: flex-start;
            background-color: #e9ecef; /* Light gray */
            color: #333;
            border-bottom-left-radius: 2px;
        }

        /* Names and Timestamps */
        .sender-name {
            display: block;
            font-weight: bold;
            font-size: 0.75rem;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .msg-user .sender-name { color: #d1f366; text-align: right; } /* Neon accent for user name */
        .msg-admin .sender-name { color: #0d3b36; text-align: left; }

        .time {
            display: block;
            font-size: 0.65rem;
            margin-top: 5px;
            opacity: 0.6;
        }
        .msg-user .time { text-align: right; }
    </style>
</head>
<body>

    <div class="user-list">
        <h3 style="padding: 20px;">Support Inbox</h3>
        <?php while($u = $users->fetch_assoc()): ?>
            <div class="user-item" onclick="loadUserChat(<?php echo $u['id']; ?>, '<?php echo $u['fname']; ?>')">
                <strong><?php echo $u['fname'] . " " . $u['lname']; ?></strong>
                <p style="font-size: 0.8rem; color: #888;">Click to reply</p>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="chat-area">
        <div id="admin-chat-window">Select a user to start chatting</div>
        
        <div class="reply-box" id="reply-container" style="display:none;">
            <input type="text" id="admin-reply" style="flex:1; padding:10px;">
            <button onclick="sendAdminReply()" style="background:#0d3a35; color:white; border:none; padding:10px 20px; border-radius:5px;">Send Reply</button>
        </div>
    </div>

    <script>
        let activeUserId = null;

        function loadUserChat(id, name) {
            activeUserId = id;
            document.getElementById('reply-container').style.display = 'flex';
            fetchAdminMessages();
        }

        function fetchAdminMessages() {
            if(!activeUserId) return;
            fetch(`backend/chat_handler.php?action=fetch&user_id=${activeUserId}`)
                .then(r => r.text())
                .then(html => {
                    const win = document.getElementById('admin-chat-window');
                    win.innerHTML = html;
                    win.scrollTop = win.scrollHeight;
                });
        }

        function sendAdminReply() {
            const text = document.getElementById('admin-reply').value;
            const formData = new URLSearchParams();
            formData.append('user_id', activeUserId);
            formData.append('sender', 'admin');
            formData.append('message', text);

            fetch('backend/chat_handler.php?action=send', { method: 'POST', body: formData })
                .then(() => {
                    document.getElementById('admin-reply').value = '';
                    fetchAdminMessages();
                });
        }

        setInterval(fetchAdminMessages, 3000);
    </script>
</body>
</html>