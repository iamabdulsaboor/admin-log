<x-guest-layout>
    <div class="p-6">
        <h2 class="text-xl font-semibold mb-4">NPI Lookup Tool</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-2 rounded mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('npi.store') }}" method="POST" class="mb-6">
            @csrf
            <div class="flex gap-2">
                <input type="text" name="npi_number" placeholder="Enter NPI Number"
                       class="border rounded p-2 w-full" required>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Search & Save
                </button>
            </div>
        </form>

        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">NPI</th>
                    <th class="border px-3 py-2">Organization</th>
                    <th class="border px-3 py-2">Official</th>
                    <th class="border px-3 py-2">Title</th>
                    <th class="border px-3 py-2">Phone</th>
                    <th class="border px-3 py-2">City</th>
                    <th class="border px-3 py-2">State</th>
                    <th class="border px-3 py-2">Taxonomy</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $rec)
                    <tr>
                        <td class="border px-3 py-2">{{ $rec->npi_number }}</td>
                        <td class="border px-3 py-2">{{ $rec->organization_name }}</td>
                        <td class="border px-3 py-2">{{ $rec->authorized_official }}</td>
                        <td class="border px-3 py-2">{{ $rec->official_title }}</td>
                        <td class="border px-3 py-2">{{ $rec->telephone }}</td>
                        <td class="border px-3 py-2">{{ $rec->city }}</td>
                        <td class="border px-3 py-2">{{ $rec->state }}</td>
                        <td class="border px-3 py-2">{{ $rec->taxonomy_desc }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center p-3">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-guest-layout>
