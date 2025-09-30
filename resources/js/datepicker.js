import $ from "jquery";

document.addEventListener("DOMContentLoaded", function () {
    // Find all date inputs
    const dateInputs = document.querySelectorAll('input[type="date"]');

    // Convert each date input to use the datepicker
    dateInputs.forEach(function (input) {
        try {
            // Get the current value
            const currentValue = input.value;

            // Create a wrapper div for the datepicker
            const wrapper = document.createElement("div");
            wrapper.className = "input-group date";
            wrapper.id =
                "datetimepicker-" + Math.random().toString(36).substr(2, 9);
            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(input);

            // Add the calendar icon
            const appendDiv = document.createElement("div");
            appendDiv.className = "input-group-append";
            appendDiv.innerHTML =
                '<span class="input-group-text"><i class="fas fa-calendar"></i></span>';
            wrapper.appendChild(appendDiv);

            // Change input type from date to text
            input.type = "text";
            input.className += " form-control datetimepicker-input";
            input.setAttribute("data-toggle", "datetimepicker");
            input.setAttribute("data-target", "#" + wrapper.id);

            // Set the value back
            if (currentValue) {
                input.value = currentValue;
            }

            // Initialize Tempus Dominus datepicker
            const $picker = $(`#${wrapper.id}`);
            $picker.datetimepicker({
                format: "YYYY-MM-DD",
                useCurrent: false,
                icons: {
                    time: "fas fa-clock",
                    date: "fas fa-calendar",
                    up: "fas fa-arrow-up",
                    down: "fas fa-arrow-down",
                    previous: "fas fa-chevron-left",
                    next: "fas fa-chevron-right",
                    today: "fas fa-calendar-check",
                    clear: "fas fa-trash",
                    close: "fas fa-times",
                },
            });

            // Show calendar when input is clicked
            input.addEventListener("click", function () {
                try {
                    $picker.datetimepicker("show");
                } catch (error) {
                    console.error("Error showing datepicker:", error);
                }
            });
        } catch (error) {
            console.error("Error initializing datepicker:", error);
            // Restore original input if initialization fails
            input.type = "date";
        }
    });
});
