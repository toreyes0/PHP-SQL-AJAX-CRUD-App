<!DOCTYPE html>
<html>
    <head>
        <title>PHP SQL AJAX CRUD</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="script.js" async></script>
    </head>
    <body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div id="head" class="mt-5 mb-3">
                            <h2 class="pull-left">Products</h2>
                            <button
                                type="button"
                                class="btn btn-success"
                                data-bs-toggle="modal"
                                data-bs-target="#add"
                            >
                                Add Product
                            </button>
                        </div>
                        <?php
                        require_once "backend/database.php";
                        
                        // load table
                        $sql = "SELECT * FROM products";
                        $result = $conn -> query($sql);
                        if ($result) {
                            if ($result -> num_rows > 0) {
                                echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>#</th>";
                                            echo "<th>Product Name</th>";
                                            echo "<th>Unit</th>";
                                            echo "<th>Price</th>";
                                            echo "<th>Expiry Date</th>";
                                            echo "<th>Available Inventory</th>";
                                            echo "<th>Available Inventory Cost</th>";
                                            echo "<th></th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while ($row = $result -> fetch_array(MYSQLI_ASSOC)) {
                                        echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['product_name'] . "</td>";
                                            echo "<td>" . $row['unit'] . "</td>";
                                            echo "<td>" . $row['price'] . "</td>";
                                            echo "<td>" . $row['date_of_expiry'] . "</td>";
                                            echo "<td>" . $row['available_inventory'] . "</td>";
                                            echo "<td>" . $row['available_inventory_cost'] . "</td>";
                                            echo "<td>";
                                                echo '<button type="button" class="btn btn-info view-menu-btn" data-bs-toggle="modal" data-bs-target="#view" value="' . $row['id'] . '">View</button>';
                                                echo '<button type="button" class="btn btn-warning update-menu-btn" data-bs-toggle="modal" data-bs-target="#update" value="' . $row['id'] . '">Update</button>';
                                                echo '<button type="button" class="btn btn-danger delete-menu-btn" data-bs-toggle="modal" data-bs-target="#delete" value="' . $row['id'] . '">Delete</button>';
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                echo "</table>";
                                
                                $result -> free_result();
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                        $conn -> close();
                        ?>
                    </div>
                </div>
            </div>
        </div>


        <!-- create -->
        <div id="add" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="add-form">
                            <div class="row g-3">
                                <label for="prod-name">Product Name</label>
                                <input type="text" class="form-control" id="prod-name" placeholder="Coke, Coke 8oz" name="prod-name" required>
                                <div class="invalid-feedback">
                                    Please enter valid characters (A-Z, 0-9).
                                </div>
                            </div>
                            <div class="row g-3">
                                <label for="unit">Unit</label>
                                <input type="text" class="form-control" id="unit" placeholder="bottle, case x 12" name="unit" required>
                                <div class="invalid-feedback">
                                    Please enter valid characters (A-Z, 0-9).
                                </div>
                            </div>
                            <div class="row g-3">
                                <label for="price">Price</label>
                                <input type="text" class="form-control" id="price" placeholder="60" name="price" required>
                                <div class="invalid-feedback">
                                    Please enter a number.
                                </div>
                            </div>
                            <div class="row g-3">
                                <label for="exp-date">Expiry Date</label>
                                <input type="text" class="form-control" id="exp-date" placeholder="YYYY-MM-DD" name="exp-date" required>
                                <div class="invalid-feedback">
                                    Please enter a valid date (YYYY-MM-DD).
                                </div>
                            </div>
                            <div class="row g-3">
                                <label for="available-inv">Available Inventory</label>
                                <input type="text" class="form-control" id="available-inv" placeholder="20" name="available-inv" required>
                                <div class="invalid-feedback">
                                    Please enter a number.
                                </div>
                            </div>
                            <div class="row g-3">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                                <div class="invalid-feedback">
                                    Please upload an image.
                                </div>
                            </div>
                            <button id="add-btn" type="button" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- read -->
        <div id="view" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Product Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="image-display"></div>
                        <hr>
                        <div id="details-container">
                            <div class="detail">
                                <strong>Product Name</strong><br>
                                <span id="prod-name-detail"></span>
                            </div>
                            <div class="detail">
                                <strong>Unit</strong><br>
                                <span id="unit-detail"></span>
                            </div>
                            <div class="detail">
                                <strong>Price</strong><br>
                                <span id="price-detail"></span>
                            </div>
                            <div class="detail">
                                <strong>Expiry Date</strong><br>
                                <span id="exp-date-detail"></span>
                            </div>
                            <div class="detail">
                                <strong>Available Inventory</strong><br>
                                <span id="avail-inv-detail"></span>
                            </div>
                            <div class="detail">
                                <strong>Available Inventory Cost</strong><br>
                                <span id="avail-inv-cost-detail"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- update -->
        <div id="update" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="update-form">
                            <div class="row g-3">
                                <label for="prod-name-update">Product Name</label>
                                <input type="text" class="form-control" id="prod-name-update" placeholder="Coke, Coke 8oz" name="prod-name-update" required>
                                <div class="invalid-feedback">
                                    Please enter valid characters (A-Z, 0-9).
                                </div>
                            </div>
                            <div class="row g-3">
                                <label for="unit-update">Unit</label>
                                <input type="text" class="form-control" id="unit-update" placeholder="bottle, case x 12" name="unit-update" required>
                                <div class="invalid-feedback">
                                    Please enter valid characters (A-Z, 0-9).
                                </div>
                            </div>
                            <div class="row g-3">
                                <label for="price-update">Price</label>
                                <input type="text" class="form-control" id="price-update" placeholder="60" name="price-update" required>
                                <div class="invalid-feedback">
                                    Please enter a number.
                                </div>
                            </div>
                            <div class="row g-3">
                                <label for="exp-date-update">Expiry Date</label>
                                <input type="text" class="form-control" id="exp-date-update" placeholder="YYYY-MM-DD" name="exp-date-update" required>
                                <div class="invalid-feedback">
                                    Please enter a valid date (YYYY-MM-DD).
                                </div>
                            </div>
                            <div class="row g-3">
                                <label for="avail-inv-update">Available Inventory</label>
                                <input type="text" class="form-control" id="avail-inv-update" placeholder="20" name="avail-inv-update" required>
                                <div class="invalid-feedback">
                                    Please enter a number.
                                </div>
                            </div>
                            <div class="row g-3">
                                <label for="image-update">Image</label>
                                <input type="file" class="form-control" id="image-update" name="image-update" accept="image/*" required>
                                <div class="invalid-feedback">
                                    Please upload an image.
                                </div>
                            </div>
                            <button id="update-btn" type="button" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete-->
        <div id="delete" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this record?
                    </div>
                    <div class="modal-footer">
                        <button id="delete-btn" type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
