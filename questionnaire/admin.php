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

// Traitement du formulaire d'ajout de question
if(isset($_POST['submit'])) {
    $question = $_POST['question'];
    $reponse_1 = $_POST['reponse_1'];
    $reponse_2 = $_POST['reponse_2'];
    $reponse_3 = $_POST['reponse_3'];
    $reponse_4 = $_POST['reponse_4'];
    $bonne_reponse = $_POST['bonne_reponse'];
    $youtube_link = $_POST['youtube_link'];

    // Upload de l'image de la question
    $question_image = '';
    if ($_FILES['question_image']['size'] > 0) {
        $question_image = 'images/' . $_FILES['question_image']['name'];
        move_uploaded_file($_FILES['question_image']['tmp_name'], $question_image);
    }

    // Upload des images des réponses
    $reponse_1_image = '';
    if ($_FILES['reponse_1_image']['size'] > 0) {
        $reponse_1_image = 'images/' . $_FILES['reponse_1_image']['name'];
        move_uploaded_file($_FILES['reponse_1_image']['tmp_name'], $reponse_1_image);
    }

    // Upload des images des réponses
    $reponse_2_image = '';
    if ($_FILES['reponse_2_image']['size'] > 0) {
        $reponse_2_image = 'images/' . $_FILES['reponse_2_image']['name'];
        move_uploaded_file($_FILES['reponse_2_image']['tmp_name'], $reponse_2_image);
    }
    
    // Upload des images des réponses
    $reponse_3_image = '';
    if ($_FILES['reponse_3_image']['size'] > 0) {
        $reponse_3_image = 'images/' . $_FILES['reponse_3_image']['name'];
        move_uploaded_file($_FILES['reponse_3_image']['tmp_name'], $reponse_3_image);
    }
            
    // Upload des images des réponses
    $reponse_4_image = '';
    if ($_FILES['reponse_4_image']['size'] > 0) {
        $reponse_4_image = 'images/' . $_FILES['reponse_4_image']['name'];
        move_uploaded_file($_FILES['reponse_4_image']['tmp_name'], $reponse_4_image);
    }
    
    $sql = "INSERT INTO questions (question, reponse_1, reponse_2, reponse_3, reponse_4, bonne_reponse, question_image, reponse_1_image, reponse_2_image, reponse_3_image, reponse_4_image, youtube_link)
            VALUES ('$question', '$reponse_1', '$reponse_2', '$reponse_3', '$reponse_4', '$bonne_reponse', '$question_image', '$reponse_1_image', '$reponse_2_image', '$reponse_3_image', '$reponse_4_image', '$youtube_link')";

    if ($conn->query($sql) === TRUE) {
        echo "Question ajoutée avec succès.";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

// Traitement de la suppression de question
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM questions WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Question supprimée avec succès.";
    } else {
        echo "Erreur lors de la suppression de la question: " . $conn->error;
    }
}

// Récupération des questions de la base de données
$sql = "SELECT * FROM questions";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau d'administration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .actions {
            text-align: center;
        }
        .actions a {
            text-decoration: none;
            color: #007bff;
            margin: 0 5px;
        }
        .actions a:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>
    <!-- Bouton pour accéder à la page accueil.php -->
    <a href="accueil.php" class="accueil-btn">Accueil</a>
    <div class="container">
        <h2>Ajouter une nouvelle question :</h2>
        <form method="post" action="admin.php" enctype="multipart/form-data">
            <label>Question :</label>
            <textarea name="question" rows="4" required></textarea>
            <label>Image de la question :</label>
            <input type="file" name="question_image">
            <label>Réponse 1 :</label>
            <input type="text" name="reponse_1" required>
            <label>Image de la réponse 1 :</label>
            <input type="file" name="reponse_1_image">
            <label>Réponse 2 :</label>
            <input type="text" name="reponse_2" required>
            <label>Image de la réponse 2 :</label>
            <input type="file" name="reponse_2_image">
            <label>Réponse 3 :</label>
            <input type="text" name="reponse_3" required>
            <label>Image de la réponse 3 :</label>
            <input type="file" name="reponse_3_image">
            <label>Réponse 4 :</label>
            <input type="text" name="reponse_4" required>
            <label>Image de la réponse 4 :</label>
            <input type="file" name="reponse_4_image">
            <label>Bonne réponse :</label>
            <select name="bonne_reponse">
                <option value="1">Réponse 1</option>
                <option value="2">Réponse 2</option>
                <option value="3">Réponse 3</option>
                <option value="4">Réponse 4</option>
            </select>
            <label>Lien vers une vidéo YouTube (optionnel) :</label>
            <input type="text" name="youtube_link">
            <input type="submit" name="submit" value="Ajouter">
        </form>
        <h2>Liste des questions :</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Bonne réponse</th>
                <th>Action</th>
            </tr>
            <?php
            // Nouvelle requête pour récupérer l'index de la bonne réponse pour chaque question
            $bonne_reponse_sql = "SELECT id, bonne_reponse FROM questions";
            $bonne_reponse_result = $conn->query($bonne_reponse_sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row["id"]."</td>";
                    echo "<td>".$row["question"]."</td>";

                    // Afficher la bonne réponse associée à chaque question
                    $question_id = $row["id"];
                    $bonne_reponse = '';
                    if ($bonne_reponse_result->num_rows > 0) {
                        while($bonne_reponse_row = $bonne_reponse_result->fetch_assoc()) {
                            if ($bonne_reponse_row["id"] == $question_id) {
                                $bonne_reponse = $bonne_reponse_row["bonne_reponse"];
                                break;
                            }
                        }
                    }

                    echo "<td>".$bonne_reponse."</td>";
                    echo "<td class='actions'>
                            <a href='edit_question.php?id=".$row["id"]."'>Modifier</a>
                            <a href='admin.php?delete=".$row["id"]."' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cette question ?\")'>Supprimer</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Aucune question trouvée.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>