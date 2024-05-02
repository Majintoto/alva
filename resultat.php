<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat du Questionnaire</title>
    <style>
        /* Styles CSS */
        .correct-answer {
            color: green;
        }
        .wrong-answer {
            color: red;
        }
        table {
            margin: 0 auto; /* Centrer le tableau */
            border-collapse: collapse;
            width: 80%; /* Largeur du tableau */
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <!-- Bouton pour retourner au questionnaire -->
    <a href="questionnaire.php" style="position: fixed; top: 20px; right: 20px;">Retour au questionnaire</a>
    <h2 style="text-align: center;">Résultat du Questionnaire</h2>
    <div id="score" style="text-align: center; font-size: 20px; margin-bottom: 20px;">
        <?php
        // Vérification si les réponses sont envoyées par le formulaire
        if(isset($_POST['reponse'])){
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

            // Initialisation du score
            $score = 0;

            // Récupération des réponses fournies par l'utilisateur
            $reponses = $_POST['reponse'];

            // Récupération de toutes les questions
            $sql = "SELECT * FROM questions";
            $result = $conn->query($sql);
            $questions = [];
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $questions[$row['id']] = $row;
                }
            }

            // Création du tableau
            echo "<table>";
            echo "<tr><th>Question</th><th>Votre réponse</th><th>Bonne réponse</th></tr>";

            // Affichage des réponses
            foreach($questions as $question_id => $question){
                // Vérification si la réponse de l'utilisateur est correcte ou incorrecte
                $user_answer = isset($reponses[$question_id]) ? $reponses[$question_id] : "Aucune réponse";
                $correct_answer = $question['bonne_reponse'];
                if($user_answer === $correct_answer){
                    $score++; // Incrémentation du score si la réponse est correcte
                    echo "<tr><td>" . $question['question'] . "</td><td class='correct-answer'>" . $user_answer . "</td><td class='correct-answer'>" . $correct_answer . "</td></tr>";
                } else {
                    echo "<tr><td>" . $question['question'] . "</td><td class='wrong-answer'>" . $user_answer . "</td><td class='correct-answer'>" . $correct_answer . "</td></tr>";
                }
            }

            // Affichage du score
            echo "</table>";
            echo "<p>Votre score est de : <strong>" . $score . "/" . count($questions) . "</strong></p>";

            // Fermeture de la connexion à la base de données
            $conn->close();
        } else {
            // Si aucune réponse n'a été envoyée
            echo "Aucun résultat à afficher.";
        }
        ?>
    </div>
</body>