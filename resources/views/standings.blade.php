<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <title>League Standings</title>
    <style>

    </style>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?v=1" rel="stylesheet">

    <script src="{{ asset('js/app.js') }}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>

<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-4">League Standings</h1>

    <!-- New buttons added here -->
    <div class="mb-4">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="reset-matches">Reset or
            Generate Matches for League
        </button>
    </div>

    <div class="mb-4">
        <table class="table-auto w-full border-separate border border-slate-500" id="standings-table">
            <!-- League standings table -->
        </table>
    </div>

    <!-- Week container for results and predictions -->
    <div class="week-containers grid grid-cols-1 gap-4">

    </div>

    <div class="md:max-lg:flex justify-between">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-2" id="next-week">Next Week
        </button>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-2" id="play-all">Play All
            Matches
        </button>
    </div>
</div>
</body>
</html>
