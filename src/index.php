<?php
require "db/dbconnection.class.php";
$dbconnect = new dbconnection();

$sql = "SELECT * FROM items 
        LEFT JOIN categories ON categories.id = category_id 
        LEFT JOIN periods ON periods.id = period_id 
        ORDER BY items.id DESC";

$query = $dbconnect->prepare($sql);
$query->execute();
$transactions = $query->fetchAll(PDO::FETCH_ASSOC);

$sql_cat = "SELECT * FROM categories ORDER BY cat_name";
$query_cat = $dbconnect->prepare($sql_cat);
$query_cat->execute();
$categories = $query_cat->fetchAll(PDO::FETCH_ASSOC);

$sql_per = "SELECT * FROM periods ORDER BY period_name";
$query_per = $dbconnect->prepare($sql_per);
$query_per->execute();
$periods = $query_per->fetchAll(PDO::FETCH_ASSOC);

$total_income = 0;
$total_expenses = 0;

foreach ($transactions as $transaction) {
    if ($transaction['type'] == 1) {
        $total_income += $transaction['amount'];
    } else {
        $total_expenses += $transaction['amount'];
    }
}

$net_balance = $total_income - $total_expenses;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>💸 MoneyHub</title>
</head>

<body>
<!-- Header -->
    <div class="header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <h1>💸 MoneyHub</h1>
                <div class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>
                    <?= date('M d, Y') ?>
                </div>
            </div>
        </div>
    </div>

<!-- Navbar -->
    <nav>
        <div class="container">
            <ul class="nav nav-tabs" id="mainTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button">
                        <i class="bi bi-house me-2"></i>Dashboard
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="add-tab" data-bs-toggle="tab" data-bs-target="#add" type="button">
                        <i class="bi bi-plus-circle me-2"></i>Add Transaction
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="transactions-tab" data-bs-toggle="tab" data-bs-target="#transactions" type="button">
                        <i class="bi bi-list-ul me-2"></i>All Transactions
                    </button>
                </li>
            </ul>
        </div>
    </nav>

<!-- Main Content -->
    <div class="container py-4">
        <div class="tab-content" id="mainTabContent">
        <!-- Tabs -->
            <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon income">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                        <div class="stat-value positive">€<?= number_format($total_income, 2) ?></div>
                        <div class="stat-label">Total Income</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon expense">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                        <div class="stat-value negative">€<?= number_format($total_expenses, 2) ?></div>
                        <div class="stat-label">Total Expenses</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon balance">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="stat-value <?= $net_balance >= 0 ? 'positive' : 'negative' ?>">
                            €<?= number_format($net_balance, 2) ?>
                        </div>
                        <div class="stat-label">Net Balance</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon balance">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <div class="stat-value"><?= count($transactions) ?></div>
                        <div class="stat-label">Total Transactions</div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Transactions</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($transactions)): ?>
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h6>No transactions yet</h6>
                                <p>Add your first transaction to get started</p>
                                <button class="btn btn-primary" data-bs-toggle="tab" data-bs-target="#add">
                                    <i class="bi bi-plus-circle me-2"></i>Add Transaction
                                </button>
                            </div>
                        <?php else: ?>
                            <?php foreach (array_slice($transactions, 0, 5) as $transaction): ?>
                                <div class="transaction-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($transaction['item_name']) ?></h6>
                                            <div class="transaction-meta">
                                                <span class="me-3">
                                                    <i class="bi bi-tag me-1"></i>
                                                    <?= htmlspecialchars($transaction['cat_name'] ?? 'No Category') ?>
                                                </span>
                                                <span>
                                                    <i class="bi bi-calendar me-1"></i>
                                                    <?= htmlspecialchars($transaction['period_name'] ?? 'No Period') ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="transaction-amount <?= $transaction['type'] == 1 ? 'income' : 'expense' ?>">
                                                <?= $transaction['type'] == 1 ? '+' : '-' ?>€<?= number_format($transaction['amount'], 2) ?>
                                            </div>
                                            <div class="transaction-meta">
                                                <?= $transaction['type'] == 1 ? 'Income' : 'Expense' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($transactions) > 5): ?>
                                <div class="p-3 text-center border-top">
                                    <button class="btn btn-outline-secondary" data-bs-toggle="tab" data-bs-target="#transactions">
                                        View All Transactions
                                    </button>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="add" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="form-section">
                            <h4 class="mb-4">Add New Transaction</h4>
                            
                            <form method="post" action="insert.php" id="transaction-form">
                                <div class="mb-3">
                                    <label for="itemName" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="itemName" name="itemName" 
                                           placeholder="Enter transaction description" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" class="form-control" id="amount" name="amount" 
                                               step="0.01" placeholder="0.00" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Choose transaction type</option>
                                        <option value="0">Expense</option>
                                        <option value="1">Income</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Choose a category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>">
                                                <?= htmlspecialchars($category['cat_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="period" class="form-label">Period</label>
                                    <select class="form-select" id="period" name="period" required>
                                        <option value="">Choose a period</option>
                                        <?php foreach ($periods as $period): ?>
                                            <option value="<?= $period['id'] ?>">
                                                <?= htmlspecialchars($period['period_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="bi bi-check-circle me-2"></i>Save Transaction
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="transactions" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">All Transactions</h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="filterType" onchange="filterTransactions()">
                                <option value="all">All Types</option>
                                <option value="1">Income Only</option>
                                <option value="0">Expenses Only</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($transactions)): ?>
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h6>No transactions found</h6>
                                <p>Start by adding your first transaction</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="transactionsTable">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Category</th>
                                            <th>Period</th>
                                            <th>Type</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($transactions as $transaction): ?>
                                            <tr data-type="<?= $transaction['type'] ?>">
                                                <td>
                                                    <strong><?= htmlspecialchars($transaction['item_name']) ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <?= htmlspecialchars($transaction['cat_name'] ?? 'No Category') ?>
                                                    </span>
                                                </td>
                                                <td class="text-muted">
                                                    <?= htmlspecialchars($transaction['period_name'] ?? 'No Period') ?>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $transaction['type'] == 1 ? 'bg-success' : 'bg-danger' ?>">
                                                        <?= $transaction['type'] == 1 ? 'Income' : 'Expense' ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <strong class="<?= $transaction['type'] == 1 ? 'text-success' : 'text-danger' ?>">
                                                        <?= $transaction['type'] == 1 ? '+' : '-' ?>€<?= number_format($transaction['amount'], 2) ?>
                                                    </strong>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>