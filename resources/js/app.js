import './bootstrap';
import 'jquery/src/jquery';

$(document).ready(function () {
    var currentWeek = 1;
    loadStandings(1);
    // Call the generateResultOfWeek function to create the initial week container for week 1
    generateResultOfWeek(currentWeek);

    // Event handler for "Reset Matches" button
    $('#reset-matches').click(function () {
        resetMatches();
    });

    // Event handlers for previous and next week buttons
    $('#prev-week').click(function () {
        var currentWeek = parseInt($('#week-results').data('week'));
        loadWeekResults(currentWeek - 1);
        loadWeekPredictions(currentWeek - 1);
    });

    // Event handler for "Next Week" button
    $('#next-week').click(function () {
        currentWeek++;
        generateResultOfWeek(currentWeek); // Generate new week container for the next week
        $('.current-week').text(currentWeek);
    });

    // Event handler for playing a week
    // Event handler for playing a week
    $(document).on('click', '.play-week', function () {
        var week = $(this).data('week');
        playWeek(week);
    });

    // Event handler for playing all matches
    $('#play-all').click(function () {
        playAllMatches();
    });


});

function generateResultOfWeek(week) {
    // Generate the HTML for the week container with a specific ID
    let weekContainerHTML = '<div class="week-container" data-week="' + week + '">' +
        '<div class="week-table" id="week-results-' + week + '">' +
        '</div>' +
        '<div class="week-predictions" id="week-predictions-' + week + '">' +
        '</div>' +
        '<div class="md:max-lg:flex">' +
        '<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded play-week" data-week="' + week + '">Play Week ' + week + '</button>' +
        '</div>' +
        '</div>';

    // Append the generated HTML to the "week-containers" div
    $('.week-containers').append(weekContainerHTML);

    // Load the week results and predictions inside this method
    loadWeekResults(week);
    loadWeekPredictions(week);
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

function loadStandings(week) {
    $.ajax({
        url: '/standings/' + week, // Replace with the appropriate route for fetching standings
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            // Update league standings table
            var standingsTable = '<tr><th>Team</th><th>Played</th><th>Won</th><th>Lost</th><th>Draw</th><th>Points</th><th>Goal Difference</th></tr>';
            $.each(response.data.standings, function (index, team) {
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
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}

function loadWeekResults(week) {
    $.ajax({
        url: '/week-results/' + week, // Replace with the appropriate route for fetching week results
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            // Update week results table
            var weekResultsTable = '<table class="border-separate border-spacing-2 border border-slate-500 ..."><tr><th>Home Team</th><th>Away Team</th><th>Home Goals</th><th>Away Goals</th><th>Actions</th></tr>';
            $.each(response.data.matches, function (index, match) {
                weekResultsTable += '<tr data-match-id="' + match.id + '">' +
                    '<td>' + match.home_team.name + '</td>' +
                    '<td>' + match.away_team.name + '</td>' +
                    '<td class="editable">' + match.home_team_goals + '</td>' +
                    '<td class="editable">' + match.away_team_goals + '</td>' +
                    '<td><button class="edit-result">Edit</button><button class="save-result" style="display: none;">Save</button></td>' +
                    '</tr>';
            });
            weekResultsTable += '</table>';
            $('#week-results-' + week).html(weekResultsTable);

            // Update the current week attribute for the buttons
            $('#week-results-' + week).data('week', week);
            $('.current-week').text(week);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}

function loadWeekPredictions(week) {
    $.ajax({
        url: '/week-predictions/' + week, // Replace with the appropriate route for fetching week predictions
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            // Update week predictions table
            var weekPredictionsTable = '<table class="border-separate border-spacing-2 border border-slate-500 ..."><tr><th>Team</th><th>Prediction</th></tr>';
            $.each(response.data.predictions.predictions[week], function (index, prediction) {
                weekPredictionsTable += '<tr>' +
                    '<td>' + prediction.team_name + '</td>' +
                    '<td>' + prediction.team_prediction + '%</td>' +
                    '</tr>';
            });
            weekPredictionsTable += '</table>';
            $('#week-predictions-' + week).html(weekPredictionsTable);
            $('.current-week').text(week);
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
            loadStandings(week);
            loadWeekResults(week);
            loadWeekPredictions(week);
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

            loadStandings(currentWeek);
            loadWeekResults(currentWeek);
            loadWeekPredictions(currentWeek);
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
