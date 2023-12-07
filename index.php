<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="img/DB.png">
    <title>Football Tournament</title>    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Add custom CSS styles if needed */
        .fade-section {
            transition: opacity 1s ease-in-out;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <!-- Section 1: Input Number of Teams -->
    <div id="section1" class="fade-section">
        <form id="teamForm">
            <div class="form-group">
                <label for="numberOfTeams">Number of Teams:</label>
                <input type="number" class="form-control" id="numberOfTeams" placeholder="Enter the number of teams" min="1">
            </div>
            <button type="button" class="btn btn-primary" onclick="showTeamNamesSection()" id="generateTeamsBtn">Generate Teams</button>
        </form>
    </div>

    <!-- Section 2: Input Team Names -->
    <div id="section2" class="fade-section" style="display: none;">
        <div id="teamNamesInput">
            <!-- Input fields for team names will be dynamically generated here -->
        </div>
        <button type="button" class="btn btn-primary" onclick="showTournamentSection()">Generate Tournament</button>
    </div>

    <!-- Section 3: Display Tournament Structure -->
    <div id="section3" class="fade-section" style="display: none;">
        <div id="tournamentContainer">
            <!-- Tournament structure will be dynamically generated here -->
        </div>
    </div>
</div>

<!-- Add JavaScript for interactivity -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function showTeamNamesSection() {
        // Get the number of teams from the input field
        var numberOfTeams = document.getElementById('numberOfTeams').value;

        // Validate the input (you may want to add more validation)
        if (isNaN(numberOfTeams) || numberOfTeams < 2) {
            alert('Please enter a valid number of teams (minimum 2).');
            return;
        }

        // Disable the "Generate Teams" button
        document.getElementById('generateTeamsBtn').disabled = true;

        // Clear previous team names
        document.getElementById('teamNamesInput').innerHTML = '';

        // Input fields for team names
        var teamNamesHtml = '<div class="form-group">';
        teamNamesHtml += '<label for="teamNames">Enter Team Names:</label>';

        for (var i = 1; i <= numberOfTeams; i++) {
            teamNamesHtml += '<input type="text" class="form-control" id="teamName' + i + '" placeholder="Team ' + i + '">';
        }

        teamNamesHtml += '</div>';
        document.getElementById('teamNamesInput').innerHTML = teamNamesHtml;

        // Fade out section 1 and fade in section 2
        document.getElementById('section1').style.opacity = '0';
        setTimeout(function () {
            document.getElementById('section1').style.display = 'none';
            document.getElementById('section2').style.display = 'block';
            setTimeout(function () {
                document.getElementById('section2').style.opacity = '1';
            }, 100);
        }, 1000); // 1000 milliseconds = 1 second
    }

    function showTournamentSection() {
        // Get the number of teams from the input field
        var numberOfTeams = document.getElementById('numberOfTeams').value;

        // Validate the input (you may want to add more validation)
        if (isNaN(numberOfTeams) || numberOfTeams < 2) {
            alert('Please enter a valid number of teams (minimum 2).');
            return;
        }

        // Fade out section 2 and fade in section 3
        document.getElementById('section2').style.opacity = '0';
        setTimeout(function () {
            document.getElementById('section2').style.display = 'none';
            document.getElementById('section3').style.display = 'block';
            setTimeout(function () {
                document.getElementById('section3').style.opacity = '1';
                // Call the function to generate the tournament structure
                generateTournament();
            }, 100);
        }, 1000); // 1000 milliseconds = 1 second
    }

    function generateTournament() {
        // Get the number of teams from the input field
        var numberOfTeams = document.getElementById('numberOfTeams').value;

        // Validate the input (you may want to add more validation)
        if (isNaN(numberOfTeams) || numberOfTeams < 2) {
            alert('Please enter a valid number of teams (minimum 2).');
            return;
        }

        // Clear previous tournament structure
        document.getElementById('tournamentContainer').innerHTML = '';

        // Randomize the order of teams
        var randomizedTeams = [];
        for (var i = 1; i <= numberOfTeams; i++) {
            var teamName = document.getElementById('teamName' + i).value || 'Team ' + i;
            randomizedTeams.push(teamName);
        }
        randomizedTeams = shuffle(randomizedTeams);

        // Proceed to the pool stage for more than 16 teams
        var tournamentHtml = '<h3>Pool Stage</h3>';
        tournamentHtml += '<div class="row">';

        // Calculate the number of pools and the number of teams in each pool
        var numberOfPools = Math.ceil(numberOfTeams / 4);
        var teamsInLastPool = numberOfTeams % 4 || 4; // Number of teams in the last pool, minimum 1

        // Determine the number of teams in each pool
        var teamsInEachPool = Math.floor(numberOfTeams / numberOfPools);

        // Calculate the number of teams that need to be distributed among the pools
        var remainingTeams = numberOfTeams % numberOfPools;

        // Loop through the pools
        for (var pool = 1; pool <= numberOfPools; pool++) {
            tournamentHtml += '<div class="col-md-6">'; // Use col-md-6 for two columns

            // Heading for each pool
            tournamentHtml += '<h2>POOL ' + pool + '</h2>';

            tournamentHtml += '<table class="table">';
            tournamentHtml += '<thead>';
            tournamentHtml += '<tr>';
            tournamentHtml += '<th>Team</th>';
            tournamentHtml += '<th>W</th>';
            tournamentHtml += '<th>D</th>';
            tournamentHtml += '<th>L</th>';
            tournamentHtml += '</tr>';
            tournamentHtml += '</thead>';
            tournamentHtml += '<tbody>';

            // Determine the number of teams in the current pool
            var teamsInCurrentPool = teamsInEachPool + ((remainingTeams > 0 && pool <= remainingTeams) ? 1 : 0);

            for (var i = 1; i <= teamsInCurrentPool; i++) {
                var teamName = randomizedTeams.shift(); // Get the next team from the randomized list

                // Display team information including wins, losses, and draws in a table
                tournamentHtml += '<tr>';
                tournamentHtml += '<td>' + teamName + '</td>';
                tournamentHtml += '<td id="wins' + i + '">0</td>';
                tournamentHtml += '<td id="draws' + i + '">0</td>';
                tournamentHtml += '<td id="losses' + i + '">0</td>';
                tournamentHtml += '</tr>';
            }

            tournamentHtml += '</tbody>';
            tournamentHtml += '</table>';
            tournamentHtml += '</div>';
        }

        tournamentHtml += '</div>'; // End the row

        // Display the tournament structure
        document.getElementById('tournamentContainer').innerHTML = tournamentHtml;

        // Add a button to proceed to the next stage (Bracket Stage)
        var nextStageButton = '<button type="button" class="btn btn-primary" onclick="showBracketStage()">Proceed to Bracket Stage</button>';
        document.getElementById('tournamentContainer').insertAdjacentHTML('beforeend', nextStageButton);
    }

    // Function to shuffle an array (Fisher-Yates algorithm)
    function shuffle(array) {
        var currentIndex = array.length, randomIndex, tempValue;

        while (currentIndex !== 0) {
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex--;

            tempValue = array[currentIndex];
            array[currentIndex] = array[randomIndex];
            array[randomIndex] = tempValue;
        }

        return array;
    }

    function showBracketStage() {
        // Get the number of teams from the input field
        var numberOfTeams = document.getElementById('numberOfTeams').value;

        // Get the top 2 teams from each pool
        var topTeams = getTopTeams();

        // Proceed to Bracket Stage
        var tournamentHtml = '<h3>Bracket Stage - Round 1</h3>';
        tournamentHtml += '<div class="row">';

        // Generate matchups between teams from different pools
        for (var i = 0; i < topTeams.length / 2; i++) {
            var team1 = topTeams[i * 2];
            var team2 = topTeams[i * 2 + 1];

            tournamentHtml += '<div class="col-md-4">';
            tournamentHtml += '<div class="match">';
            tournamentHtml += '<h4>Match ' + (i + 1) + '</h4>';
            tournamentHtml += '<label for="result' + i + '">' + team1 + ' vs ' + team2 + ' Result:</label>';
            tournamentHtml += '<select class="form-control" id="result' + i + '">';
            tournamentHtml += '<option value="win">Win</option>';
            tournamentHtml += '<option value="draw">Draw</option>';
            tournamentHtml += '<option value="loss">Loss</option>';
            tournamentHtml += '</select>';
            tournamentHtml += '<label for="goals' + i + '">Number of Goals:</label>';
            tournamentHtml += '<input type="number" class="form-control" id="goals' + i + '" placeholder="Enter goals">';
            tournamentHtml += '<button type="button" class="btn btn-primary" onclick="updateBracketResult(' + i + ')">Submit Result</button>';
            tournamentHtml += '</div>';
            tournamentHtml += '</div>';
        }

        tournamentHtml += '</div>'; // End the row

        // Display the Bracket Stage
        document.getElementById('tournamentContainer').innerHTML = tournamentHtml;

        // Add a button to proceed to the next round
        var nextRoundButton = '<button type="button" class="btn btn-primary" onclick="showNextBracketRound()">Proceed to Round 2</button>';
        document.getElementById('tournamentContainer').insertAdjacentHTML('beforeend', nextRoundButton);
    }

    function updateBracketResult(matchNumber) {
        // Get the selected result from the dropdown
        var resultSelect = document.getElementById('result' + matchNumber);
        var result = resultSelect.value;

        // Get the number of goals
        var goalsInput = document.getElementById('goals' + matchNumber);
        var goals = goalsInput.value;

        // Update team records based on match results
        // Assume topTeams is an array containing the top 2 teams from each pool
        var team1 = topTeams[matchNumber * 2];
        var team2 = topTeams[matchNumber * 2 + 1];

        if (result === 'win') {
            updateTeamRecord(team1, 'win');
            updateTeamRecord(team2, 'loss');
        } else if (result === 'draw') {
            updateTeamRecord(team1, 'draw');
            updateTeamRecord(team2, 'draw');
        } else if (result === 'loss') {
            updateTeamRecord(team1, 'loss');
            updateTeamRecord(team2, 'win');
        }

        // For demonstration purposes, you can do something with the number of goals (e.g., display it)
        console.log('Match ' + (matchNumber + 1) + ' Goals: ' + goals);
    }

    function showNextBracketRound() {
        // You can implement logic to handle subsequent rounds if needed
        alert('Implement logic for the next round.');
    }

    function getTopTeams() {
        var topTeams = [];
        var numberOfPools = Math.ceil(numberOfTeams / 4);

        for (var pool = 1; pool <= numberOfPools; pool++) {
            var team1 = document.getElementById('teamName' + (pool - 1) * 4 + 1).value;
            var team2 = document.getElementById('teamName' + (pool - 1) * 4 + 2).value;

            topTeams.push(team1);
            topTeams.push(team2);
        }

        return topTeams;
    }

    function updateTeamRecord(teamName, result) {
        var winsElement = document.getElementById('wins' + teamName);
        var lossesElement = document.getElementById('losses' + teamName);
        var drawsElement = document.getElementById('draws' + teamName);

        if (result === 'win') {
            winsElement.textContent = parseInt(winsElement.textContent) + 1;
        } else if (result === 'loss') {
            lossesElement.textContent = parseInt(lossesElement.textContent) + 1;
        } else if (result === 'draw') {
            drawsElement.textContent = parseInt(drawsElement.textContent) + 1;
        }
    }
</script>

</body>
</html>
