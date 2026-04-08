<?php
// Include database connection  
include 'database.php';

$err = "";
$success = "";
$equipment = null;

//add new equipment
if (isset($_POST['add_equipment'])) {
    $tool_id = trim($_POST['tool_id']);
    $tool_name = trim($_POST['tool_name']);
    $category = trim($_POST['category']);
    $condition_status = trim($_POST['condition_status']);
    $quantity = trim($_POST['quantity']);
    $image = $_FILES['tool_image']['name'];
    $target = "images/" . basename($image);

    if(empty($tool_id) || empty($tool_name) || empty($category) || empty($condition_status) || empty($quantity) || empty($image)) {
        $err = "All fields are required.";
    } elseif (!is_numeric($tool_id) || !is_numeric($quantity)) {
        $err = "Tool ID and Quantity must be numeric.";
    } 

    if (!$err){
        $stmt = $conn->prepare("INSERT INTO tools (tool_id, tool_name, category, condition_status, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssis", $tool_id, $tool_name, $category, $condition_status, $quantity, $image);
        if ($stmt->execute()) {
            $success = "Equipment added successfully.";
            move_uploaded_file($_FILES['tool_image']['tmp_name'], $target);  
            header("Location: adequipment.php");
            exit();   
        } else {
            $err = "Error adding equipment: " . $stmt->error;
        }     
      $stmt->close();
    }
}

//Delete equipment
if (isset($_GET['delete'])) {
    $tool_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM tools WHERE tool_id = ?");
    $stmt->bind_param("i", $tool_id);
    if ($stmt->execute()) {
        header("Location: adequipment.php");
        exit();
    } else {
        echo "Error deleting equipment: " . $stmt->error;
    }
    $stmt->close();
}

//edit equipment
if (isset($_POST['edit_equipment'])) {
    $tool_id = intval($_POST['tool_id']);
    $tool_name = trim($_POST['tool_name']);
    $category = trim($_POST['category']);
    $condition_status = trim($_POST['condition_status']);
    $quantity = intval($_POST['quantity']);
    $current_image = $_FILES['tool_image']['name'];
    $target = "images/" . basename($current_image);

    $image = !empty($current_image) ? $current_image : $_POST['existing_image'];

    if(empty($tool_name) || empty($category) || empty($condition_status) || empty($quantity)) {
        $err = "All fields except image are required.";
    } elseif (!is_numeric($quantity)) {
        $err = "Quantity must be numeric.";
    }

    if (!$err){
        $stmt = $conn->prepare("UPDATE tools SET tool_name=?, category=?, condition_status=?, quantity=?, image=? WHERE tool_id=?");
        $stmt->bind_param("sssisi", $tool_name, $category, $condition_status, $quantity, $image, $tool_id);

        if ($stmt->execute()) {
            if (!empty($current_image)) {
                move_uploaded_file($_FILES['tool_image']['tmp_name'], $target);
            }
            header("Location: adequipment.php");
            exit();   
        } else {
            echo "Error updating equipment: " . $stmt->error;
        }     
      $stmt->close();
    }
}

