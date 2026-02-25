<?php
/**
 * Prize Draw Number Generator
 * Generates 20 unique random numbers between 35 and 1500
 */

// Initialize array to store unique numbers
$prizeNumbers = [];

// Generate 20 unique random numbers
while (count($prizeNumbers) < 20) {
    $randomNumber = rand(35, 1500);
    
    // Only add if the number is not already in the array
    if (!in_array($randomNumber, $prizeNumbers)) {
        $prizeNumbers[] = $randomNumber;
    }
}

// Sort the numbers for better presentation (optional)
sort($prizeNumbers);

// Display the results
echo "<h2>Prize Draw - 20 Unique Winning Numbers</h2>";
echo "<p>Numbers range: 35 to 1500</p>";
echo "<hr>";

echo "<h3>Winning Numbers:</h3>";
echo "<ol>";
foreach ($prizeNumbers as $number) {
    echo "<li><strong>$number</strong></li>";
}
echo "</ol>";

echo "<hr>";
echo "<p>Total unique numbers generated: " . count($prizeNumbers) . "</p>";
