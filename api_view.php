<?php
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

$teamsUrl = "https://www.thesportsdb.com/api/v1/json/123/search_all_teams.php?l=English_Premier_League";
$teamsJson = fetchAPIData($teamsUrl);
$teamsData = $teamsJson ? json_decode($teamsJson, true) : null;
$teams = $teamsData['teams'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Premier League Teams – From TheSportsDB</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1, h2 { color: #003366; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        img { border-radius: 6px; }
        a { text-decoration: none; color: #003366; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Premier League Teams – From TheSportsDB</h1>
    <a href="index.html">← Back</a><br><br>

    <input type="text" id="teamSearch" onkeyup="filterTable()" placeholder="🔍 Search for a team..." style="padding:8px; width:300px; margin-bottom:10px;">

    <?php if (empty($teams)): ?>
        <p style="color:red;">⚠️ No data found in team API response.</p>
    <?php else: ?>
        <table id="teamsTable">
            <thead>
                <tr><th>Team</th><th>Stadium</th><th>Country</th><th>Logo</th></tr>
            </thead>
            <tbody>
                <?php foreach ($teams as $team): ?>
                    <tr>
                        <td>
                            <a href="players.php?id=<?= $team['idTeam'] ?>">
                                <?= $team['strTeam'] ?? 'N/A' ?>
                            </a>
                        </td>
                        <td><?= $team['strStadium'] ?? 'N/A' ?></td>
                        <td><?= $team['strCountry'] ?? 'N/A' ?></td>
                        <td>
                            <?php if (!empty($team['strTeamBadge'])): ?>
                                <img src="<?= $team['strTeamBadge'] ?>" width="50">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <script>
        function filterTable() {
            let input = document.getElementById("teamSearch");
            let filter = input.value.toLowerCase();
            let table = document.getElementById("teamsTable");
            let tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let row = tr[i];
                let td = row.getElementsByTagName("td")[0];
                if (td) {
                    let txtValue = td.textContent || td.innerText;
                    row.style.display = txtValue.toLowerCase().indexOf(filter) > -1 ? "" : "none";
                }
            }
        }
    </script>
</body>
</html>
