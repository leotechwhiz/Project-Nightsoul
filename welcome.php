<?php
// Created and Programmed by: LeoTechWhiz / Roshan Gautam


// Initialize the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Welcome</title>
    <style>
        .typing-box {
            border: 2px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
            background-color: #f9f9f9;
        }
        .typing-box p {
            font-size: 18px;
        }
        .result {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Welcome Message -->
        <h1 class="display-4">Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
        <p class="lead">You have successfully logged in.</p>
        <hr>

        <!-- Typing Test Section -->
        <div class="typing-box">
            <h3>Typing Test Game</h3>
            <p id="test-text">Practice typing this text to test your speed!</p>
            <textarea id="typing-area" rows="5" class="form-control" placeholder="Start typing here..."></textarea>
            <button class="btn btn-primary mt-3" id="start-button">Start Test</button>
            <p class="result text-success" id="result"></p>
        </div>

        <!-- Logout Button -->
        <hr>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <script>
        const testText = "The quick brown fox jumps over the lazy dog.";
        const testTextElement = document.getElementById("test-text");
        const typingArea = document.getElementById("typing-area");
        const startButton = document.getElementById("start-button");
        const resultElement = document.getElementById("result");

        let startTime;

        // Function to start the typing test
        startButton.addEventListener("click", function() {
            typingArea.value = "";
            typingArea.focus();
            testTextElement.textContent = testText;
            resultElement.textContent = "";
            startTime = new Date();
        });

        // Event listener to check typing completion
        typingArea.addEventListener("input", function() {
            const typedText = typingArea.value;

            // Check if the user has completed typing the test text
            if (typedText === testText) {
                const endTime = new Date();
                const timeTaken = (endTime - startTime) / 1000; // Time in seconds
                const wordsPerMinute = (testText.split(" ").length / timeTaken) * 60;
                resultElement.textContent = `Congratulations! You typed the text in ${timeTaken.toFixed(2)} seconds. Your speed is ${wordsPerMinute.toFixed(2)} WPM.`;
            }
        });
    </script>
</body>
</html>
