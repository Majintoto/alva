<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questionnaire</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            width: 100%;
            background-color: #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            height: 20px;
        }
        .progress {
            height: 100%;
            background-color: #007bff;
            border-radius: 5px;
            transition: width 0.3s ease-in-out;
        }
        .question-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 20px;
            display: none;
        }
        .question {
            margin-bottom: 20px;
            font-weight: bold;
        }
        .responses {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .response {
            flex: 0 0 48%;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .response-text {
            margin-bottom: 10px;
        }
        .response img {
            width: 100px;
            height: 100px;
            border-radius: 5px;
        }
        .navigation-btns {
            text-align: center;
            margin-top: 20px;
        }
        .navigation-btns button {
            margin: 0 5px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .navigation-btns button:hover {
            background-color: #0056b3;
        }
        .accueil-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
        }
        .accueil-btn:hover {
            background-color: #0056b3;
        }
        #timer {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Bouton pour accéder à la page d'accueil -->
    <a href="accueil.php" class="accueil-btn">Accueil</a>

    <div class="container">
        <h2 style="text-align: center;">Questionnaire</h2>
        <div id="timer"></div>
        <div class="progress-bar">
            <div class="progress" id="progress"></div>
        </div>
        <form id="questionForm" method="post" action="resultat.php">
            <?php
            // Connexion à la base de données
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "questionnaire_db";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Vérification de la connexion
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Récupération des questions de la base de données
            $sql = "SELECT * FROM questions";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $i = 1;
                while($row = $result->fetch_assoc()) {
                    echo "<div class='question-container'>";
                    echo "<div class='question'>".$row["question"]."</div>";
                    echo "<div class='responses'>";
                    echo "<div class='response'>";
                    echo "<label class='response-text'><input type='radio' name='reponse[".$row["id"]."]' value='".$row["reponse_1"]."'>".$row["reponse_1"]."</label>";
                    if (!empty($row["reponse_1_image"])) {
                        echo "<img class='response-image' src='".$row["reponse_1_image"]."' alt='Response 1 Image'>";
                    }
                    echo "</div>";
                    echo "<div class='response'>";
                    echo "<label class='response-text'><input type='radio' name='reponse[".$row["id"]."]' value='".$row["reponse_2"]."'>".$row["reponse_2"]."</label>";
                    if (!empty($row["reponse_2_image"])) {
                        echo "<img class='response-image' src='".$row["reponse_2_image"]."' alt='Response 2 Image'>";
                    }
                    echo "</div>";
                    echo "<div class='response'>";
                    echo "<label class='response-text'><input type='radio' name='reponse[".$row["id"]."]' value='".$row["reponse_3"]."'>".$row["reponse_3"]."</label>";
                    if (!empty($row["reponse_3_image"])) {
                        echo "<img class='response-image' src='".$row["reponse_3_image"]."' alt='Response 3 Image'>";
                    }
                    echo "</div>";
                    echo "<div class='response'>";
                    echo "<label class='response-text'><input type='radio' name='reponse[".$row["id"]."]' value='".$row["reponse_4"]."'>".$row["reponse_4"]."</label>";
                    if (!empty($row["reponse_4_image"])) {
                        echo "<img class='response-image' src='".$row["reponse_4_image"]."' alt='Response 4 Image'>";
                    }
                    echo "</div>";
                    echo "</div>"; // Fin de responses
                    if ($i == 1) {
                        echo "<div class='navigation-btns'><button type='button' onclick='nextQuestion()'>Suivant</button></div>";
                    } else if ($i == $result->num_rows) {
                        echo "<div class='navigation-btns'><button type='button' onclick='prevQuestion()'>Précédent</button><button type='submit'>Soumettre</button></div>";
                    } else {
                        echo "<div class='navigation-btns'><button type='button' onclick='prevQuestion()'>Précédent</button><button type='button' onclick='nextQuestion()'>Suivant</button></div>";
                    }
                    echo "</div>"; // Fin de question-container
                    $i++;
                }
            } else {
                echo "Aucune question trouvée.";
            }

            // Fermer la connexion à la base de données
            $conn->close();
            ?>
        </form>
    </div>

    <script>
        let currentQuestionIndex = 0;
        let questions = document.querySelectorAll('.question-container');

        function showQuestion(index) {
            questions.forEach(function(question, i) {
                if (i === index) {
                    question.style.display = 'block';
                } else {
                    question.style.display = 'none';
                }
            });
        }

        function nextQuestion() {
            if (currentQuestionIndex < questions.length - 1) {
                currentQuestionIndex++;
                showQuestion(currentQuestionIndex);
                updateProgressBar();
            }
        }

        function prevQuestion() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                showQuestion(currentQuestionIndex);
                updateProgressBar();
            }
        }

        // Fonction pour afficher le chronomètre et les questions une par une
        function startTimerAndQuestions(duration, display) {
            let timer = duration, minutes, seconds;

            let interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval); // Arrête le chronomètre
                    timer = duration;
                    alert("Le temps est écoulé !");
                    // Soumet automatiquement le formulaire quand le temps est écoulé
                    document.getElementById('questionForm').submit();
                }
            }, 1000);
        }

        // Démarre le chronomètre lors du chargement de la page
        window.onload = function () {
            let twoMinutes = 2 * 60,
                display = document.querySelector('#timer');
            startTimerAndQuestions(twoMinutes, display);
        };

        // Fonction pour mettre à jour la barre de progression
        function updateProgressBar() {
            let progress = (currentQuestionIndex + 1) / questions.length * 100;
            document.getElementById('progress').style.width = progress + '%';
        }

        showQuestion(currentQuestionIndex);
        updateProgressBar();
    </script>
</body>
</html>