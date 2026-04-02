<?php
include 'auth_session.php';
if (!isset($url_prefix)) $url_prefix = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sbsmart Admin</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?php echo $url_prefix; ?>assets/css/admin.css?v=<?php echo time(); ?>">
    
    <!-- Chart.js (Optional but recommended) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="admin-wrapper">
    
    <!-- Sidebar Include -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="admin-main">
        <header class="admin-header">
            <div class="header-left">
                <button class="sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-search">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="admin-main-search" placeholder="Search products..." autocomplete="off">
                        <div id="search-suggestions" class="search-suggestions-dropdown"></div>
                    </div>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('admin-main-search');
                    const suggestionsContainer = document.getElementById('search-suggestions');
                    let debounceTimer;

                    searchInput.addEventListener('input', function() {
                        clearTimeout(debounceTimer);
                        const query = this.value.trim();

                        if (query.length < 2) {
                            suggestionsContainer.style.display = 'none';
                            return;
                        }

                        debounceTimer = setTimeout(() => {
                            fetch(`<?php echo $url_prefix; ?>includes/search_suggestions.php?query=${encodeURIComponent(query)}`)
                                .then(response => response.json())
                                .then(data => {
                                    renderSuggestions(data);
                                })
                                .catch(error => console.error('Error fetching suggestions:', error));
                        }, 300);
                    });

                    function renderSuggestions(data) {
                        if (data.length === 0) {
                            suggestionsContainer.innerHTML = '<div class="suggestion-item no-results">No products found</div>';
                        } else {
                            let html = '';
                            data.forEach(item => {
                                html += `
                                    <a href="<?php echo $url_prefix; ?>products/edit-product.php?id=${item.id}" class="suggestion-item">
                                        <img src="${item.image}" alt="${item.title}" class="suggestion-img">
                                        <div class="suggestion-info">
                                            <span class="suggestion-title">${item.title}</span>
                                        </div>
                                    </a>
                                `;
                            });
                            suggestionsContainer.innerHTML = html;
                        }
                        suggestionsContainer.style.display = 'block';
                    }

                    // Close suggestions when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                            suggestionsContainer.style.display = 'none';
                        }
                    });

                    // Show suggestions again when focusing if not empty
                    searchInput.addEventListener('focus', function() {
                        if (this.value.trim().length >= 2 && suggestionsContainer.innerHTML !== '') {
                            suggestionsContainer.style.display = 'block';
                        }
                    });
                });
                </script>
            </div>
            
            <div class="header-right">
                <div class="header-icon">
                    <!-- <i class="fas fa-bell"></i>
                    <span class="badge-dot"></span> -->
                </div>
                
                <div class="user-profile">
                    <div class="user-info">
                        <span>Admin</span>
                        <small>Administrator</small>
                    </div>
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </header>

        <div class="admin-content">
