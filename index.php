<?php
session_start();

$error = '';
$result = '';
$rateInfo = '';
$showResult = false;

// Lista e valutave
$currencies = [
    'EUR' => 'Euro 🇪🇺',
    'USD' => 'Dollar Amerikan 🇺🇸',
    'CAD' => 'Dollar Kanadez 🇨🇦',
    'AUD' => 'Dollar Australian 🇦🇺',
    'NZD' => 'Dollar Zelanda e Re 🇳🇿',
    'GBP' => 'Pound Britanik 🇬🇧',
    'CHF' => 'Franga Zviceriane 🇨🇭',
    'SEK' => 'Korona Suedeze 🇸🇪',
    'DKK' => 'Korona Daneze 🇩🇰',
    'NOK' => 'Korona Norvegjeze 🇳🇴',
    'JPY' => 'Jen Japonez 🇯🇵',
    'CNY' => 'Yuan Kinez 🇨🇳',
    'TRY' => 'Lira Turke 🇹🇷',
    'HUF' => 'Forint Hungarez 🇭🇺'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset'])) {
        unset($_SESSION['result'], $_SESSION['rateInfo']);
        $showResult = false;
        $error = '';
    } else {
        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
        $rate = filter_input(INPUT_POST, 'rate', FILTER_VALIDATE_FLOAT);
        $currency = $_POST['currency'] ?? 'EUR';

        if ($amount === false || $rate === false || $amount < 0 || $rate <= 0) {
            $error = 'Ju lutem shkruani vlera valide (pozitive).';
        } else {
            $lekValue = $amount * $rate;
            $formattedLek = number_format($lekValue, 2, ',', ' ');
            $formattedRate = number_format($rate, 2, ',', ' ');
            $result = $formattedLek . ' ALL';
            $rateInfo = "Kursi i këmbimit: 1 $currency = $formattedRate ALL";
            $showResult = true;

            $_SESSION['result'] = $result;
            $_SESSION['rateInfo'] = $rateInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Këmbim Valutore</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --text: #1e293b;
            --text-light: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body {
            background-color: var(--background);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 2rem;
            width: 100%;
            max-width: 500px;
        }
        header { text-align: center; margin-bottom: 1.5rem; }
        h1 { color: var(--primary); font-size: 1.8rem; margin-bottom: 0.5rem; }
        .subtitle { color: var(--text-light); }
        .flag-container { display: flex; justify-content: center; align-items: center; gap: 1rem; margin: 1.5rem 0; }
        .flag { width: 70px; height: 50px; font-size: 2rem; display: flex; justify-content: center; align-items: center; border: 1px solid var(--border); border-radius: 8px; }
        .arrow { font-size: 1.5rem; color: var(--primary); }

        .form-group { margin-bottom: 1rem; }
        label { display: block; font-weight: 600; margin-bottom: 0.5rem; }
        select, input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
        }
        input:focus, select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .buttons { display: flex; gap: 1rem; margin-top: 1.5rem; }
        button {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        .convert-btn { background: var(--primary); color: white; }
        .convert-btn:hover { background: var(--primary-dark); }
        .reset-btn { background: #f1f5f9; color: var(--secondary); border: 1px solid var(--border); }
        .reset-btn:hover { background: #e2e8f0; }

        .result, .error {
            margin-top: 1.5rem;
            padding: 1.2rem;
            border-radius: 8px;
            border-left: 4px solid;
        }
        .result { background: #f0f9ff; border-left-color: var(--primary); display: <?= $showResult ? 'block' : 'none' ?>; }
        .error { background: #fef2f2; border-left-color: #ef4444; color: #dc2626; display: <?= $error ? 'block' : 'none' ?>; }

        .result-title { font-weight: bold; color: var(--primary); }
        .conversion-value { font-size: 1.5rem; font-weight: bold; margin-top: 0.5rem; }
        .rate-info { margin-top: 0.5rem; color: var(--text-light); }
        .disclaimer { font-size: 0.8rem; color: var(--text-light); margin-top: 1.5rem; text-align: center; border-top: 1px solid var(--border); padding-top: 1rem; }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Këmbim Valutore</h1>
        <p class="subtitle">Konvertoni monedhën tuaj në Lek Shqiptar 🇦🇱</p>
    </header>

    <div class="flag-container">
        <div class="flag">🌍</div>
        <div class="arrow">→</div>
        <div class="flag">🇦🇱</div>
    </div>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="currency">Zgjidh monedhën:</label>
            <select name="currency" id="currency" required>
                <?php foreach ($currencies as $code => $name): ?>
                    <option value="<?= $code ?>" <?= (isset($_POST['currency']) && $_POST['currency'] == $code) ? 'selected' : '' ?>>
                        <?= "$code - $name" ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="amount">Shuma:</label>
            <input type="number" step="0.01" id="amount" name="amount" placeholder="0.00" min="0" value="<?= isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="rate">Koeficienti i këmbimit (kursi):</label>
            <input type="number" step="0.01" id="rate" name="rate" placeholder="120.50" min="0.01" value="<?= isset($_POST['rate']) ? htmlspecialchars($_POST['rate']) : '' ?>" required>
        </div>

        <div class="buttons">
            <button type="submit" name="submit" class="convert-btn">Konverto</button>
            <button type="submit" name="reset" class="reset-btn">Pastro</button>
        </div>
    </form>

    <?php if ($showResult): ?>
        <div class="result">
            <div class="result-title">Rezultati:</div>
            <div class="conversion-value"><?= htmlspecialchars($result) ?></div>
            <div class="rate-info"><?= htmlspecialchars($rateInfo) ?></div>
        </div>
    <?php endif; ?>

    <div class="disclaimer">
        <p>Ky kalkulator është për qëllime informative.</p>
        <p>Kontrolloni gjithmonë kurset aktuale të këmbimit.</p>
    </div>
</div>
</body>
</html>
