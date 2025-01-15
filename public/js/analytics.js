
    document.addEventListener("DOMContentLoaded", () => {
        const analyticsContainer = document.getElementById("analytics-container");

        // Fetch analytics data
        fetch("../admin_total_analytics.php")
            .then(response => response.json())
            .then(data => {
                // Populate analytics section
                analyticsContainer.innerHTML = `
                    <div class="analytics-card">
                        <h4>Total Users</h4>
                        <p>${data.total_users}</p>
                    </div>
                    <div class="analytics-card">
                        <h4>Total Learners</h4>
                        <p>${data.total_learners}</p>
                    </div>
                    <div class="analytics-card">
                        <h4>Total Tutors</h4>
                        <p>${data.total_tutors}</p>
                    </div>
                    <div class="analytics-card">
                        <h4>Total Bookings</h4>
                        <p>${data.total_bookings}</p>
                    </div>
                `;
            })
            .catch(error => {
                console.error("Error fetching analytics data:", error);
                analyticsContainer.innerHTML = `<p class="error">Failed to load analytics data.</p>`;
            });
    });

