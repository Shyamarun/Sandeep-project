<?php
session_start();
$reg_num = $_SESSION['reg_num'];
$projectCode = $_GET['projectCode'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Community Chat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #chat-box {
            height: 300px;
            overflow-y: scroll;
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px 0;
            /* Adjusted for spacing */
            cursor: pointer;
            background: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the project boxes */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #chat-message {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px 0;
            /* Adjusted for spacing */
            cursor: pointer;
            background: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the project boxes */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        body {
            font-family: Arial, sans-serif;
            background-image: url('ch.jpg');
            /* Update with the actual path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .project-box {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px 0;
            /* Adjusted for spacing */
            cursor: pointer;
            background: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            border-radius: 10px;
            /* Rounded corners for the project boxes */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Soft shadow for depth */
            transition: transform 0.2s;
            /* Smooth transform effect on hover */
        }

        .project-box:hover {
            transform: scale(1.05);
            /* Slightly scale up the project box on hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            /* Increased shadow for a lifting effect */
        }

        .navbar.fixed-bottom {
            border-radius: 15px;
            /* Curved edges for the navbar */
            background-color: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            margin: 10px 15px;
            /* Adjust margin to ensure the navbar does not stretch fully across */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Optional: Adds a subtle shadow for depth */
            overflow: hidden;
            /* Ensures content fits within the border radius */
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card-body">
            <h5 class="card-title">Chat Interface</h5>
            <div id="chat-box">
                <!-- Chat messages will be loaded here -->
            </div>
            <textarea id="chat-message" class="form-control" placeholder="Type your message"></textarea>
            <button id="send-chat" class="btn btn-primary mt-2">Send</button>
        </div>

    </div>
    <nav class="navbar fixed-bottom navbar-light bg-light">
        <a class="nav-link" href="club_home.php"><i class="fas fa-plus"></i> Home</a>
        <a class="nav-link" href="club_uploads_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-plus"></i> Add</a>
        <a class="nav-link" href="club_responses.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i> Responses</a>
        <a class="nav-link" href="club_community_home.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-chart-bar"></i>Community</a>
        <a class="nav-link" href="club_profile.php?reg_num=<?php echo $_SESSION['reg_num']; ?>"><i class="fas fa-user"></i> Profile</a>
    </nav>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var sendButton = document.getElementById('send-chat');
            var chatBox = document.getElementById('chat-box');
            var messageBox = document.getElementById('chat-message');
            var projectCode = '<?php echo $projectCode; ?>';
            var regNum = '<?php echo $_SESSION['reg_num']; ?>';

            // Function to send chat message
            function sendChatMessage(message) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", 'post_club_chat.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        // Clear the message box after sending
                        messageBox.value = '';
                        // Reload chat messages
                        loadChatMessages();
                    } else {
                        // Handle error
                        console.error('Error sending message');
                    }
                };
                xhr.send('reg_num=' + encodeURIComponent(regNum) +
                    '&chat=' + encodeURIComponent(message) +
                    '&projectCode=' + encodeURIComponent(projectCode));
            }

            // Function to load chat messages
            function loadChatMessages() {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", 'get_chat_messages.php?projectCode=' + encodeURIComponent(projectCode), true);
                xhr.onload = function() {
                    if (this.status === 200) {
                        chatBox.innerHTML = this.responseText;
                    } else {
                        // Handle error
                        console.error('Error loading messages');
                    }
                };
                xhr.send();
            }

            // Event listener for send button
            sendButton.addEventListener('click', function() {
                var chatMessage = messageBox.value;
                if (chatMessage.trim() !== '') {
                    sendChatMessage(chatMessage);
                }
            });

            // Load chat messages periodically
            setInterval(loadChatMessages, 100); // Reload messages every 3 seconds
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>