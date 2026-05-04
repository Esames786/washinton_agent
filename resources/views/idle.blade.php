<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idle Page - Logistics</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            background: linear-gradient(to bottom, #87CEEB, #ffffff);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .container {
            position: relative;
        }

        .message {
            font-size: 24px;
            font-weight: bold;
            animation: fadeIn 2s ease-in-out;
            margin-bottom: 20px;
        }

        .big-truck {
            position: absolute;
            bottom: 50px;
            left: 50vw;
            width: 250px;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }

        .falling-truck {
            position: absolute;
            font-size: 80px;
            cursor: pointer;
            animation: fallTruck 5s linear infinite;
        }

        .btn {
            margin-top: 50px;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fallTruck {
            0% { top: -50px; }
            100% { top: 100vh; }
        }

        /* Flash effect when hit */
        .hit {
            filter: brightness(1.5) saturate(2);
        }
        .title {
            font-size: 32px;
            font-weight: bold;
            color: red;
            text-transform: uppercase;
            margin-bottom: 20px;
            animation: pulse 1.5s infinite alternate;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="title">Focus on Work! Don't Be Idle! 🚀</div> <!-- Title Added -->

    <div class="message">You were idle! Click below to return.</div>
    <img style="display: none" src="{{ url('trr.jpeg') }}" class="big-truck" alt="Villain Truck" id="bigTruck">
    <button class="btn" onclick="window.history.back();">Go Back</button>
    <div id="game-area"></div>
    <p>Score: <span id="score">0</span></p>
</div>

<script>
    let score = 0;
    let gameArea = document.getElementById("game-area");
    let scoreDisplay = document.getElementById("score");
    let bigTruck = document.getElementById("bigTruck");

    let truckSpeed = 5;  // Initial speed
    let truckDirection = 1;  // 1 = Right, -1 = Left
    let screenWidth = window.innerWidth;

    function createFallingTruck() {
        let truck = document.createElement("div");
        truck.innerText = "🚚";  // Truck emoji
        truck.classList.add("falling-truck");
        truck.style.left = Math.random() * window.innerWidth + "px";
        truck.style.top = "-50px";
        truck.style.position = "absolute";
        truck.style.animation = `fallTruck ${3 + Math.random() * 2}s linear forwards`;
        gameArea.appendChild(truck);

        truck.addEventListener("click", function () {
            score++;
            scoreDisplay.innerText = score;
            truck.remove();
        });

        setTimeout(() => {
            truck.remove();
        }, 5000);
    }

    setInterval(createFallingTruck, 1000);

    // Move the big truck like a villain
    function moveBigTruck() {
        let currentLeft = parseInt(window.getComputedStyle(bigTruck).left);

        // Adjust speed based on score (makes it harder)
        truckSpeed = 5 + Math.min(score / 5, 20); // Max speed 25

        // Move left or right
        currentLeft += truckSpeed * truckDirection;

        // Reverse direction if hitting screen edges
        if (currentLeft <= 0 || currentLeft + bigTruck.clientWidth >= screenWidth) {
            truckDirection *= -1;
        }

        bigTruck.style.left = currentLeft + "px";
    }

    setInterval(moveBigTruck, 50); // Move truck smoothly

    // Clicking big truck gives 5 points & makes it "fight"
    bigTruck.addEventListener("click", function () {
        score += 5;
        scoreDisplay.innerText = score;

        // Flash effect
        bigTruck.classList.add("hit");
        setTimeout(() => bigTruck.classList.remove("hit"), 200);

        // Quick move away from click
        let dodgeDirection = Math.random() > 0.5 ? 1 : -1;
        let dodgeDistance = Math.random() * 100 + 50;  // Random dodge distance
        let newLeft = parseInt(bigTruck.style.left) + dodgeDirection * dodgeDistance;

        // Keep truck inside the screen
        if (newLeft < 0) newLeft = 0;
        if (newLeft + bigTruck.clientWidth > screenWidth) newLeft = screenWidth - bigTruck.clientWidth;

        bigTruck.style.left = newLeft + "px";
    });

    // Resize event to update screen width
    window.addEventListener("resize", function () {
        screenWidth = window.innerWidth;
    });

</script>
</body>
</html>
