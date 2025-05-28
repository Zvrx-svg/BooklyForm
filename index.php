<!DOCTYPE html>
<html>
<head>
    <title>Bookstore Input Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #ffeef3;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #c94f7c;
            font-size: 2em;
            margin-bottom: 20px;
        }
        form {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #f3cdd8;
        }
        th {
            background-color: #fbd5e5;
            color: #922c55;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        input[type="text"], input[type="number"] {
            width: 95%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #e9a9bd;
        }
        .btn {
            padding: 10px 16px;
            margin-top: 10px;
            margin-right: 5px;
            background: #f58cb0;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #e26b96;
        }
        .btn.remove {
            background: #ff7a7a;
        }
        .btn.remove:hover {
            background: #d65a5a;
        }
        .summary-table {
            max-width: 900px;
            margin: 30px auto;
            background: #fff0f5;
            border-radius: 15px;
            padding: 20px;
        }
    </style>
    <script>
        function addRow() {
            const table = document.getElementById('bookTable');
            const newRow = table.insertRow();
            newRow.innerHTML = `
                <td><input type="text" name="title[]" required></td>
                <td><input type="number" name="price[]" step="0.01" min="0" required></td>
                <td><input type="number" name="qty[]" min="0" required></td>
                <td><button type="button" class="btn remove" onclick="removeRow(this)">Remove</button></td>
            `;
        }

        function removeRow(btn) {
            const row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }
    </script>
</head>
<body>
    <h2>📚 Bookstore Input Form</h2>
    <form method="post">
        <table id="bookTable">
            <tr>
                <th>Book Title</th>
                <th>Price (RM)</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <?php
            if (isset($_POST['title'])) {
                foreach ($_POST['title'] as $i => $t) {
                    $p = $_POST['price'][$i];
                    $q = $_POST['qty'][$i];
                    echo "<tr>
                        <td><input type='text' name='title[]' value='" . htmlspecialchars($t) . "' required></td>
                        <td><input type='number' name='price[]' value='" . htmlspecialchars($p) . "' step='0.01' min='0' required></td>
                        <td><input type='number' name='qty[]' value='" . htmlspecialchars($q) . "' min='0' required></td>
                        <td><button type='button' class='btn remove' onclick='removeRow(this)'>Remove</button></td>
                    </tr>";
                }
            } else {
                for ($i = 0; $i < 3; $i++) {
                    echo "<tr>
                        <td><input type='text' name='title[]' required></td>
                        <td><input type='number' name='price[]' step='0.01' min='0' required></td>
                        <td><input type='number' name='qty[]' min='0' required></td>
                        <td><button type='button' class='btn remove' onclick='removeRow(this)'>Remove</button></td>
                    </tr>";
                }
            }
            ?>
        </table>
        <button type="button" class="btn" onclick="addRow()">Add Row</button>
        <input type="submit" name="submit" class="btn" value="View Summary">
    </form>

    <?php
    if (isset($_POST['submit'])) {
        displaySummary($_POST['title'], $_POST['price'], $_POST['qty']);
    }

    function calculateSubtotal($price, $qty) {
        return $price * $qty;
    }

    function calculateTax($subtotal) {
        return $subtotal * 0.06;
    }

    function calculateTotal($subtotal, $tax) {
        return $subtotal + $tax;
    }

    function formatCurrency($amount) {
        return "RM " . number_format($amount, 2);
    }

    function displaySummary($titles, $prices, $qtys) {
        $grandSubtotal = 0;
        echo "<div class='summary-table'><h2 style='text-align:center;'>📝 Order Summary</h2><table>";
        echo "<tr><th>Book</th><th>Quantity</th><th>Unit Price</th><th>Subtotal</th></tr>";

        foreach ($titles as $i => $title) {
            $qty = $qtys[$i];
            $price = $prices[$i];
            if ($qty > 0) {
                $sub = calculateSubtotal($price, $qty);
                $grandSubtotal += $sub;
                echo "<tr>
                        <td>" . htmlspecialchars($title) . "</td>
                        <td>{$qty}</td>
                        <td>" . formatCurrency($price) . "</td>
                        <td>" . formatCurrency($sub) . "</td>
                      </tr>";
            }
        }

        $tax = calculateTax($grandSubtotal);
        $total = calculateTotal($grandSubtotal, $tax);

        echo "<tr><td colspan='3' align='right'><strong>Subtotal:</strong></td><td>" . formatCurrency($grandSubtotal) . "</td></tr>";
        echo "<tr><td colspan='3' align='right'><strong>Tax (6%):</strong></td><td>" . formatCurrency($tax) . "</td></tr>";
        echo "<tr><td colspan='3' align='right'><strong>Total:</strong></td><td>" . formatCurrency($total) . "</td></tr>";
        echo "</table></div>";
    }
    ?>
</body>
</html>
