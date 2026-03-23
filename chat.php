<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.html"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Chat | Firstworldchoice</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
                /* Container for the whole history */
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

    <aside>
        <div style="font-weight: bold; margin-bottom: 40px;">FirstWorldchoice</div>
        <nav>
            <a href="dashboard.php" style="display:block; padding:10px; color:#666; text-decoration:none;">Dashboard</a>
            <a href="chat.php" style="display:block; padding:10px; color:var(--teal); font-weight:bold; text-decoration:none;">Live Support</a>
        </nav>
    </aside>

    <main>
        <div class="chat-header">Customer Support Specialist</div>
        
        <div id="chat-history">
            </div>

        <div class="chat-input-area">
            <input type="text" id="user-msg" placeholder="Describe your issue...">
            <button onclick="sendMsg()"><i class="fa-solid fa-paper-plane"></i> Send</button>
        </div>
    </main>

    <script>
        const userId = "<?php echo $_SESSION['user_id']; ?>";

        function fetchMessages() {
            fetch(`backend/chat_handler.php?action=fetch&user_id=${userId}`)
                .then(r => r.text())
                .then(html => {
                    const history = document.getElementById('chat-history');
                    history.innerHTML = html;
                    history.scrollTop = history.scrollHeight; // Auto-scroll to bottom
                });
        }

        function sendMsg() {
            const text = document.getElementById('user-msg').value;
            if(!text) return;

            const formData = new URLSearchParams();
            formData.append('user_id', userId);
            formData.append('sender', 'user');
            formData.append('message', text);

            fetch('backend/chat_handler.php?action=send', {
                method: 'POST',
                body: formData
            }).then(() => {
                document.getElementById('user-msg').value = '';
                fetchMessages();
            });
        }

        setInterval(fetchMessages, 3000); // Poll every 3 seconds
        fetchMessages();
    </script>
</body>
</html>