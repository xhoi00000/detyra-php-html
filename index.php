<?php
session_start(); // For flashing messages (optional)

// Handle form submission
$error = '';
$result = '';
$rateInfo = '';
$showResult = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $euroValue = filter_input(INPUT_POST, 'euro', FILTER_VALIDATE_FLOAT);
    $rateValue = filter_input(INPUT_POST, 'koeficienti', FILTER_VALIDATE_FLOAT);
    
    if ($euroValue === false || $rateValue === false || $euroValue < 0 || $rateValue <= 0) {
        $error = 'Ju lutem shkruani vlera valide (pozitive).';
    } else {
        $lekValue = $euroValue * $rateValue;
        
        // Format numbers (using number_format for Albanian locale simulation)
        $formattedLek = number_format($lekValue, 2, ',', ' ');
        $formattedRate = number_format($rateValue, 2, ',', ' ');
        
        $result = $formattedLek . ' ALL';
        $rateInfo = 'Kursi i këmbimit: 1 EUR = ' . $formattedRate . ' ALL';
        $showResult = true;
        
        // Optional: Store in session for persistence
        $_SESSION['result'] = $result;
        $_SESSION['rateInfo'] = $rateInfo;
    }
}

// Retrieve from session if needed (e.g., after reset)
if (isset($_POST['reset'])) {
    unset($_SESSION['result']);
    unset($_SESSION['rateInfo']);
    $showResult = false;
    $error = ''; // Clear any errors
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Këmbim Valutore - Euro në Lek</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --success: #22c55e;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --text: #1e293b;
            --text-light: #64748b;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--background);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 2rem;
            width: 100%;
            max-width: 500px;
        }
        
        header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        h1 {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            color: var(--text-light);
            font-size: 1rem;
        }
        
        .flag-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }
        
        .flag {
            width: 80px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            background: linear-gradient(to bottom, #ffffff 0%, #f0f0f0 100%);
            border: 1px solid var(--border);
        }
        
        .arrow {
            font-size: 1.5rem;
            color: var(--primary);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text);
        }
        
        input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        button {
            flex: 1;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
        }
        
        button:active {
            transform: scale(0.98);
        }
        
        .convert-btn {
            background-color: var(--primary);
            color: white;
        }
        
        .convert-btn:hover {
            background-color: var(--primary-dark);
        }
        
        .reset-btn {
            background-color: #f1f5f9;
            color: var(--secondary);
            border: 1px solid var(--border);
        }
        
        .reset-btn:hover {
            background-color: #e2e8f0;
        }
        
        .result {
            margin-top: 1.5rem;
            padding: 1.5rem;
            background-color: #f0f9ff;
            border-radius: 8px;
            border-left: 4px solid var(--primary);
            display: <?php echo $showResult ? 'block' : 'none'; ?>;
            animation: fadeIn 0.3s ease-out;
        }
        
        .error {
            margin-top: 1rem;
            padding: 1rem;
            background-color: #fef2f2;
            border-radius: 8px;
            border-left: 4px solid #ef4444;
            color: #dc2626;
            display: <?php echo $error ? 'block' : 'none'; ?>;
        }
        
        .result-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }
        
        .conversion-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
        }
        
        .rate-info {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .disclaimer {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            font-size: 0.8rem;
            color: var(--text-light);
            text-align: center;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 1.5rem;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .flag {
                width: 60px;
                height: 45px;
                font-size: 1.5rem;
            }
            
            .buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Këmbim Valutore</h1>
            <p class="subtitle">Konvertoni Euro në Lek Shqiptar</p>
        </header>
        
        <div class="flag-container">
            <div class="flag">🇪🇺</div>
            <div class="arrow">→</div>
            <div class="flag">🇦🇱</div>
        </div>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="euro">Shkruaj shumën në Euro:</label>
                <input type="number" step="0.01" id="euro" name="euro" placeholder="0.00" min="0" value="<?php echo isset($_POST['euro']) ? htmlspecialchars($_POST['euro']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="koeficienti">Shkruaj koeficientin e këmbimit:</label>
                <input type="number" step="0.01" id="koeficienti" name="koeficienti" placeholder="120.50" min="0.01" value="<?php echo isset($_POST['koeficienti']) ? htmlspecialchars($_POST['koeficienti']) : ''; ?>" required>
            </div>
            
            <div class="buttons">
                <button type="submit" name="submit" class="convert-btn">Konverto</button>
                <button type="submit" name="reset" class="reset-btn">Pastro</button>
            </div>
        </form>
        
        <?php if ($showResult): ?>
            <div class="result">
                <div class="result-title">Rezultati:</div>
                <div class="conversion-value"><?php echo htmlspecialchars($result); ?></div>
                <div class="rate-info"><?php echo htmlspecialchars($rateInfo); ?></div>
            </div>
        <?php endif; ?>
        
        <div class="disclaimer">
            <p>Ky kalkulator është për qëllime informative.</p>
            <p>Kontrolloni gjithmonë kurset aktuale të këmbimit.</p>
        </div>
    </div>

    <script>
        // Minimal JS for animations or future enhancements (optional)
        document.addEventListener('DOMContentLoaded', function() {
            // No core logic here - all handled by PHP
        });
    </script>
</body>
</html>
