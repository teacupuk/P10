<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>P10 - Rules</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 1rem;
            letter-spacing: 0.3px;
        }
        .card {
            box-shadow: none;
            border: none;
        }
        .rules-header {
            border-bottom: 3px solid #e10600;
        }
    </style>
</head>
<body class="bg-white text-dark">
    <header class="mb-4">
        <div class="bg-danger text-white py-2">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="fs-1 fw-bold">P10 Game</span>
                </div>
                <nav class="d-none d-md-flex gap-4 fs-3 fw-semibold">
                    <a href="/" class="text-white text-decoration-none">Leaderboard</a>
                    <a href="/rules" class="text-white text-decoration-none">Rules</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="container my-4 px-3 px-md-4" style="max-width: 960px;">
        <div class="card bg-white text-dark p-4">
            <h2 class="text-uppercase text-black fs-2 fw-bold text-center rules-header pb-2 mb-4">Game Rules</h2>

            <p class="mb-3">The rules below cover the operating parameters for the P10 Game. All changes must be democratically approved.</p>
            <ul class="mb-4">
                <li>The game is to be run at the qualifying session for each race and sprint event.</li>
                <li>Game opens 10 minutes before the start of the session, predictions close as the pit exit opens for Q1.</li>
                <li>The designated adjudicator has to wait until 2 other votes have been submitted.</li>
                <li>The previous 'winner' (1 or 2 points) of the last session has to wait until 2 other votes have been submitted.</li>
                <li>You can change drivers until the game closes at the pit exit open for Q1.</li>
                <li>Each driver prediction must be unique, no pooling votes for drivers together.</li>
                <li>The driver must make it into Q3 to be eligble for points.</li>
                <li>The qualifying results are based on what the track order is as the last car goes over the finish line, to prevent lap deletion bribes.</li>
                <li><strong>2 points</strong> are awarded for predicting the exact P10 driver.</li>
                <li><strong>1 point</strong> is awarded to the closest higher prediction (P9 → P1) if no one gets P10 exactly.</li>
                <li><strong>0 points</strong> are awarded if no qualifying guess is close enough.</li>
                <li>The last race of the season is awarded <strong>double</strong> points.
                <li>No takesies backsies.</li>
                <li>Leaderboard is automatically updated as results are entered.</li>
            </ul>
            <p class="text-muted">All decisions are final, and no edits can be made after the event's qualifying results are locked in.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>