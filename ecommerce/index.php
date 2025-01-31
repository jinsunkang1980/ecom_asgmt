<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theos Canada by Jinsun Kang</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: space-between;
            align-items: center;
        }

        .container {
            text-align: center;
            margin-top: 50px;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        p.subtitle {
            font-size: 1.5em;
            margin-bottom: 40px;
        }

        .image-gallery {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 50px;
        }

        .image-gallery img {
            width: 250px;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        footer {
            text-align: center;
            padding: 10px;
            width: 100%;
            background-color: #f8f8f8;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Welcome to Theos Canada by Jinsun Kang</h1>
        <p class="subtitle">Shop the latest clothes at affordable prices!</p>
        
        <div class="uploads">
            <img src="uploads/image1.jpg" alt="Fashion 1">
            <img src="uploads/image2.jpg" alt="Fashion 2">
            <img src="uploads/image3.jpg" alt="Fashion 3">
        </div>
    </div>

   
</body>
</html>



<?php include 'includes/footer.php'; ?>
