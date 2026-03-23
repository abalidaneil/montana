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
        :root { --sidebar-w: 240px; --teal: #0d3a35; --bg-gray: #f8fafb; }
        body { display: flex; background: var(--bg-gray); margin: 0; font-family: 'Inter', sans-serif; height: 100vh; }
        
        /* Sidebar stays fixed */
        aside { width: var(--sidebar-w); background: #fff; height: 100vh; border-right: 1px solid #eee; position: fixed; padding: 20px; }
        
        main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; height: 100vh; }
        
        /* Chat Interface */
        .chat-header { background: #fff; padding: 20px; border-bottom: 1px solid #eee; font-weight: bold; color: var(--teal); }
        #chat-history { flex: 1; padding: 30px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; }
        
        /* Message Bubbles */
        .msg { max-width: 60%; padding: 12px 18px; border-radius: 15px; font-size: 0.95rem; line-height: 1.4; position: relative; }
        .msg-user { align-self: flex-end; background: var(--teal); color: white; border-bottom-right-radius: 2px; }
        .msg-admin { align-self: flex-start; background: #e9ecef; color: #333; border-bottom-left-radius: 2px; }
        .time { font-size: 0.7rem; display: block; margin-top: 5px; opacity: 0.7; }

        .chat-input-area { background: #fff; padding: 20px; border-top: 1px solid #eee; display: flex; gap: 15px; }
        input { flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 8px; outline: none; }
        button { background: var(--teal); color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; }
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