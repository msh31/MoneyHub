<?php
require "db/dbconnection.class.php";
$dbconnect = new dbconnection();
$sql = "SELECT * FROM categories";
$query = $dbconnect -> prepare($sql);
$query -> execute() ;
$recset = $query -> fetchAll(PDO::FETCH_ASSOC);
$sql1 = "SELECT * FROM periods";
$query1 = $dbconnect -> prepare($sql1);
$query1 -> execute() ;
$recset1 = $query1 -> fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Huishoudboekje-invoer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <form method="post" action="insert.php">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Item</label>
                        <input type="text" class="form-control" id="itemName" name="itemName" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Bedrag</label>
                        <input type="text" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Uitgave of inkomst</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="" selected>Kies een optie</option>
                            <option value="0">Uitgave</option>
                            <option value="1">Inkomst</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Categorie</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="" selected>Kies een optie</option>
                            <?php
                            foreach ($recset as $row) {
                                echo "<option value='" . $row['id'] . "'>" . $row['cat_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="period" class="form-label">Periode</label>
                        <select class="form-select" id="period" name="period" required>
                            <option value="" selected>Kies een optie</option>
                            <?php
                            foreach ($recset1 as $row) {
                                echo "<option value='" . $row['id'] . "'>" . $row['period_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Bewaar</button>
                </form>
            </div>
            <div class="col"></div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>