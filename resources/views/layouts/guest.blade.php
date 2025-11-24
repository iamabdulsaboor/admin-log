<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-gray-900">

    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-4 px-6 flex items-center justify-between">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
                <h1 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    Dashboard
                </h1>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 shadow rounded-lg">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        {{ $slot }}
                    </tbody>
                </table>
            </div>
        </main>
    </div>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: aliceblue;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        background-color: aliceblue;

    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    th {
        background-color: aliceblue;
        text-align: left;
    }
</style>
<!-- Modal -->
<div id="callModal"
    class="fixed inset-0 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">Update Call Notes</h2>

        <textarea id="callNotes" class="w-full border p-3 rounded"
                  placeholder="Enter call notes..."></textarea>

        <div class="flex justify-end gap-3 mt-4">
            <button type="button" onclick="closeCallModal()"
                    class="px-4 py-2 bg-gray-400 text-white rounded">
                Cancel
            </button>

            <button onclick="saveCall()"
                    class="px-4 py-2 bg-blue-600 text-white rounded">
                Save
            </button>
        </div>
    </div>
</div>

<script>
    let activeID = null;

    function openCallModal(id) {
        activeID = id;
        document.getElementById('callModal').classList.remove('hidden');
    }

    function closeCallModal() {
        document.getElementById('callModal').classList.add('hidden');
        activeID = null;
    }

    function saveCall() {
        let notes = document.getElementById('callNotes').value;

        fetch(`/npi/call/${activeID}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ call_notes: notes })
        })
        .then(res => res.json())
        .then(data => {
            // update UI instantly
            document.getElementById(`calledStatus-${activeID}`).innerText = "Called";
            document.getElementById(`calledStatus-${activeID}`).className =
                "px-3 py-1 rounded text-sm bg-green-600 text-white";

            document.getElementById(`callCount-${activeID}`).innerText = data.call_count;
            document.getElementById(`lastCalled-${activeID}`).innerText = data.last_called_at;

            closeCallModal();
        });
    }
</script>

</body>
</html>
