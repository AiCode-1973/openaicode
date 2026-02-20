<?php
$config = @include 'scripts/config.php';
$apiKey = $config['api_key'] ?? null;
$chatResponse = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    require_once 'scripts/connector.php';
    $ai = new AiCodeConnector($apiKey);
    
    $soul = file_exists('SOUL.md') ? file_get_contents('SOUL.md') : "";
    $user = file_exists('USER.md') ? file_get_contents('USER.md') : "";
    $memory = file_exists('MEMORY.md') ? file_get_contents('MEMORY.md') : "";
    
    $context = "Identidade: $soul\nUsuário: $user\nMemória: $memory";
    $chatResponse = $ai->ASK($_POST['message'], $context);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AiCode - Dashboard do Assistente</title>
    <style>
        :root {
            --acid-green: #a3ff00;
            --dark-grey: #1a1a1a;
            --light-grey: #2d2d2d;
            --text-color: #e0e0e0;
            --accent-blue: #00d4ff;
        }

        body {
            background-color: var(--dark-grey);
            color: var(--text-color);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            overflow-x: hidden;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--acid-green);
            padding-bottom: 15px;
            margin-bottom: 30px;
            animation: fadeInDown 0.8s ease-out;
        }

        h1 {
            color: var(--acid-green);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 1.8em;
            text-shadow: 0 0 10px rgba(163, 255, 0, 0.3);
        }

        .status-badge {
            background-color: var(--acid-green);
            color: black;
            padding: 6px 18px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.85em;
            box-shadow: 0 0 15px rgba(163, 255, 0, 0.4);
        }

        .grid {
            display: grid;
            grid-template-columns: 350px 1fr 350px;
            gap: 25px;
        }

        .card {
            background: linear-gradient(145deg, #252525, #2d2d2d);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.05);
            transition: transform 0.3s ease;
        }

        .card h2 {
            color: var(--acid-green);
            font-size: 1.1em;
            margin-top: 0;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
        }

        .card h2::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 18px;
            background: var(--acid-green);
            margin-right: 12px;
            border-radius: 2px;
        }

        /* Chat Styles */
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 650px;
        }

        .chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 15px;
            background: rgba(0,0,0,0.2);
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .message {
            margin-bottom: 15px;
            padding: 12px 18px;
            border-radius: 12px;
            max-width: 85%;
            line-height: 1.5;
            font-size: 0.95em;
        }

        .message.user {
            background: var(--light-grey);
            align-self: flex-end;
            margin-left: auto;
            border-bottom-right-radius: 2px;
            border-right: 3px solid var(--accent-blue);
        }

        .message.ai {
            background: rgba(163, 255, 0, 0.05);
            align-self: flex-start;
            border-bottom-left-radius: 2px;
            border-left: 3px solid var(--acid-green);
        }

        .chat-input-area {
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            flex-grow: 1;
            background: #111;
            border: 1px solid #444;
            color: white;
            padding: 12px 15px;
            border-radius: 8px;
            outline: none;
            transition: border 0.3s;
        }

        input[type="text"]:focus {
            border-color: var(--acid-green);
        }

        button {
            background: var(--acid-green);
            color: black;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(163, 255, 0, 0.4);
        }

        .memory-item {
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 12px 0;
        }

        .memory-item .date {
            color: var(--acid-green);
            font-size: 0.75em;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        pre {
            background: #0d0d0d;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.8em;
            color: #aaa;
            margin: 0;
            white-space: pre-wrap;
        }

        .skill-tag {
            background: rgba(163, 255, 0, 0.1);
            color: var(--acid-green);
            border: 1px solid rgba(163, 255, 0, 0.3);
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.75em;
            font-weight: 600;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--dark-grey); }
        ::-webkit-scrollbar-thumb { background: #444; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>AiCode <span style="font-weight: 200; color: #666;">|</span> OS</h1>
            <div class="status-badge">NEURAL_LINK_ESTABLISHED</div>
        </header>

        <div class="grid">
            <!-- Coluna Esquerda: Info & Identidade -->
            <div class="sidebar-left">
                <div class="card" style="margin-bottom: 20px;">
                    <h2>Neural Identity</h2>
                    <p style="font-size: 0.9em; margin-bottom: 8px;"><strong>Agent:</strong> AiCode</p>
                    <p style="font-size: 0.9em; margin-bottom: 8px;"><strong>Core:</strong> Gemini 1.5 Flash</p>
                    <p style="font-size: 0.9em; margin-bottom: 8px;"><strong>Vibe:</strong> Tech-Acid / Professional</p>
                    <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.05); margin: 15px 0;">
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        <?php
                        $skills = glob('skills/*', GLOB_ONLYDIR);
                        foreach ($skills as $skill) {
                            $name = basename($skill);
                            echo "<span class='skill-tag'>$name</span>";
                        }
                        ?>
                    </div>
                </div>

                <div class="card">
                    <h2>Heartbeat Tasks</h2>
                    <div style="font-size: 0.85em; color: #888;">
                        <?php
                        $hb = file_exists('HEARTBEAT.md') ? file_get_contents('HEARTBEAT.md') : "";
                        preg_match_all('/- \[( |x)\] (.*)/', $hb, $matches);
                        if (!empty($matches[2])) {
                            foreach (array_slice($matches[2], 0, 5) as $task) {
                                echo "<div style='margin-bottom: 10px; display: flex; align-items: flex-start;'>";
                                echo "<span style='color: var(--acid-green); margin-right: 10px;'>[●]</span>";
                                echo "<span>$task</span>";
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Coluna Central: Chat Terminal -->
            <div class="main-content">
                <div class="card chat-container">
                    <h2>AiCode Chat Terminal</h2>
                    <div class="chat-messages" id="chatWindow">
                        <?php if ($chatResponse): ?>
                            <div class="message ai">
                                <strong>AiCode:</strong><br>
                                <?php echo nl2br(htmlspecialchars($chatResponse)); ?>
                            </div>
                        <?php else: ?>
                            <div class="message ai">
                                <strong>AiCode:</strong><br>
                                Link neural estabelecido. Estou pronto para processar suas demandas de desenvolvimento. O que vamos construir hoje?
                            </div>
                        <?php endif; ?>
                    </div>
                    <form action="" method="POST" class="chat-input-area">
                        <input type="text" name="message" placeholder="Envie um comando ou pergunte algo ao sistema..." required autocomplete="off">
                        <button type="submit">EXECUTAR</button>
                    </form>
                </div>
            </div>

            <!-- Coluna Direita: Logs & Memória -->
            <div class="sidebar-right">
                <div class="card">
                    <h2>Recent Memory Logs</h2>
                    <div style="max-height: 600px; overflow-y: auto;">
                        <?php
                        $memFiles = glob('memory/*.md');
                        rsort($memFiles);
                        foreach (array_slice($memFiles, 0, 2) as $file) {
                            $content = file_get_contents($file);
                            $date = basename($file, '.md');
                            echo "<div class='memory-item'>";
                            echo "<span class='date'>LOG $date</span>";
                            echo "<pre>" . htmlspecialchars(substr($content, 0, 200)) . "...</pre>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const chatWindow = document.getElementById('chatWindow');
        chatWindow.scrollTop = chatWindow.scrollHeight;
    </script>
</body>
</html>
