
    document.addEventListener("DOMContentLoaded", () => {
        const progressContainer = document.getElementById("quiz-progress-container");

        // Fetch quiz progress data
        fetch("../admin_get_quiz_progress.php")
            .then(response => response.json())
            .then(data => {
                // Create table for quiz progress
                const table = document.createElement("table");
                table.classList.add("quiz-progress-table");

                // Add table header
                table.innerHTML = `
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Username</th>
                            <th>Quiz Level Completed</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data
                            .map(
                                (leader, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${leader.username}</td>
                                <td>${leader.quiz_level_completed}</td>
                                <td>${new Date(leader.updated_at).toLocaleString()}</td>
                            </tr>
                        `
                            )
                            .join("")}
                    </tbody>
                `;

                // Append table to container
                progressContainer.appendChild(table);
            })
            .catch(error => {
                console.error("Error fetching quiz progress data:", error);
                progressContainer.innerHTML = `<p class="error">Failed to load quiz progress data.</p>`;
            });
    });

