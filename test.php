<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Post</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #ff69b4;
            border-radius: 8px;
            font-size: 16px;
            color: #173879;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #ff1493;
        }
        select {
            appearance: none;
        }
        button {
            background-color: #e6add9; /* Changed button color */
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
        }
        .error {
            color: red;
            font-size: 16px;
            margin-top: 5px;
        }
        .custom-file-label {
            overflow: hidden;
        }
        .preview-container {
            margin-top: 20px;
            text-align: center;
        }
        .preview-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: 10px;
            margin-top: 10px;
        }
        .remove-image {
            color: #dc3545;
            cursor: pointer;
            font-size: 14px;
            margin-top: 5px;
            display: inline-block;
        }
        @media (max-width: 600px) {
            input, textarea, select {
                width: calc(100% - 20px);
            }
        }
    </style>
</head>
<body>

<div class="container mt-4">
    
   <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "sql11.freesqldatabase.com";
        $username = "sql11687350";
        $password = "4r8x73pH2u";
        $dbname = "sql11687350";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $title_post = $conn->real_escape_string($_POST['postTitle']);
        $descri_post = $conn->real_escape_string($_POST['postDescription']);
        $category_post = $conn->real_escape_string($_POST['postCategory']);

        if (isset($_FILES["mediaFile"]) && $_FILES["mediaFile"]["error"] == UPLOAD_ERR_OK) {
            $imagelink_post = $conn->real_escape_string("uploads/" . basename($_FILES["mediaFile"]["name"]));
            move_uploaded_file($_FILES["mediaFile"]["tmp_name"], $imagelink_post);
        } else {
            $imagelink_post = null;
        }

        $sql = "INSERT INTO Post (title_post, descri_post, category_post, imagelink_post ,status_post) 
                VALUES ('$title_post', '$descri_post', '$category_post', '$imagelink_post' ,'Accepted')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success success-alert' role='alert'>Post créé avec succès!</div>";
            echo "<script>setTimeout(function() { document.querySelector('.success-alert').style.display = 'none'; }, 5000);</script>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Erreur: " . $sql . "<br>" . $conn->error . "</div>";
        }

        $conn->close();
    }
    $uploadDirectory = 'uploads';

    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }
    ?>

    <form id="postForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
         <p class="error"></p>
        <div class="form-group">
            <input type="text" class="form-control" name="postTitle" placeholder="Titre" required>
        </div>
        <div class="form-group">
            <textarea class="form-control" name="postDescription" placeholder="Description" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <select class="form-control" name="postCategory">
                <option value="General">Général</option>
                <option value="Fashion">Mode</option>
                <option value="Beauty">Beauté</option>
            </select>
        </div>
       
        <div class="form-group">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="mediaFile" name="mediaFile" accept="image/*">
                <label class="custom-file-label" for="mediaFile">Choisir un fichier</label>
            </div>
            <div class="preview-container" id="previewContainer" style="display: none;">
                <img src="#" alt="Aperçu" class="preview-image" id="previewImage">
                <span class="remove-image" onclick="removeImage()">Supprimer l'image</span>
            </div>
        </div>
        <button type="submit" >Publier</button>
       
    </form>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
<script>
    document.getElementById("mediaFile").addEventListener("change", function () {
        var fileInput = this;
        var previewContainer = document.getElementById("previewContainer");
        var previewImage = document.getElementById("previewImage");

        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = "block";
            };

            reader.readAsDataURL(fileInput.files[0]);
        }
    });

    function removeImage() {
        var fileInput = document.getElementById("mediaFile");
        var previewContainer = document.getElementById("previewContainer");
        var previewImage = document.getElementById("previewImage");

        fileInput.value = "";
        previewContainer.style.display = "none";
        previewImage.src = "";
    }
</script>

</body>
</html>