//fetch equipment data for editing
if (isset($_GET['edit'])) {
    $tool_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM tools WHERE tool_id = ?");
    $stmt->bind_param("i", $tool_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $equipment = $result->fetch_assoc();
    } else {
        echo "Equipment not found.";
        exit();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/adequipment.css">
</head>
<body>
    <div class="adequipment_container">
        <h1>Equipment Management</h1>
        <p>This is where you can manage your equipment.</p>

        <?php if ($err): ?>
            <div class="error"><?php echo $err; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>  
        <?php endif; ?>


        <!--add tool button-->
        <button class="add_tool_btn" onclick="openModal('add')">Add New Equipment</button>

        <!--add/edit equipment modal-->
        <div id="equipmentModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2 id="modalTitle">Add New Equipment</h2>
                <div id ="modalformwrapper"></div>
            </div>
        </div>
        <!--table for tools-->
        <table class="equipment_table">
            <tr>
                <th>tool_ID</th>
                <th>tool_image</th>
                <th>tool_Name</th>
                <th>category</th>
                <th>status</th>
                <th>quantity</th>
                <th>Action</th>
            </tr>
            <?php
            // Fetch equipment data from the database
            $sql = "SELECT * FROM tools";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['tool_id']) . "</td>";
                    echo "<td><img src='images/" . htmlspecialchars($row['image'], ENT_QUOTES) . "' alt='" . htmlspecialchars($row['tool_name'], ENT_QUOTES) . "' width='50'></td>";
                    echo "<td>" . htmlspecialchars($row['tool_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['condition_status']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                    echo "<td><button class='edit_btn' onclick=\"openModal('edit', this)\" 
                        data-id='" . htmlspecialchars($row['tool_id'], ENT_QUOTES) . "' 
                        data-name='" . htmlspecialchars($row['tool_name'], ENT_QUOTES) . "' 
                        data-category='" . htmlspecialchars($row['category'], ENT_QUOTES) . "' 
                        data-status='" . htmlspecialchars($row['condition_status'], ENT_QUOTES) . "' 
                        data-quantity='" . htmlspecialchars($row['quantity'], ENT_QUOTES) . "' 
                        data-image='" . htmlspecialchars($row['image'], ENT_QUOTES) . "'>Edit</button> 
                        <a href='adequipment.php?delete=" . urlencode($row['tool_id']) . "' class='delete_btn' onclick=\"return confirm('Are you sure you want to delete this equipment?')\">Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No equipment found.</td></tr>";
            }
            ?>
        </table>
    </div>
    <script>
        function openModal(mode, el = null) {
            const modal = document.getElementById('equipmentModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalFormWrapper = document.getElementById('modalformwrapper');

            if (mode === 'add') {
                modalTitle.textContent = 'Add New Equipment';
                modalFormWrapper.innerHTML = `
                    <form method="POST" enctype="multipart/form-data">
                        <input type="number" name="tool_id" placeholder="Tool ID" required>
                        <input type="text" name="tool_name" placeholder="Tool Name" required>
                        <input type="text" name="category" placeholder="Category" required>
                        <input type="text" name="condition_status" placeholder="Condition Status" required>
                        <input type="number" name="quantity" placeholder="Quantity" required>
                        <input type="file" name="tool_image" accept="image/*" required>
                        <button type="submit" name="add_equipment">Add Equipment</button>
                    </form>
                `;
            } else if (mode === 'edit') {
                // Fetch existing data for the selected equipment
                const toolId = el.getAttribute('data-id');
                const toolName = el.getAttribute('data-name');
                const category = el.getAttribute('data-category');
                const status = el.getAttribute('data-status');
                const quantity = el.getAttribute('data-quantity');
                const image = el.getAttribute('data-image');

                modalTitle.textContent = 'Edit Equipment';
                modalFormWrapper.innerHTML = `
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="tool_id" value="${toolId}">
                        <input type="hidden" name="existing_image" value="${image}">
                        <input type="text" name="tool_name" placeholder="Tool Name" value="${toolName}" required>
                        <input type="text" name="category" placeholder="Category" value="${category}" required>
                        <input type="text" name="condition_status" placeholder="Condition Status" value="${status}" required>
                        <input type="number" name="quantity" placeholder="Quantity" value="${quantity}" required>
                        <input type="file" name="tool_image" accept="image/*">
                        <button type="submit" name="edit_equipment">Update Equipment</button>
                    </form>
                `;
            }

            modal.classList.add('active');
        }
        function closeModal() {
            const modal = document.getElementById('equipmentModal');
            modal.classList.remove('active');
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('equipmentModal');
            if (event.target === modal) {
                modal.classList.remove('active');
            }
        };
    </script>
</body>
</html>
