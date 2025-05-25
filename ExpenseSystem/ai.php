<?php 
include("session.php");
$exp_fetched = mysqli_query($con, "SELECT * FROM expenses WHERE user_id = '$userid'");
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prompt'])) {
    header('Content-Type: application/json');

    $prompt = trim($_POST['prompt']);
    $apiKey = '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'; // Your OpenRouter API key
    $endpoint = 'https://openrouter.ai/api/v1/chat/completions';

    $payload = [
        'model' => 'openai/gpt-4o',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a helpful assistant for people . Only answer questions related to expenses,savings,as cutbacks (such as where they can cut back to save money,what spendings are essencial and what are non essencial). If a user asks about anything not related to expenses ,savings and cutbacks, respond ONLY with: "Sorry, I can only answer questions about expenses and accountant topics." Do not provide any other information.'
            ],
            [
                'role' => 'user',
                'content' => $prompt
            ],
        ],
        'max_tokens'  => 300,
        'temperature' => 0.7,
    ];

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
        'HTTP-Referer: https://yourdomain.com',
        'X-Title: Hackathon Index'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response  = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        echo json_encode(['error' => "cURL error: $curlError"]);
        exit;
    }

    $data = json_decode($response, true);

    if (isset($data['choices'][0]['message']['content'])) {
        $ai_response = $data['choices'][0]['message']['content'];
        $char_limit = 700;
        if (strlen($ai_response) > $char_limit) {
            $preview = substr($ai_response, 0, $char_limit);
            echo json_encode([
                'result' => nl2br(html_entity_decode($preview)),
                'conversation_data' => json_encode($payload['messages'])
            ]);
        } else {
            echo json_encode([
                'result' => html_entity_decode($ai_response),
                'conversation_data' => json_encode($payload['messages'])
            ]);
        }
    } else {
        echo json_encode([
            'error'        => "Unexpected response structure (HTTP $httpCode)",
            'raw_response' => $response
        ]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="js/feather.min.js"></script>

    <title>Expense Manager - Dashboard</title>
</head>
<body>

    <div class="d-flex" id="wrapper">

        
        <div class="border-right" id="sidebar-wrapper">
            <div class="user">
                <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="120">
                <h5><?php echo $username ?></h5>
                <p><?php echo $useremail ?></p>
            </div>
            <div class="sidebar-heading">Management</div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action"><span data-feather="home"></span> Dashboard</a>
                <a href="add_expense.php" class="list-group-item list-group-item-action sidebar-active"><span data-feather="plus-square"></span> Add Expenses</a>
                <a href="manage_expense.php" class="list-group-item list-group-item-action"><span data-feather="dollar-sign"></span> Manage Expenses</a>
            </div>
            <div class="sidebar-heading">Settings </div>
            <div class="list-group list-group-flush">
                <a href="profile.php" class="list-group-item list-group-item-action "><span data-feather="user"></span> Profile</a>
                <a href="ai.php" class="list-group-item list-group-item-action "><span data-feather="info"></span> AiAssistant</a>
                <a href="logout.php" class="list-group-item list-group-item-action "><span data-feather="power"></span> Logout</a>
            </div>
        </div>
        
        <div id="page-content-wrapper">

            <nav class="navbar navbar-expand-lg navbar-light  border-bottom">


                <button class="toggler" type="button" id="menu-toggle" aria-expanded="false">
                    <span data-feather="menu"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="25">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="profile.phcol-mdp">Your Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container">
                <h3 class="mt-4 text-center">Chat with Our AI Accountant</h3>
                <hr>
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="card shadow-sm mb-5">
                            <div class="card-header bg-success text-white d-flex align-items-center">
                                <img src="download.png" alt="AI Avatar" class="rounded-circle mr-3" style="width:40px;height:40px;">
                                <span class="ml-2 font-weight-bold">AI Accountant Assistant</span>
                            </div>
                            <div class="card-body" style="background:#f8f9fa;">
                                <form id="chatForm">
                                    <div class="form-group">
                                        <label for="prompt" class="font-weight-bold">Ask a question about saving money, expenses, or cutbacks:</label>
                                        <textarea class="form-control mb-2" name="prompt" id="prompt" rows="4" placeholder="E.g. How can I reduce my monthly food expenses?" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block">
                                        <span data-feather="send"></span> Send
                                    </button>
                                </form>
                                <div class="response mt-4 p-3 border rounded bg-white" id="responseBox" style="min-height:80px; font-size:1.1rem;"></div>
                            </div>
                            <div class="card-footer text-muted text-center" style="font-size:0.95rem;">
                                <span data-feather="info"></span>
                                This assistant only answers questions about expenses, savings, and cutbacks.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       

    </div>
    <script src="js/jquery.slim.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/feather.min.js"></script>
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
        feather.replace();

        document.getElementById("chatForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const prompt = document.getElementById("prompt").value;
            const responseBox = document.getElementById("responseBox");
            responseBox.innerHTML = `<span class="text-secondary"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Thinking...</span>`;

            fetch("", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "prompt=" + encodeURIComponent(prompt)
            })
            .then(res => res.json())
            .then(data => {
                const aiResponse = data.result || data.error || "Error: No response.";
                responseBox.innerHTML = `<strong class="text-success">Assistant:</strong><br>${aiResponse}`;
            })
            .catch(() => {
                responseBox.innerHTML = "<span class='text-danger'>Error contacting AI.</span>";
            });
        });
    </script>
</body>
</html>