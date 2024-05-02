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

// Récupération de l'ID de la question à modifier depuis l'URL
$id = $_GET['id'];

// Récupération des données de la question depuis la base de données
$sql = "SELECT * FROM questions WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Traitement du formulaire de modification de question
if(isset($_POST['submit'])) {
    $question = $_POST['question'];
    $reponse_1 = $_POST['reponse_1'];
    $reponse_2 = $_POST['reponse_2'];
    $reponse_3 = $_POST['reponse_3'];
    $reponse_4 = $_POST['reponse_4'];
    $youtube_link = $_POST['youtube_link'];

    // Vérifiez si une nouvelle image de question a été téléchargée
    $question_image = $row['question_image'];
    if ($_FILES['question_image']['size'] > 0) {
        $question_image = 'images/' . $_FILES['question_image']['name'];
        move_uploaded_file($_FILES['question_image']['tmp_name'], $question_image);
    }

    // Fonction pour gérer le téléchargement d'images de réponse
    function handleImageUpload($fieldName, $oldImagePath) {
        // Vérifiez si une nouvelle image a été téléchargée
        if ($_FILES[$fieldName]['size'] > 0) {
            // Supprimez l'ancienne image si elle existe
            if (!empty($oldImagePath) && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            // Téléchargez la nouvelle image
            $newImagePath = 'images/' . $_FILES[$fieldName]['name'];
            move_uploaded_file($_FILES[$fieldName]['tmp_name'], $newImagePath);
            return $newImagePath;
        } else {
            // Si aucune nouvelle image n'a été téléchargée, conservez l'ancienne image
            return $oldImagePath;
        }
    }

    // Gestion des images de réponse
    $reponse_1_image = handleImageUpload('reponse_1_image', $row['reponse_1_image']);
    $reponse_2_image = handleImageUpload('reponse_2_image', $row['reponse_2_image']);
    $reponse_3_image = handleImageUpload('reponse_3_image', $row['reponse_3_image']);
    $reponse_4_image = handleImageUpload('reponse_4_image', $row['reponse_4_image']);

    // Mettre à jour la question dans la base de données avec les nouvelles valeurs
    $sql = "UPDATE questions SET question='$question', reponse_1='$reponse_1', reponse_2='$reponse_2', reponse_3='$reponse_3', reponse_4='$reponse_4', question_image='$question_image', reponse_1_image='$reponse_1_image', reponse_2_image='$reponse_2_image', reponse_3_image='$reponse_3_image', reponse_4_image='$reponse_4_image', youtube_link='$youtube_link' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Question mise à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour de la question: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une question</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
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
    <a href="admin.php" class="accueil-btn">Panneau de configuration</a>
    <div class="container">
        <h2>Modifier la question :</h2>
        <form method="post" action="" enctype="multipart/form-data">
            <label>Question :</label>
            <textarea name="question" rows="4" required><?php echo $row['question']; ?></textarea>
            <label>Image de la question :</label>
            <input type="file" name="question_image">
            <label>Réponse 1 :</label>
            <input type="text" name="reponse_1" value="<?php echo $row['reponse_1']; ?>" required>
            <label>Image de la réponse 1 :</label>
            <input type="file" name="reponse_1_image">
            <label>Réponse 2 :</label>
            <input type="text" name="reponse_2" value="<?php echo $row['reponse_2']; ?>" required>
            <label>Image de la réponse 2 :</label>
            <input type="file" name="reponse_2_image">
            <label>Réponse 3 :</label>
            <input type="text" name="reponse_3" value="<?php echo $row['reponse_3']; ?>" required>
            <label>Image de la réponse 3 :</label>
            <input type="file" name="reponse_3_image">
            <label>Réponse 4 :</label>
            <input type="text" name="reponse_4" value="<?php echo $row['reponse_4']; ?>" required>
            <label>Image de la réponse 4 :</label>
            <input type="file" name="reponse_4_image">
            <label>Lien vers une vidéo YouTube (optionnel) :</label>
            <input type="text" name="youtube_link" value="<?php echo $row['youtube_link']; ?>">
            <input type="submit" name="submit" value="Modifier">
        </form>
    </div>
</body>
</html>