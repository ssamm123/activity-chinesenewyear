<?php
session_start();

if(isset($_POST['compute'])){

    $angpao1 = (int) $_POST['angpao1'];
    $angpao2 = (int) $_POST['angpao2'];
    $angpao3 = (int) $_POST['angpao3'];
    $foodExpenses = (int) $_POST['foodExpenses'];
    $isDragonYear = isset($_POST['isDragonYear']);
    $luckyNumber = (int) $_POST['luckyNumber'];

    // ================= VALIDATION =================
    if($angpao1 < 0 || $angpao2 < 0 || $angpao3 < 0 || $foodExpenses < 0){
        $error = "❌ Values cannot be negative!";
    } elseif($luckyNumber <= 0){
        $error = "❌ Lucky number must be greater than 0!";
    } else {

        $totalAngPao = $angpao1 + $angpao2 + $angpao3;
        $remainingMoney = $totalAngPao - $foodExpenses;

        if($foodExpenses > 1000000){
            $error = "❌ Food expenses too unrealistic!";
        } else {

            if($isDragonYear){
                $remainingMoney *= 2;
                $dragonMessage = "🐉 Dragon Bonus Applied! Money Doubled!";
            } else {
                $dragonMessage = "No Dragon Bonus this year.";
            }

            $remainingMoney += 500;
            $remainingMoney -= 200;

            $expensesHigher = ($foodExpenses > $totalAngPao);
            $superLucky = ($remainingMoney > 5000 && $luckyNumber == 8);
            $luckyCondition = ($remainingMoney > 5000 || $isDragonYear);
            $notDragon = !$isDragonYear;

            $luckyNumber++;
            $foodExpenses--;

            // ================= FORTUNE LEVEL SYSTEM =================
            if($remainingMoney >= 20000){
                $fortuneTitle = "🏆 Emperor of Wealth";
            } elseif($remainingMoney >= 10000){
                $fortuneTitle = "🐲 Dragon Elite";
            } elseif($remainingMoney >= 5000){
                $fortuneTitle = "💰 Prosperous Citizen";
            } elseif($remainingMoney >= 1000){
                $fortuneTitle = "🧧 Rising Fortune";
            } else {
                $fortuneTitle = "🥠 Fortune in Progress";
            }

            // ================= RANDOM FORTUNE =================
            $fortunes = [
                "Great wealth is coming your way.",
                "A surprise opportunity will appear.",
                "Invest wisely this year.",
                "Your hard work will pay off.",
                "Luck favors the brave."
            ];

            $randomFortune = $fortunes[array_rand($fortunes)];

            // ================= SAVINGS RATE =================
            if($totalAngPao > 0){
                $savingsRate = round(($remainingMoney / $totalAngPao) * 100, 2);
            } else {
                $savingsRate = 0;
            }

            // ================= SESSION STORAGE =================
            $_SESSION['lastFortune'] = $remainingMoney;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<title>Chinese New Year Money Calculator</title>
<link href="https://fonts.googleapis.com/css2?family=Bungee+Spice&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
<h2>Chinese New Year Money Calculator</h2>

<form method="POST">
    <input type="number" name="angpao1" placeholder="Ang Pao 1" required><br>
    <input type="number" name="angpao2" placeholder="Ang Pao 2" required><br>
    <input type="number" name="angpao3" placeholder="Ang Pao 3" required><br>
    <input type="number" name="foodExpenses" placeholder="Food Expenses" required><br>
    <input type="number" name="luckyNumber" placeholder="Lucky Number" required><br>

    <label>
        <input type="checkbox" name="isDragonYear">
        🐉 Year of the Dragon?
    </label>

    <button type="submit" name="compute">🎆 Compute</button>
</form>

<?php if(isset($error)){ ?>
    <div class="error"><?php echo $error; ?></div>
<?php } ?>

<?php if(isset($remainingMoney) && !isset($error)){ ?>
<div class="result">

<h3>🎉 Happy Chinese New Year! 🎉</h3>

🧧 Total Ang Pao: ₱<?php echo $totalAngPao; ?><br>
🥟 Remaining Money: ₱<?php echo $remainingMoney; ?><br>
<?php echo $dragonMessage; ?><br><br>

📈 Savings Rate: <?php echo $savingsRate; ?>%<br><br>

<?php
if($superLucky){
    echo "🌟 <b>SUPER</b> Lucky Year! 🌟<br>";
} elseif($luckyCondition){
    echo "✨ Lucky Year! ✨<br>";
} else {
    echo "Fortune will grow with patience.<br>";
}

if($expensesHigher){
    echo "⚠ Expenses higher than Ang Pao!<br>";
}

if($notDragon){
    echo "This is <b>NOT</b> the Dragon Year.<br>";
}
?>

<br>
🔢 Lucky Number After Increment: <?php echo $luckyNumber; ?><br>
🍜 Food Expenses After Decrement: <?php echo $foodExpenses; ?><br><br>

🎊 Final Fortune Score: ₱<?php echo ($remainingMoney + $luckyNumber); ?><br><br>

<h3><?php echo $fortuneTitle; ?></h3>
🥠 Fortune Message: <?php echo $randomFortune; ?><br><br>

<?php
if(isset($_SESSION['lastFortune'])){
    echo "🕒 Last Computed Fortune: ₱" . $_SESSION['lastFortune'];
}
?>

</div>
<?php } ?>

</div>
</body>
</html>
