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
        <button type="button" class="btn btn-primary" onclick="showPoolStage()">Proceed to Pool Stage</button>
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
    // Global variable for the number of pools and teams in the current pool
    var numberOfPools;
    var teamsInCurrentPool;

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

    function showPoolStage() {
    // Get the number of teams from the input field
    var numberOfTeams = document.getElementById('numberOfTeams').value;

    // Proceed to Pool Stage
    var tournamentHtml = '<h3>Pool Stage</h3>';
    tournamentHtml += '<div class="row">';

    // Calculate the number of pools and the number of teams in each pool
    numberOfPools = Math.ceil(numberOfTeams / 4);
    teamsInCurrentPool = Math.floor(numberOfTeams / numberOfPools);

    // Determine the number of teams in the last pool
    var teamsInLastPool = numberOfTeams % numberOfPools || teamsInCurrentPool;

    // Randomize the order of teams
    var randomizedTeams = [];
    for (var pool = 1; pool <= numberOfPools; pool++) {
        for (var i = 1; i <= teamsInCurrentPool; i++) {
            var teamName = document.getElementById('teamName' + (pool * teamsInCurrentPool + i)).value || 'Team ' + (pool * teamsInCurrentPool + i);
            randomizedTeams.push(teamName);
        }
    }
    randomizedTeams = shuffle(randomizedTeams);

    // Loop through the pools
    for (var pool = 1; pool <= numberOfPools; pool++) {
        tournamentHtml += '<div class="col-md-6">'; // Use col-md-6 for two columns

        // Heading for each pool
        tournamentHtml += '<h2>POOL ' + pool + '</h2>';

        tournamentHtml += '<table class="table">';
        tournamentHtml += '<thead>';
        tournamentHtml += '<tr>';
        tournamentHtml += '<th>Team</th>';
        tournamentHtml += '<th>Result</th>';
        tournamentHtml += '</tr>';
        tournamentHtml += '</thead>';
        tournamentHtml += '<tbody>';

        // Determine the number of teams in the current pool
        var teamsInCurrentPool = (pool === numberOfPools) ? teamsInLastPool : teamsInCurrentPool;

        for (var i = 1; i <= teamsInCurrentPool; i++) {
            var teamName = randomizedTeams.shift(); // Get the next team from the randomized list

            // Display team information including match results in a table
            tournamentHtml += '<tr>';
            tournamentHtml += '<td>' + teamName + '</td>';
            tournamentHtml += '<td>';
            tournamentHtml += '<label for="poolResult' + i + '">Result:</label>';
            tournamentHtml += '<select class="form-control" id="poolResult' + i + '">';
            tournamentHtml += '<option value="win">Win</option>';
            tournamentHtml += '<option value="draw">Draw</option>';
            tournamentHtml += '<option value="loss">Loss</option>';
            tournamentHtml += '</select>';
            tournamentHtml += '</td>';
            tournamentHtml += '</tr>';
        }

        tournamentHtml += '</tbody>';
        tournamentHtml += '</table>';
        tournamentHtml += '</div>';
    }

    tournamentHtml += '</div>'; // End the row

    // Display the Pool Stage
    document.getElementById('tournamentContainer').innerHTML = tournamentHtml;

    // Add a button to proceed to the next stage (Bracket Stage)
    var nextStageButton = '<button type="button" class="btn btn-primary" onclick="updatePoolResults()">Proceed to Bracket Stage</button>';
    document.getElementById('tournamentContainer').insertAdjacentHTML('beforeend', nextStageButton);
}

    // Function to update the pool stage results
    function updatePoolResults() {
        // Get the number of teams from the input field
        var numberOfTeams = document.getElementById('numberOfTeams').value;

        // Loop through the pools
        for (var pool = 1; pool <= numberOfPools; pool++) {
            // Loop through the teams in the current pool
            for (var i = 1; i <= teamsInCurrentPool; i++) {
                var teamName = document.getElementById('teamName' + i).value || 'Team ' + i;
                var resultSelect = document.getElementById('poolResult' + i);
                var result = resultSelect.value;

                // Update team records based on match results
                updateTeamRecord(teamName, result);
            }
        }

        // Proceed to Bracket Stage
        showBracketStage();
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

        // Proceed to Bracket Stage
        var tournamentHtml = '<h3>Bracket Stage</h3>';
        tournamentHtml += '<div class="row">';
        
        for (var i = 1; i <= numberOfTeams; i++) {
            tournamentHtml += '<div class="col-md-4">';
            tournamentHtml += '<div class="match">';
            tournamentHtml += '<label for="result' + i + '">' + 'Match ' + i + ' Result:</label>';
            tournamentHtml += '<select class="form-control" id="result' + i + '">';
            tournamentHtml += '<option value="win">Win</option>';
            tournamentHtml += '<option value="draw">Draw</option>';
            tournamentHtml += '<option value="loss">Loss</option>';
            tournamentHtml += '</select>';
            tournamentHtml += '<button type="button" class="btn btn-primary" onclick="updateBracketResult(' + i + ')">Submit Result</button>';
            tournamentHtml += '</div>';
            tournamentHtml += '</div>';
        }

        tournamentHtml += '</div>'; // End the row

        // Display the Bracket Stage
        document.getElementById('tournamentContainer').innerHTML = tournamentHtml;

        // Add a button to proceed to the next stage (Final Stage)
        var nextStageButton = '<button type="button" class="btn btn-primary" onclick="showFinalStage()">Proceed to Final Stage</button>';
        document.getElementById('tournamentContainer').insertAdjacentHTML('beforeend', nextStageButton);
    }

    function updateBracketResult(matchNumber) {
        // Get the selected result from the dropdown
        var resultSelect = document.getElementById('result' + matchNumber);
        var result = resultSelect.value;

        // Update team records based on match results
        if (result === 'win') {
            // For simplicity, consider team 1 as the winner and team 2 as the loser
            updateTeamRecord(1, 'win');
            updateTeamRecord(2, 'loss');
        } else if (result === 'draw') {
            // For simplicity, consider both teams as drawing
            updateTeamRecord(1, 'draw');
            updateTeamRecord(2, 'draw');
        } else if (result === 'loss') {
            // For simplicity, consider team 1 as the loser and team 2 as the winner
            updateTeamRecord(1, 'loss');
            updateTeamRecord(2, 'win');
        }
    }

    function showFinalStage() {
        // Get the top 2 teams from each pool
        var topTeams = getTopTeams();

        // Proceed to the Final Stage
        var tournamentHtml= '<h3>Final Stage</h3>';
        tournamentHtml += '<div class="row">';

        for (var i = 0; i < topTeams.length; i++) {
            tournamentHtml += '<div class="col-md-4">';
            tournamentHtml += '<div class="match">';
            tournamentHtml += '<label for="finalResult' + i + '">' + 'Match ' + (i + 1) + ' Result:</label>';
            tournamentHtml += '<select class="form-control" id="finalResult' + i + '">';
            tournamentHtml += '<option value="win">Win</option>';
            tournamentHtml += '<option value="draw">Draw</option>';
            tournamentHtml += '<option value="loss">Loss</option>';
            tournamentHtml += '</select>';
            tournamentHtml += '<button type="button" class="btn btn-primary" onclick="updateFinalResult(' + i + ')">Submit Result</button>';
            tournamentHtml += '</div>';
            tournamentHtml += '</div>';
        }

        tournamentHtml += '</div>'; // End the row

        // Display the Final Stage
        document.getElementById('tournamentContainer').innerHTML = tournamentHtml;

        // Add a button to show the final results
        var nextStageButton = '<button type="button" class="btn btn-primary" onclick="showFinalResults()">Show Final Results</button>';
        document.getElementById('tournamentContainer').insertAdjacentHTML('beforeend', nextStageButton);
    }

    function updateFinalResult(matchNumber) {
        // Get the selected result from the dropdown
        var resultSelect = document.getElementById('finalResult' + matchNumber);
        var result = resultSelect.value;

        // Update team records based on match results
        if (result === 'win') {
            // For simplicity, consider team 1 as the winner and team 2 as the loser
            updateTeamRecord(1, 'win');
            updateTeamRecord(2, 'loss');
        } else if (result === 'draw') {
            // For simplicity, consider both teams as drawing
            updateTeamRecord(1, 'draw');
            updateTeamRecord(2, 'draw');
        } else if (result === 'loss') {
            // For simplicity, consider team 1 as the loser and team 2 as the winner
            updateTeamRecord(1, 'loss');
            updateTeamRecord(2, 'win');
        }
    }

    function showFinalResults() {
        // Get the top 2 teams from each pool
        var topTeams = getTopTeams();

        // Display the Final Results
        var finalResultsHtml = '<h3>Final Results</h3>';
        finalResultsHtml += '<ul>';

        for (var i = 0; i < topTeams.length; i++) {
            finalResultsHtml += '<li>';
            finalResultsHtml += 'Team: ' + topTeams[i].name + ' | Wins: ' + topTeams[i].wins + ' | Draws: ' + topTeams[i].draws + ' | Losses: ' + topTeams[i].losses;
            finalResultsHtml += '</li>';
        }

        finalResultsHtml += '</ul>';

        // Display the Final Results
        document.getElementById('tournamentContainer').innerHTML = finalResultsHtml;
    }

    // Function to update team records based on match results
    function updateTeamRecord(team, result) {
        // Update team records based on match results
        for (var i = 0; i < teams.length; i++) {
            if (teams[i].name === team) {
                if (result === 'win') {
                    teams[i].wins++;
                } else if (result === 'draw') {
                    teams[i].draws++;
                } else if (result === 'loss') {
                    teams[i].losses++;
                }
                break;
            }
        }
    }

    // Function to get the top 2 teams from each pool
    function getTopTeams() {
        // Sort teams based on the number of wins (descending order)
        teams.sort(function (a, b) {
            return b.wins - a.wins;
        });

        // Get the top 2 teams from each pool
        var topTeams = [];
        for (var i = 0; i < numberOfPools; i++) {
            topTeams.push(teams[i]);
            topTeams.push(teams[i + numberOfPools]);
        }

        return topTeams;
    }
</script>

</body>
</html>
