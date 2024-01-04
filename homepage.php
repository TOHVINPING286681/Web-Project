<?php
include('dbconnect.php');

$itemsPerPage = 12;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $itemsPerPage;

// Fetch item name, price, category, and image path from the database
$stmt = $pdo->prepare("SELECT item_name, item_id, item_price, item_category, image1_path FROM tbl_items LIMIT :offset, :itemsPerPage");
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalItemsStmt = $pdo->query("SELECT COUNT(*) FROM tbl_items");
$totalItems = $totalItemsStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TradeCycle - Homepage</title>
    <link rel="stylesheet" href="style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  </head>
  <body>
    <header>
      <div class="logo-container">
        <img src="icons/logo.svg" height="100" width="100" />
        <h1>TradeCycle</h1>
      </div>

      <div class="username-icon-container">
        <div class="dropdown">
          <button class="dropbtn">
            <p class="username1"></p>
            <i class="fas fa-user"></i>
          </button>
          <div class="dropdown-content">
            <a href="user_profile.php">Profile</a>
            <a href="logout.php">Logout</a>
          </div>
        </div>
        <div class="cart-icons">
          <a href="add_cart.html">
            <i class="fas fa-shopping-cart"></i>
          </a>
        </div>
      </div>
    </header>
    <main class="home-main">
      <div class="leftContainer">
        <div class="filter">
          <h2 id="filter-heading">Filter</h2>

          <div class="categories">
            <ul class="filter-categories">
              <p id="categories">Categories</p>
              <li>All</li>
              <br />
              <li>Accessories</li>
              <br />
              <li>Computer</li>
              <br />
              <li>Women Fashion</li>
              <br />
              <li>Men Fashion</li>
              <br />
              <li>Mobile Phone</li>
              <br />
              <li>Beauty & Personal Care</li>
              <br />
              <li>Health & Suppliement</li>
              <br />
              <li>Baby & Kids</li>
              <br />
              <li>Pet Supplies</li>
              <br />
              <li>Furniture & Home Living</li>
              <br />
              <li>Gadgets</li>
              <br />
              <li>Photography</li>
              <br />
              <li>Sports Equipment</li>
              <br />
              <li>Books & Stationaries</li>
              <br />
              <li>Toys</li>
              <br />
              <li>Cars & Motorbikes Accessories</li>
              <br />
              <li>Home Appliances</li>
              <br />
              <li>Others</li>
              <br /><br />
            </ul>
            <p id="categories">Price Range</p>
            <ul class="price-range">
              <li>RM1-9</li>
              <br />
              <li>RM10-100</li>
              <br />
              <li>RM101-500</li>
              <br />
              <li>RM501-1000</li>
              <br />
              <li>RM1000++</li>
              <br />
            </ul>
          </div>
        </div>
      </div>
      <div class="rightContainer">
        <div class="search-container">
          <input type="text" class="search-input" placeholder="Search..." />
          <button class="search-button" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
        <div class="grid-container">
          <?php
            foreach ($items as $item) {
              echo "<div class='grid-item' data-category='{$item['item_category']}' data-price='{$item['item_price']}'>";
              echo "<img src='{$item['image1_path']}' alt='Item Image' />";
              echo "<h3>{$item['item_name']}</h3>";
              echo "<p id='mainPrice'>RM {$item['item_price']}</p>";
              echo "<button onclick=\"location.href = 'item_detail.php?item_id={$item['item_id']}';\" class='btn'>View More</button>";
              echo "</div>";
          }
          ?>
      </div>
      <!-- Add this HTML code where you want to display the pagination -->
<div class="pagination-container">
    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <a href="?page=<?= $i ?>" class="pagination-link <?php if ($i === $page) echo 'active'; ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>

    </main>

    <footer>
      <p>&copy; 2023 TradeCycle. All rights reserved.</p>
    </footer>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        // Fetch the username using an AJAX request
        fetch('profile.php')
          .then((response) => response.json())
          .then((data) => {
            if (data.user_name) {
              // Make sure to use the correct key here
              // Update the username in the dropdown
              document.querySelector('.username1').innerText = data.user_name;
            } else {
              console.error('Error fetching username:', data.error);
            }
          })
          .catch((error) => console.error('Error fetching username:', error));

      // Filter logic
      document.querySelectorAll('.filter-categories li').forEach((category) => {
        category.addEventListener('click', function () {
          const categoryName = category.textContent.trim();
          filterItemsByCategory(categoryName);
        });
      });

      document.querySelectorAll('.price-range li').forEach((priceRange) => {
        priceRange.addEventListener('click', function () {
          const priceText = priceRange.textContent.trim();
          filterItemsByPrice(priceText);
        });
      });

      document.querySelector('.search-input').addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
          const searchTerm = document.querySelector('.search-input').value.trim();
          filterItemsBySearch(searchTerm);
        }
      });

      document.querySelector('.search-input').addEventListener('input', function () {
        const searchTerm = document.querySelector('.search-input').value.trim();
        filterItemsBySearch(searchTerm);
      });

      document.querySelector('.search-button').addEventListener('click', function () {
        const searchTerm = document.querySelector('.search-input').value.trim();
        filterItemsBySearch(searchTerm);
      });

      function filterItemsByCategory(category) {
        const items = document.querySelectorAll('.grid-item');
        items.forEach((item) => {
          const itemCategory = item.dataset.category;
          
          if (category === 'All' || itemCategory === category) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });
      }

      function filterItemsByPrice(priceRange) {
        const items = document.querySelectorAll('.grid-item');
        items.forEach((item) => {
          const itemPrice = parseFloat(item.dataset.price);
          const [min, max] = priceRange.split('-').map((val, index) => index === 0 ? parseFloat(val.replace('RM', '')) : parseFloat(val.trim()));
          if (itemPrice >= min && itemPrice <= max) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });
      }

      function filterItemsBySearch(searchTerm) {
        const items = document.querySelectorAll('.grid-item');
        items.forEach((item) => {
          const itemName = item.querySelector('h3').textContent;
          const regex = new RegExp(searchTerm.replace(/[-/\\^$*+?.()|[\]{}]/g, '\\$&'), 'i');

          if (itemName.match(regex)) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });
      }
    });
    </script>
  </body>
</html>
