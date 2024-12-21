<?php
      include("./utils/dbconnection.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Storage Data Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #121212;
      color: #ffffff;
      margin: 0;
      padding: 0;
    }
    table {
      width: 80%;
      margin: 20px auto;
      border-collapse: collapse;
      background-color: #1e1e1e;
      color: white;
    }
    table, th, td {
      border: 1px solid #444;
    }
    th, td {
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #444;
      font-weight: bold;
      text-transform: uppercase;
    }
    .chart-container {
      width: 80%;
      margin: 20px auto;
    }
    .button-container {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin: 20px;
    }
    button {
      padding: 10px 15px;
      border: none;
      cursor: pointer;
      background-color: #333;
      color: white;
      border-radius: 5px;
    }
    button:hover {
      background-color: #555;
    }
    .light-mode {
      background-color: #f9f9f9;
      color: #121212;
    }
    .light-mode table {
      background-color: white;
      color: black;
    }
    .light-mode th {
      background-color: #ddd;
    }
    .light-mode button {
      background-color: #ddd;
      color: black;
    }
  </style>
</head>
<body>

<h1 style="text-align: center;">Storage Loss</h1>

<div class="button-container">
  <button onclick="addRow('table1')">Add Row (Table 1)</button>
  <button onclick="deleteRow('table1')">Delete Row (Table 1)</button>
</div>

<h2 style="text-align: center;">Table 1</h2>
<table id="table1"></table>

<div class="button-container">
  <button onclick="submitTable1Data()">Submit</button>
</div>

<div class="chart-container" id="chart1-container">
  <canvas id="chart1"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const defaultTable1Data = [
    ["Year", "Storage Loss (%)", "Storage Loss ($M)"],
    ["2020", "25", "11"],
    ["2021", "20", "20"],
    ["2022", "13", "30"],
    ["2023", "11", "24"],
    ["2024", "9", "13"]
  ];

  function saveToLocalStorage() {
    const table1Data = getTableData('table1');
    localStorage.setItem('table1Data', JSON.stringify(table1Data));
  }

  function loadFromLocalStorage() {
    const table1Data = JSON.parse(localStorage.getItem('table1Data')) || defaultTable1Data;
    return { table1Data };
  }

  function renderTable(tableId, data) {
    const table = document.getElementById(tableId);

    while (table.rows.length > 0) {
      table.deleteRow(0);
    }

    data.forEach((rowData, rowIndex) => {
      const newRow = table.insertRow();
      rowData.forEach((cellData, cellIndex) => {
        if (rowIndex === 0) {
          const newCell = document.createElement('th');
          newCell.textContent = cellData;
          newRow.appendChild(newCell);
        } else {
          const newCell = newRow.insertCell();
          newCell.contentEditable = true;
          newCell.textContent = cellData;
          newCell.addEventListener('input', () => {
            saveToLocalStorage();
            updateChart();
          });
        }
      });
    });

    saveToLocalStorage();
  }

  function getTableData(tableId) {
    const table = document.getElementById(tableId);
    const data = [];
    for (let i = 0; i < table.rows.length; i++) {
      const row = table.rows[i];
      const rowData = [];
      for (let j = 0; j < row.cells.length; j++) {
        rowData.push(row.cells[j].textContent);
      }
      data.push(rowData);
    }
    return data;
  }

  function updateChartFromTable(tableId, chart, labelsIndex, ...dataIndexes) {
    const table = document.getElementById(tableId);
    const rows = table.rows;
    const labels = [];
    const datasets = dataIndexes.map((index, idx) => ({
      label: rows[0].cells[index].textContent,
      data: [],
      borderColor: idx === 0 ? 'rgba(255, 99, 132, 1)' : 'rgba(54, 162, 235, 1)',
      backgroundColor: idx === 0 ? 'rgba(255, 99, 132, 0.5)' : 'rgba(54, 162, 235, 0.5)',
      borderWidth: 2,
      fill: false,
    }));

    for (let i = 1; i < rows.length; i++) {
      const cells = rows[i].cells;
      labels.push(cells[labelsIndex].textContent);
      datasets.forEach((dataset, idx) => {
        dataset.data.push(parseFloat(cells[dataIndexes[idx]].textContent) || 0);
      });
    }

    chart.data.labels = labels;
    chart.data.datasets = datasets;
    chart.update();
  }

  function addRow(tableId) {
    const table = document.getElementById(tableId);
    const newRow = table.insertRow();
    for (let i = 0; i < table.rows[0].cells.length; i++) {
      const newCell = newRow.insertCell();
      newCell.contentEditable = "true";
      newCell.textContent = '-';
      newCell.addEventListener('input', () => {
        saveToLocalStorage();
        updateChart();
      });
    }
    saveToLocalStorage();
    updateChart();
  }

  function deleteRow(tableId) {
    const table = document.getElementById(tableId);
    if (table.rows.length > 2) {
      table.deleteRow(-1);
    }
    saveToLocalStorage();
    updateChart();
  }

  const ctx1 = document.getElementById('chart1').getContext('2d');
  const chart1 = new Chart(ctx1, {
    type: 'line',
    data: {
      labels: [],
      datasets: []
    },
    options: {
      responsive: true,
    }
  });

  function updateChart() {
    updateChartFromTable('table1', chart1, 0, 1, 2);
  }

  function submitTable1Data() {
    const tableData = getTableData('table1');
    tableData.shift(); // Remove the header row

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'submit.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify(tableData));
    xhr.onload = function () {
      if (xhr.status === 200) {
        alert('Data submitted successfully!');
      } else {
        alert('Failed to submit data.');
      }
    };
  }

  const { table1Data } = loadFromLocalStorage();
  renderTable('table1', table1Data);
  updateChart();
</script>
</body>
