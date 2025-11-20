<x-guest-layout>

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

        {{-- Records Table --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
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
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($records as $rec)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-base">{{ $rec->npi_number }}</td>
                            <td class="px-4 py-3 text-base">{{ $rec->organization_name }}</td>
                            <td class="px-4 py-3 text-base">{{ $rec->authorized_official }}</td>
                            <td class="px-4 py-3 text-base">{{ $rec->official_title }}</td>
                            <td class="px-4 py-3 text-base">{{ $rec->telephone }}</td>
                            <td class="px-4 py-3 text-base">{{ $rec->city }}</td>
                            <td class="px-4 py-3 text-base">{{ $rec->state }}</td>
                            <td class="px-4 py-3 text-base">{{ $rec->taxonomy_desc }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center p-6 text-gray-500 text-lg">No records found.</td>
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
</x-guest-layout>
