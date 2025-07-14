<?php
if (!isset($_GET['id'])) {
    die("Team ID is required.");
}

$teamID = $_GET['id'];

function fetchAPIData($url) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);
    return $error ? false : $response;
}

$apiUrl = "https://www.thesportsdb.com/api/v1/json/123/lookup_all_players.php?id=$teamID";
$json = fetchAPIData($apiUrl);
$data = $json ? json_decode($json, true) : null;
$players = $data['player'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Team Players</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { color: #003366; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        img { border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Players in This Team</h1>
    <a href="api_view.php">← Back to Teams</a><br><br>

    <?php if (empty($players)): ?>
        <p style="color:red;">⚠️ No players found for this team.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Nationality</th>
                <th>Thumbnail</th>
            </tr>
            <?php foreach ($players as $player): ?>
                <tr>
                    <td><?= $player['strPlayer'] ?? 'N/A' ?></td>
                    <td><?= $player['strPosition'] ?? 'N/A' ?></td>
                    <td><?= $player['strNationality'] ?? 'N/A' ?></td>
                    <td>
                        <?php if (!empty($player['strThumb'])): ?>
                            <img src="<?= $player['strThumb'] ?>" width="50">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
