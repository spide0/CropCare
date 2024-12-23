<?php
include("./dbconnection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post-Harvest Transport System: Editable Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }
        h1, h2, h3 {
            text-align: center;
            color: #2a2a2a;
        }
        section {
            margin: 30px auto;
            width: 90%;
        }
        .section-title {
            margin-top: 20px;
            color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        .button-container {
            text-align: center;
            margin: 20px 0;
        }
        .button-container button {
            margin: 5px;
            padding: 10px 20px;
            border: none;
            background-color: #0056b3;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .button-container button:hover {
            background-color: #003d80;
        }
        .chart-container {
            margin: 40px auto;
            width: 80%;
            height: 400px;
        }
    </style>
</head>
<body>
    <h1>Post-Harvest Transport System: Editable Data</h1>

    <section>
        <h3 class="section-title">Yearly Data: Transport Loss and Profit Analysis</h3>
        <table id="data-table">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Transport Loss (tons)</th>
                    <th>Transport Loss ($ million)</th>
                    <th>Profit ($ million)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="data-table-body">
                <!-- Data dynamically populated -->
            </tbody>
        </table>
        <div class="button-container">
            <button id="add-row">Add Row</button>
            <button id="submit-data">Submit Data</button>
        </div>
    </section>

    <section>
        <h3 class="section-title">Profit and Loss Analysis Chart</h3>
        <div class="chart-container">
            <canvas id="profitLossChart"></canvas>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let data = JSON.parse(localStorage.getItem('transportData')) || [
            { year: 1999, lossTons: 30, lossDollars: 1.5, profit: 2 },
            { year: 2000, lossTons: 28, lossDollars: 1.4, profit: 2.3 },
        ];

        const tableBody = document.getElementById('data-table-body');

        // Render table rows
        function renderTable() {
            tableBody.innerHTML = '';
            data.forEach((entry, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td contenteditable="true" class="year">${entry.year}</td>
                    <td contenteditable="true" class="loss-tons">${entry.lossTons}</td>
                    <td contenteditable="true" class="loss-dollars">${entry.lossDollars}</td>
                    <td contenteditable="true" class="profit">${entry.profit}</td>
                    <td>
                        <button onclick="deleteRow(${index})">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Add a new row
        document.getElementById('add-row').addEventListener('click', () => {
            data.push({ year: '', lossTons: '', lossDollars: '', profit: '' });
            renderTable();
            updateChart();
        });

        // Delete a row
        function deleteRow(index) {
            data.splice(index, 1);
            renderTable();
            updateChart();
        }

        // Save data to localStorage
        function saveData() {
            const rows = tableBody.querySelectorAll('tr');
            data = Array.from(rows).map(row => ({
                year: parseInt(row.querySelector('.year').innerText) || '',
                lossTons: parseFloat(row.querySelector('.loss-tons').innerText) || '',
                lossDollars: parseFloat(row.querySelector('.loss-dollars').innerText) || '',
                profit: parseFloat(row.querySelector('.profit').innerText) || ''
            }));
            localStorage.setItem('transportData', JSON.stringify(data));
        }

        // Submit data to the server
        document.getElementById('submit-data').addEventListener('click', () => {
    saveData(); // Save the current state of the table

    fetch('submit_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data), // `data` is the array of objects
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json(); // Parse JSON response
    })
    .then(result => {
        if (result.status === "success") {
            alert("Data submitted successfully!");
        } else {
            alert("Error: " + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the data.');
    });
});


        // Update chart
        const ctx = document.getElementById('profitLossChart').getContext('2d');
        let chart;

        function updateChart() {
            const labels = data.map(entry => entry.year);
            const lossDollars = data.map(entry => entry.lossDollars);
            const profit = data.map(entry => entry.profit);

            if (chart) chart.destroy();

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Transport Loss ($ million)',
                            data: lossDollars,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                        },
                        {
                            label: 'Profit ($ million)',
                            data: profit,
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Profit and Loss Analysis',
                        },
                    },
                },
            });
        }

        // Initialize table and chart
        renderTable();
        updateChart();

        tableBody.addEventListener('input', () => {
            saveData();
            updateChart();
        });
    </script>
</body>
</html>
