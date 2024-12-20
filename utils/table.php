<?php
      include("./utils/dbconnection.php");

?>


<h1>Harvest Loss Management</h1>




    <div class="controls">
        <button onclick="addRow()">Add Row</button>
        
    </div>


    

    <table id="harvestTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Stage</th>
            <th>Loss Type</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

    <?php

// Handle delete request
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteSql = "DELETE FROM harvestdata WHERE id = $deleteId";
    $conn->query($deleteSql);
    }
   
    $sql = "SELECT * FROM harvestdata";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["stage"] . "</td>
                    <td>" . $row["loss_type"] . "</td>
                    <td>" . $row["amount"] . "</td>
                    <td>
                        <a href='?delete_id=" . $row["id"] . "'>
                            <button>Delete</button>
                        </a>
                    </td>
                  </tr>";
        }
        // Refresh the page after deletion

    } else {
        echo "<tr><td colspan='5' style='text-align: center;'>No data available</td></tr>";
    }
    ?>
    
        <!-- Add more rows as needed -->
    </tbody>
</table>



    <script>
        function addRow() {
            const table = document.getElementById('harvestTable').getElementsByTagName('tbody')[0];
            const newRow = table.insertRow();

            // Add blank checkbox cell
        const selectCell = newRow.insertCell(0);
        selectCell.textContent = ""; // Leave the cell blank

            // Add editable cells
            const stageCell = newRow.insertCell(1);
            const lossTypeCell = newRow.insertCell(2);
            const amountCell = newRow.insertCell(3);

            stageCell.contentEditable = true;
            lossTypeCell.contentEditable = true;
            amountCell.contentEditable = true;

            // Add placeholder text
            stageCell.textContent = "Enter Stage";
            lossTypeCell.textContent = "Enter Loss Type";
            amountCell.textContent = "Enter Amount";


            // Add submit button cell
            const actionCell = newRow.insertCell(4);
            const submitButton = document.createElement('button');
            submitButton.textContent = "Submit";
            submitButton.onclick = function () {
    const rowData = {
        stage: stageCell.textContent,
        lossType: lossTypeCell.textContent,
        amount: parseFloat(amountCell.textContent) || 0
    };

    // Send data to the server
    fetch('insert_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `stage=${encodeURIComponent(rowData.stage)}&lossType=${encodeURIComponent(rowData.lossType)}&amount=${rowData.amount}`
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Log server response
        alert(data); // Show success or error message
    })
    .catch(error => {
        console.error("Error:", error);
        //alert("Failed to insert data!");
    });
    window.location.reload();
    
    
};

            actionCell.appendChild(submitButton);



        }

       
    </script>


