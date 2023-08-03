<!DOCTYPE html>
<html>
<head>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>League Standings</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
    </style>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>

<div class="container mx-auto">
    <h1>League Standings</h1>

    <!-- New buttons added here -->
    <div>

        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="reset-matches">Reset or Generate Matches for League</button>
    </div>

    <table class="border-separate border-spacing-2 border border-slate-500 ..." id="standings-table">
        <!-- League standings table -->
    </table>

    <div id="week-results">
        <!-- Week results table -->
    </div>

    <div id="week-predictions">
        <!-- Week predictions table -->
    </div>

    <div  class="md:max-lg:flex">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="prev-week">Previous Week</button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"  id="next-week">Next Week</button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"  id="play-week">Play Week</button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"  id="play-all">Play All Matches</button>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>


    $(document).ready(function () {
        // Initial load for the first week
        loadWeek(1);

        // Event handler for "Generate League Matches" button
        $('#generate-matches').click(function () {
            generateLeagueMatches();
        });

        // Event handler for "Reset Matches" button
        $('#reset-matches').click(function () {
            resetMatches();
        });

        // Event handlers for previous and next week buttons
        $('#prev-week').click(function () {
            var currentWeek = parseInt($('#week-results').data('week'));
            loadWeek(currentWeek - 1);
        });

        $('#next-week').click(function () {
            var currentWeek = parseInt($('#week-results').data('week'));
            loadWeek(currentWeek + 1);
        });

        // Event handler for playing a week
        $('#play-week').click(function () {
            var currentWeek = parseInt($('#week-results').data('week'));
            console.log($('#week-results').data('week'));
            playWeek(currentWeek);
        });

        // Event handler for playing all matches
        $('#play-all').click(function () {
            playAllMatches();
        });


    });

    function generateLeagueMatches() {

    }

    function resetMatches() {
        // Send AJAX request to resetLeague method
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/reset-league', // Replace with the appropriate route for resetLeague method
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                // Handle success response
                alert(response.message); // Show success message or handle as needed
                // You may also need to refresh the standings table or other content after the reset
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.log(error);
            }
        });
    }

    function loadWeek(week) {
        $.ajax({
            url: '/standings/' + week, // Replace with the appropriate route
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                // Update league standings table
                var standingsTable = '<tr><th>Team</th><th>Played</th><th>Won</th><th>Lost</th><th>Draw</th><th>Points</th><th>Goal Difference</th></tr>';
                $.each(response.standings, function (index, team) {
                    standingsTable += '<tr>' +
                        '<td>' + team.team_name + '</td>' +
                        '<td>' + team.played + '</td>' +
                        '<td>' + team.won + '</td>' +
                        '<td>' + team.lost + '</td>' +
                        '<td>' + team.draw + '</td>' +
                        '<td>' + team.points + '</td>' +
                        '<td>' + team.goal_difference + '</td>' +
                        '</tr>';
                });
                $('#standings-table').html(standingsTable);

                // Update week results table
                var weekResultsTable = '<table class="border-separate border-spacing-2 border border-slate-500 ..."><tr><th>Home Team</th><th>Away Team</th><th>Home Goals</th><th>Away Goals</th><th>Actions</th></tr>';
                $.each(response.matches, function (index, match) {
                    weekResultsTable += '<tr data-match-id="' + match.id + '">' +
                        '<td>' + match.home_team.name + '</td>' +
                        '<td>' + match.away_team.name + '</td>' +
                        '<td class="editable">' + match.home_team_goals + '</td>' +
                        '<td class="editable">' + match.away_team_goals + '</td>' +
                        '<td><button class="edit-result">Edit</button><button class="save-result" style="display: none;">Save</button></td>' +
                        '</tr>';
                });
                weekResultsTable += '</table>';
                $('#week-results').html(weekResultsTable);

                // Update week predictions table
                var weekPredictionsTable = '<table class="border-separate border-spacing-2 border border-slate-500 ..."><tr><th>Team</th><th>Prediction</th></tr>';

                // Dynamically get the week number
                var weekNumber =  response.matches[0].week;
                console.log(weekNumber);

                $.each(response.predictions.predictions[weekNumber], function (index, prediction) {
                    weekPredictionsTable += '<tr>' +
                        '<td>' + prediction.team_name + '</td>' +
                        '<td>' + prediction.team_prediction + '%</td>' +
                        '</tr>';
                });
                weekPredictionsTable += '</table>';
                $('#week-predictions').html(weekPredictionsTable);

                // Update the current week attribute for the buttons
                $('#week-results').data('week', weekNumber);
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }

    function playWeek(week) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/play-week/' + week, // Replace with the appropriate route
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                loadWeek(week);
                console.log('Week ' + week + ' played successfully.');
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }

    function playAllMatches() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/play-all-matches', // Replace with the appropriate route
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                var currentWeek = parseInt($('#week-results').data('week'));
                loadWeek(currentWeek);
                console.log('All matches played successfully.');
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }

    // Event handler for editing a match result
    $(document).on('click', '.edit-result', function () {
        var $row = $(this).closest('tr');
        $row.find('.editable').attr('contenteditable', 'true');
        $row.find('.edit-result').hide();
        $row.find('.save-result').show();
    });

    // Event handler for saving a match result
    $(document).on('click', '.save-result', function () {
        var $row = $(this).closest('tr');
        var matchId = $row.data('match-id');
        var homeGoals = $row.find('.editable').eq(0).text();
        var awayGoals = $row.find('.editable').eq(1).text();

        $.ajax({
            url: '/update-result/' + matchId,  // Replace with the appropriate route
            type: 'POST',
            dataType: 'json',
            data: {
                home_goals: homeGoals,
                away_goals: awayGoals
            },
            success: function (response) {
                var currentWeek = parseInt($('#week-results').data('week'));
                loadWeek(currentWeek);
                console.log('Match result updated successfully.');
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    });
</script>
</body>
</html>
