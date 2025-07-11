<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        :root {
            --primary-bg: #ffffff;
            --primary-text: #212529;
            --nav-bg: #343a40;
            --nav-text: #ffffff;
            --card-bg: #ffffff;
            --card-shadow: rgba(0,0,0,0.1);
        }

        [data-theme="dark"] {
            --primary-bg: #1e1e2f;
            --primary-text: #f1f1f1;
            --nav-bg: #121212;
            --nav-text: #f1f1f1;
            --card-bg: #2b2b3d;
            --card-shadow: rgba(255,255,255,0.1);
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--primary-bg);
            color: var(--primary-text);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        nav {
            background-color: var(--nav-bg);
            padding: 1em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        nav li {
            margin-right: 20px;
        }
        nav a {
            color: var(--nav-text);
            text-decoration: none;
            font-weight: bold;
        }
        .theme-toggle {
            cursor: pointer;
            color: var(--nav-text);
            font-size: 0.9rem;
            padding: 0.5em;
            background: none;
            border: 1px solid var(--nav-text);
            border-radius: 4px;
        }
        main {
            padding: 2em;
        }
        h1, h2 {
            color: var(--primary-text);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2em;
            background-color: var(--card-bg);
            box-shadow: 0 2px 4px var(--card-shadow);
        }
        th, td {
            padding: 12px;
            border: 1px solid #dee2e6;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            color: #343a40;
        }
        .form-group {
            margin-bottom: 1em;
        }
        input, select, button {
            padding: 0.5em;
            margin-right: 0.5em;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        button.danger {
            background-color: #dc3545;
        }
        .card {
            background-color: var(--card-bg);
            padding: 1em;
            border-radius: 5px;
            margin-bottom: 1em;
            box-shadow: 0 2px 4px var(--card-shadow);
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1em;
        }
        #userTable tbody tr.hidden {
            display: none;
        }
    </style>
</head>
<body>
<nav>
    <ul>
        <li><a href="#users">Users</a></li>
        <li><a href="#dashboard">Dashboard</a></li>
        <li><a href="#insights">Insights</a></li>
    </ul>
    <button class="theme-toggle" id="themeToggle">Toggle Theme</button>
</nav>
<main>
    <section id="users">
        <h1>User Directory</h1>
        <form id="searchForm">
            <div class="form-group">
                <input type="text" id="searchName" placeholder="Search by name">
                <input type="email" id="searchEmail" placeholder="Search by email">
                <select id="searchStatus">
                    <option value="">Status</option>
                    <option value="Active">Active</option>
                    <option value="Suspended">Suspended</option>
                    <option value="Disabled">Disabled</option>
                </select>
                <button type="submit">Search</button>
            </div>
        </form>
        <table id="userTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>John Doe</td>
                    <td>john@example.com</td>
                    <td>Active</td>
                    <td>Admin</td>
                    <td>
                        <button>Edit</button>
                        <button class="danger">Disable</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jane Smith</td>
                    <td>jane@example.com</td>
                    <td>Suspended</td>
                    <td>User</td>
                    <td>
                        <button>Edit</button>
                        <button class="danger">Enable</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <section id="dashboard">
    <h2>Dashboard</h2>
    <p>Overview stats (sales, orders, revenue, inventory levels)</p>
    <div class="dashboard-grid">
        <div class="card">
            <h3>Total Users</h3>
            <p id="totalUsers">2</p>
        </div>
        <div class="card">
            <h3>Active Orders</h3>
            <p>58</p>
        </div>
        <div class="card">
            <h3>Monthly Revenue</h3>
            <p>$12,000</p>
        </div>
        <div class="card">
            <h3>Inventory Warnings</h3>
            <p>12 items low in stock</p>
        </div>
    </div>
</section>
</main>
<script>
    const form = document.getElementById('searchForm');
    const nameInput = document.getElementById('searchName');
    const emailInput = document.getElementById('searchEmail');
    const statusSelect = document.getElementById('searchStatus');
    const tableRows = document.querySelectorAll('#userTable tbody tr');
    const toggleBtn = document.getElementById('themeToggle');
    const htmlEl = document.documentElement;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const name = nameInput.value.toLowerCase();
        const email = emailInput.value.toLowerCase();
        const status = statusSelect.value;

        tableRows.forEach(row => {
            const rowName = row.children[1].textContent.toLowerCase();
            const rowEmail = row.children[2].textContent.toLowerCase();
            const rowStatus = row.children[3].textContent;

            const nameMatch = !name || rowName.includes(name);
            const emailMatch = !email || rowEmail.includes(email);
            const statusMatch = !status || rowStatus === status;

            if (nameMatch && emailMatch && statusMatch) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    });

    toggleBtn.addEventListener('click', () => {
        const isDark = htmlEl.getAttribute('data-theme') === 'dark';
        htmlEl.setAttribute('data-theme', isDark ? 'light' : 'dark');
    });
</script>
</body>
</html>