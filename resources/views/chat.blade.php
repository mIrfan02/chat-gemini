<!DOCTYPE html>
<html>
<head>
    <title>Gemini AI Chatbot</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        #chat-container {
            width: 100%;
            max-width: 800px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 550px;
        }
        #chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            background: #fafafa;
        }
        #user-input {
            border: none;
            padding: 15px;
            border-top: 1px solid #ddd;
            font-size: 16px;
            outline: none;
            resize: none;
            width: calc(100% - 100px);
            border-radius: 0 0 0 12px;
        }
        #send-btn {
            border: none;
            background: #007bff;
            color: white;
            padding: 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 0 0 12px 0;
            transition: background 0.3s;
        }
        #send-btn:hover {
            background: #0056b3;
        }
        .message {
            margin: 10px 0;
            padding: 10px 15px;
            border-radius: 10px;
            max-width: 75%;
            clear: both;
        }
        .user-message {
            background: #007bff;
            color: white;
            float: right;
            text-align: right;
        }
        .bot-message {
            background: #e9e9e9;
            color: #333;
            float: left;
            text-align: left;
        }
        .typing-indicator {
            display: none;
            margin: 10px 0;
            padding: 10px;
            border-radius: 10px;
            background: #f1f1f1;
            color: #666;
            text-align: center;
        }
        .typing-indicator.active {
            display: block;
        }
        .typing-indicator span {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin: 0 3px;
            border-radius: 50%;
            background: #007bff;
            animation: blink 1.4s infinite both;
        }
        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }
        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }
        @keyframes blink {
            0%, 100% {
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
        }
        @media (max-width: 600px) {
            #user-input {
                width: calc(100% - 70px);
            }
            #send-btn {
                width: 70px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<div id="chat-container">
    <div id="chat-box"></div>
    <div id="typing-indicator" class="typing-indicator">
        <span></span><span></span><span></span> AI is typing...
    </div>
    <div style="display: flex;">
        <textarea id="user-input" placeholder="Type your message here..."></textarea>
        <button id="send-btn">Send</button>
    </div>
</div>

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatBox = document.getElementById('chat-box');
        const userInput = document.getElementById('user-input');
        const sendBtn = document.getElementById('send-btn');
        const typingIndicator = document.getElementById('typing-indicator');

        sendBtn.addEventListener('click', function() {
            const message = userInput.value.trim();
            if (!message) return;

            // Add the user message to the chat box
            chatBox.innerHTML += `<div class="message user-message">${message}</div>`;
            userInput.value = ''; // Clear the input field

            // Scroll to the bottom of the chat box
            chatBox.scrollTop = chatBox.scrollHeight;

            // Show typing indicator
            typingIndicator.classList.add('active');

            // Send the message to the server
            fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message })
            })
            .then(response => response.json())
            .then(data => {
                const botReply = data.response || 'No response from model.';
                chatBox.innerHTML += `<div class="message bot-message">${botReply}</div>`;
                chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom after adding the reply

                // Hide typing indicator
                typingIndicator.classList.remove('active');
            })
            .catch(error => {
                console.error('Error:', error);
                typingIndicator.classList.remove('active'); // Hide typing indicator on error
            });
        });

        // Optional: Add an event listener for the Enter key
        userInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendBtn.click();
            }
        });
    });
</script> --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.getElementById('chat-box');
    const userInput = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');
    const typingIndicator = document.getElementById('typing-indicator');

    sendBtn.addEventListener('click', function() {
        const message = userInput.value.trim();
        if (!message) return;

        // Add the user message to the chat box
        chatBox.innerHTML += `<div class="message user-message">${message}</div>`;
        userInput.value = ''; // Clear the input field

        // Scroll to the bottom of the chat box
        chatBox.scrollTop = chatBox.scrollHeight;

        // Show typing indicator
        typingIndicator.classList.add('active');

        // Send the message to the server
        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message })
        })
        .then(response => response.json())
        .then(data => {
            // Check if the response has the correct structure
            const botReply = data.response && typeof data.response === 'string' ? data.response : 'No response from model.';
            chatBox.innerHTML += `<div class="message bot-message">${botReply}</div>`;
            chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom after adding the reply

            // Hide typing indicator
            typingIndicator.classList.remove('active');
        })
        .catch(error => {
            console.error('Error:', error);
            typingIndicator.classList.remove('active'); // Hide typing indicator on error
        });
    });

    // Optional: Add an event listener for the Enter key
    userInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendBtn.click();
        }
    });
});

</script>


</body>
</html>
