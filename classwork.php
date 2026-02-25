<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classwork</title>
</head>
<body style="text-align: center;display: flex;flex-direction: column;align-items: center;justify-content: center;height: 100vh;">
    <?php

        $name = "Kalejaiye Oluwadara";
        $color = "rED";
        $city = "Ogun";

        echo "All lowercase: <h1>" . strtolower($name) . "</h1>";
        echo "First letter lowercase: <h2>" . lcfirst($color) . "</h2>";
        echo "First letter uppercase: <h3>" . ucfirst($city) . "</h3>";
        echo "All uppercase: <h4>" . strtoupper($name) . "</h4>";

     ?>
</body>
</html>