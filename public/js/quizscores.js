
    document.addEventListener("DOMContentLoaded", () => {
        const scoresContainer = document.getElementById("quiz-scores-container");

        // Fetch quiz scores data
        fetch("../admin_get_total_scores.php")
            .then(response => response.json())
            .then(data => {
                // Create table for quiz scores
                const table = document.createElement("table");
                table.classList.add("quiz-scores-table");

                // Add table header
                table.innerHTML = `
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Username</th>
                            <th>Total Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data
                            .map(
                                (score, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${score.username}</td>
                                <td>${score.total_score}</td>
                            </tr>
                        `
                            )
                            .join("")}
                    </tbody>
                `;

                // Append table to container
                scoresContainer.appendChild(table);
            })
            .catch(error => {
                console.error("Error fetching quiz scores data:", error);
                scoresContainer.innerHTML = `<p class="error">Failed to load quiz scores data.</p>`;
            });
    });

