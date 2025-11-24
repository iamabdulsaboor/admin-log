<x-guest-layout>
<x-state-time-widget />
<div> 
    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-3xl font-semibold mb-6">NPI Lookup Tool</h2>

        {{-- Success / Error Messages --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Search Form --}}
        <form action="{{ route('npi.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="npi_number" placeholder="Enter NPI Number"
                       class="border rounded p-3 w-full sm:w-auto flex-1 text-base" required>
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition text-base font-medium">
                    Search & Save
                </button>
            </div>
        </form>

        {{-- FILTER SECTION --}}
        <div class="mb-4 flex flex-col sm:flex-row gap-3">
            {{-- Real-time search --}}
            <input id="liveSearch" type="text" placeholder="Search in table (real-time)..."
                   class="border rounded p-3 w-full sm:w-1/2 text-base">

            {{-- Select State --}}
            <select id="stateFilter" class="border rounded p-3 w-full sm:w-1/3 text-base">
                <option value="">Filter by State</option>
                @foreach ($records->pluck('state')->unique()->sort() as $state)
                    @if($state)
                        <option value="{{ $state }}">{{ $state }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        {{-- Records Table --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table id="npiTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-base font-semibold text-gray-700">NPI</th>
                        <th class="px-4 py-3 text-left text-base font-semibold text-gray-700">Organization</th>
                        <th class="px-4 py-3 text-left text-base font-semibold text-gray-700">Official</th>
                        <th class="px-4 py-3 text-left text-base font-semibold text-gray-700">Title</th>
                        <th class="px-4 py-3 text-left text-base font-semibold text-gray-700">Phone</th>
                        <th class="px-4 py-3 text-left text-base font-semibold text-gray-700">City</th>
                        <th class="px-4 py-3 text-left text-base font-semibold text-gray-700">State</th>
                        <th class="px-4 py-3 text-left text-base font-semibold text-gray-700">Taxonomy</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
    @forelse ($records as $rec)
        <tr id="row-{{ $rec->id }}" class="hover:bg-gray-50 transition">
            <td class="px-4 py-3 text-base">{{ $rec->npi_number }}</td>
            <td class="px-4 py-3 text-base">{{ $rec->organization_name }}</td>
            <td class="px-4 py-3 text-base">{{ $rec->authorized_official }}</td>
            <td class="px-4 py-3 text-base">{{ $rec->official_title }}</td>
            <td class="px-4 py-3 text-base">{{ $rec->telephone }}</td>
            <td class="px-4 py-3 text-base">{{ $rec->city }}</td>
            <td class="px-4 py-3 text-base stateCell">{{ $rec->state }}</td>
            <td class="px-4 py-3 text-base">{{ $rec->taxonomy_desc }}</td>

            {{-- NEW COLUMNS --}}
            <td class="px-4 py-3 text-base">
                <span id="calledStatus-{{ $rec->id }}"
                      class="px-3 py-1 rounded text-sm 
                      {{ $rec->is_called ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                      {{ $rec->is_called ? 'Called' : 'Not Called' }}
                </span>
            </td>

            <td class="px-4 py-3 text-base" id="callCount-{{ $rec->id }}">
                {{ $rec->call_count }}
            </td>

            <td class="px-4 py-3 text-base" id="lastCalled-{{ $rec->id }}">
                {{ $rec->last_called_at ?? 'Never' }}
            </td>

            <td class="px-4 py-3 text-base">
                <button onclick="openCallModal({{ $rec->id }})"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    Call Now
                </button>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="12" class="text-center p-6 text-gray-500 text-lg">No records found.</td>
        </tr>
    @endforelse
</tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $records->links() }}
        </div>
    </div>

    {{-- ADVANCED REAL-TIME JS --}}
    <script>
        const searchInput = document.getElementById('liveSearch');
        const stateFilter = document.getElementById('stateFilter');
        const tableBody = document.getElementById('tableBody');
        const rows = tableBody.getElementsByTagName('tr');

        // Real-time search + state filter
        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const selectedState = stateFilter.value.toLowerCase();

            for (let i = 0; i < rows.length; i++) {
                let row = rows[i];
                let rowText = row.innerText.toLowerCase();
                let rowState = row.querySelector('.stateCell')?.innerText.toLowerCase();

                let matchSearch = rowText.includes(searchValue);
                let matchState = selectedState === "" || rowState === selectedState;

                row.style.display = (matchSearch && matchState) ? "" : "none";
            }
        }

        searchInput.addEventListener('keyup', filterTable);
        stateFilter.addEventListener('change', filterTable);
    </script>
<div/>
</x-guest-layout>
