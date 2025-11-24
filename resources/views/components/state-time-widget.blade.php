<div>
<div class="max-w-7xl mx-auto p-4">
    <h2 class="text-2xl font-semibold mb-4">US States – Current Time</h2>

    <div id="statesTimeContainer"
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

        {{-- JS will inject state cards here --}}
    </div>
</div>

<script>
    const statesTimeZones = {
        "California": "America/Los_Angeles",
        "Nevada": "America/Los_Angeles",
        "Washington": "America/Los_Angeles",
        "Oregon": "America/Los_Angeles",

        "Arizona": "America/Phoenix",

        "Texas": "America/Chicago",
        "Illinois": "America/Chicago",
        "Alabama": "America/Chicago",
        "Minnesota": "America/Chicago",
        "Missouri": "America/Chicago",
        "Wisconsin": "America/Chicago",
        "Iowa": "America/Chicago",
        "Oklahoma": "America/Chicago",
        "Louisiana": "America/Chicago",

        "New York": "America/New_York",
        "Florida": "America/New_York",
        "Georgia": "America/New_York",
        "New Jersey": "America/New_York",
        "Pennsylvania": "America/New_York",
        "Massachusetts": "America/New_York",
        "Virginia": "America/New_York",
        "North Carolina": "America/New_York",
        "South Carolina": "America/New_York",

        "Colorado": "America/Denver",
        "Utah": "America/Denver",
        "Wyoming": "America/Denver",
        "Montana": "America/Denver",

        "Hawaii": "Pacific/Honolulu",
        "Alaska": "America/Anchorage"
    };

    const container = document.getElementById('statesTimeContainer');

    // Create cards
    Object.entries(statesTimeZones).forEach(([state, tz]) => {
        const box = document.createElement("div");
        box.className = "stateBox p-4 border rounded-lg shadow bg-white transition";
        box.setAttribute("data-state", state);

        box.innerHTML = `
            <h3 class="font-semibold text-lg">${state}</h3>
            <p id="time-${state.replace(/ /g,'_')}" class="text-xl font-bold mt-1"></p>
        `;
        container.appendChild(box);
    });

    // Update times and hide PM states
    function updateStateTimes() {
        Object.entries(statesTimeZones).forEach(([state, tz]) => {
            const id = `time-${state.replace(/ /g,'_')}`;
            const timeElement = document.getElementById(id);
            const stateBox = document.querySelector(`[data-state="${state}"]`);

            const now = new Date().toLocaleString("en-US", { 
                timeZone: tz,
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
                hour12: true 
            });

            timeElement.textContent = now;

            // Detect AM or PM
            const period = now.slice(-2); // "AM" or "PM"

            if (period === "PM") {
                stateBox.style.display = "none";
            } else {
                stateBox.style.display = "block";
            }
        });
    }

    setInterval(updateStateTimes, 1000);
    updateStateTimes();
</script>


</div>