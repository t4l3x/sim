import './bootstrap';
import 'jquery/src/jquery';

const league = 1;
const totalWeeks = await getTotalWeeks(league);
$(document).ready(async function () {
    let currentWeek = 1;

    loadStandings(league);
    // Call the generateResultOfWeek function to create the initial week container for week 1
    generateResultOfWeek(currentWeek);

    // Event handler for "Reset Matches" button
    $('#reset-matches').click(function () {
        resetMatches();
    });

    // Event handler for "Next Week" button
    $('#next-week').click(function () {
        currentWeek++;
        if (currentWeek > totalWeeks) {
            alert('All weeks have been Loaded.');
            return;
        }

        generateResultOfWeek(currentWeek); // Generate new week container for the next week
        $('.current-week').text(currentWeek);
    });

    // Event handler for playing a week
    $(document).on('click', '.play-week', function () {
        const week = $(this).data('week');
        playWeek(league, week);
    });

    // Event handler for playing all matches
    $('#play-all').click(function () {
        currentWeek = totalWeeks;
        playAllMatches(league);
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
    loadWeekResults(league,week);
    loadWeekPredictions(league,week);
}
function resetMatches() {
    // Send AJAX request to resetLeague method
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/reset-league/' + league, // Replace with the appropriate route for resetLeague method
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

function loadStandings(league) {
    $.ajax({
        url: '/standings/' + league ,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            // Update league standings table
            let standingsTable = '<tr><th>Team</th><th>Played</th><th>Won</th><th>Lost</th><th>Draw</th><th>Points</th><th>Goal Difference</th></tr>';
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
            // Handle error here, show an error message or update UI accordingly
        }
    });
}

function loadWeekResults(league,week) {
    $.ajax({
        url: '/week-results/' + league + '/' + week, // Replace with the appropriate route for fetching week results
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

function loadWeekPredictions(league,week) {
    $.ajax({
        url:  '/week-predictions/' + league + '/' + week,// Replace with the appropriate route for fetching week predictions
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

function playWeek(league,week) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/play-week/' + league + '/' + week, // Replace with the appropriate route
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            loadStandings(league);
            loadWeekResults(league,week);
            loadWeekPredictions(league,week);
            console.log('Week ' + week + ' played successfully.');
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}

function playAllMatches(league) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/play-all-matches/' + league,
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            // Get the total weeks and store it in a variable

            // Clear existing containers before generating new ones
            $('.week-containers').empty();
            // Load and display results and predictions for each week
            for (let week = 1; week <= totalWeeks; week++) {
                generateResultOfWeek(week); // Generate new week container for the next week
                $('.current-week').text(week);
            }

            // Update standings after all weeks are played
            loadStandings(league);

            // Display a success message
            console.log('All matches played successfully.');
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}

function getTotalWeeks(league) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: '/total-weeks/' + league,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                // Resolve the promise with the total weeks value
                resolve(response.data.total);
            },
            error: function (xhr, status, error) {
                // Reject the promise with an error (optional)
                console.log(error);
                reject(error);
            }
        });
    });
}
// Event handler for editing a match result
$(document).on('click', '.edit-result', function () {
    const $row = $(this).closest('tr');
    $row.find('.editable').attr('contenteditable', 'true');
    $row.find('.edit-result').hide();
    $row.find('.save-result').show();
});

// Event handler for saving a match result
$(document).on('click', '.save-result', function () {
    const $row = $(this).closest('tr');
    const matchId = $row.data('match-id');
    const homeGoals = $row.find('.editable').eq(0).text();
    const awayGoals = $row.find('.editable').eq(1).text();

    // Get the CSRF token from the meta tag
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/update-result/' + matchId,
        type: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        data: {
            home_goals: homeGoals,
            away_goals: awayGoals
        },
        success: function (response) {
            const currentWeek = parseInt($(this).closest('.week-container').data('week'));

            console.log('Match result updated successfully.');

            // Update week predictions for the same week
            updateWeekPredictions(currentWeek);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
});
// Function to update week predictions for a specific week
function updateWeekPredictions(week) {
    $.ajax({
        url: '/week-predictions/' + league + '/' + week,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            // Update week predictions table for the specified week
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
