<?php
session_start();
require_once 'database/db_config.php';
$url_prefix = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Brands | SB Smart India</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="favicon.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="asstes/css/style.css">
    <link rel="stylesheet" href="asstes/css/footer.css">
    <link rel="stylesheet" href="assets/css/header-menu.css">
    <link rel="stylesheet" href="assets/css/mega-menu.css">

    <style>
        :root {
            --brand-primary: #004aad;
            --brand-light: #eef4ff;
            --text-dark: #1a1a1a;
            --text-muted: #666;
            --bg-soft: #f8f9fa;
        }

        .brands-hero {
            background-color: var(--brand-primary);
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .brands-hero h1 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .brands-hero p {
            font-size: 18px;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        .brands-container {
            max-width: 1300px;
            margin: 50px auto;
            padding: 0 20px;
        }

        /* Alphabet Filter Bar */
        .alphabet-bar {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 40px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            position: sticky;
            top: 10px;
            z-index: 100;
        }

        .alphabet-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            background: var(--bg-soft);
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .alphabet-btn:hover, .alphabet-btn.active {
            background: var(--brand-primary);
            color: white;
            transform: translateY(-2px);
        }

        .alphabet-btn.disabled {
            opacity: 0.3;
            pointer-events: none;
        }

        /* Search Bar */
        .brand-search-row {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        .brand-search-wrapper {
            position: relative;
            max-width: 500px;
            width: 100%;
        }

        .brand-search-wrapper i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--brand-primary);
        }

        .brand-search-input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border-radius: 50px;
            border: 2px solid #eee;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
        }

        .brand-search-input:focus {
            border-color: var(--brand-primary);
            outline: none;
            box-shadow: 0 4px 20px rgba(0, 74, 173, 0.1);
        }

        /* Brand Grid */
        .brand-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 25px;
            margin-top: 50px;
        }

        .brand-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            color: var(--text-dark);
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .brand-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-color: var(--brand-primary);
        }

        .brand-logo-container {
            width: 100%;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            overflow: hidden;
        }

        .brand-logo-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .brand-card:hover .brand-logo-container img {
            transform: scale(1.1);
        }

        .brand-name {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 5px;
            color: var(--text-dark);
        }

        .brand-product-count {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .brand-fallback {
            background: var(--brand-light);
            color: var(--brand-primary);
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 800;
            border-radius: 8px;
        }

        .no-brands-found {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 20px;
            color: var(--text-muted);
        }

        .no-brands-found i {
            display: block;
            font-size: 60px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            .brands-hero h1 { font-size: 32px; }
            .brand-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 15px; }
            .alphabet-bar { gap: 4px; padding: 10px; }
            .alphabet-btn { width: 30px; height: 30px; font-size: 13px; }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="brands-hero">
        <div class="container">
            <h1>Our Partner Brands</h1>
            <p>We partner with the world's most trusted industrial manufacturers to ensure your business runs with 100% genuine and high-performance products.</p>
            
            <div class="brand-search-row">
                <div class="brand-search-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="brandSearchInput" class="brand-search-input" placeholder="Search for a brand...">
                </div>
            </div>
        </div>
    </section>

    <div class="brands-container">
        <!-- Alphabet Navigation -->
        <div class="alphabet-bar">
            <div class="alphabet-btn active" data-letter="ALL">ALL</div>
            <?php
            foreach (range('A', 'Z') as $char) {
                echo '<div class="alphabet-btn" data-letter="' . $char . '">' . $char . '</div>';
            }
            ?>
            <div class="alphabet-btn" data-letter="0-9">0-9</div>
        </div>

        <div class="brand-grid" id="brandGrid">
            <?php
            // Fetch all brands with product counts
            $brands_sql = "SELECT b.*, (SELECT COUNT(*) FROM products WHERE brand_id = b.id AND status = 1) as product_count 
                           FROM brands b 
                           ORDER BY b.name ASC";
            $brands_res = $conn->query($brands_sql);

            if ($brands_res && $brands_res->num_rows > 0) {
                while ($brand = $brands_res->fetch_assoc()) {
                    $letter = strtoupper(substr($brand['name'], 0, 1));
                    $data_letter = is_numeric($letter) ? '0-9' : $letter;
                    
                    $logo_path = $brand['logo'];
                    $has_logo = (!empty($logo_path) && file_exists($logo_path));
            ?>
                    <a href="products.php?brand=<?php echo $brand['id']; ?>" class="brand-card" data-name="<?php echo htmlspecialchars(strtolower($brand['name'])); ?>" data-letter="<?php echo $data_letter; ?>">
                        <div class="brand-logo-container">
                            <?php if ($has_logo): ?>
                                <img src="<?php echo htmlspecialchars($logo_path); ?>" alt="<?php echo htmlspecialchars($brand['name']); ?>">
                            <?php else: ?>
                                <div class="brand-fallback"><?php echo strtoupper(substr($brand['name'], 0, 2)); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="brand-name"><?php echo htmlspecialchars($brand['name']); ?></div>
                        <div class="brand-product-count"><?php echo $brand['product_count']; ?> Products</div>
                    </a>
            <?php
                }
            } else {
                echo '<div class="no-brands-found"><i class="fa-solid fa-industry"></i><p>No brands currently available.</p></div>';
            }
            ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('brandSearchInput');
            const alphabetBtns = document.querySelectorAll('.alphabet-btn');
            const brandCards = document.querySelectorAll('.brand-card');
            const brandGrid = document.getElementById('brandGrid');
            
            let activeLetter = 'ALL';
            let searchQuery = '';

            function filterBrands() {
                let foundAny = false;
                
                brandCards.forEach(card => {
                    const name = card.getAttribute('data-name');
                    const letter = card.getAttribute('data-letter');
                    
                    const matchesSearch = name.includes(searchQuery);
                    const matchesLetter = (activeLetter === 'ALL' || letter === activeLetter);
                    
                    if (matchesSearch && matchesLetter) {
                        card.style.display = 'flex';
                        foundAny = true;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/Hide no results message
                let noResultsMsg = document.getElementById('noResultsMsg');
                if (!foundAny) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.id = 'noResultsMsg';
                        noResultsMsg.className = 'no-brands-found';
                        noResultsMsg.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i><p>No brands match your search or filter.</p>';
                        brandGrid.appendChild(noResultsMsg);
                    }
                } else if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            }

            // Search Event
            searchInput.addEventListener('input', (e) => {
                searchQuery = e.target.value.toLowerCase().trim();
                filterBrands();
            });

            // Alphabet Filter Event
            alphabetBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    alphabetBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    activeLetter = btn.getAttribute('data-letter');
                    filterBrands();
                });
            });

            // Highlight used letters in alphabet bar based on actual brands
            const usedLetters = new Set();
            brandCards.forEach(card => usedLetters.add(card.getAttribute('data-letter')));
            
            alphabetBtns.forEach(btn => {
                const letter = btn.getAttribute('data-letter');
                if (letter !== 'ALL' && !usedLetters.has(letter)) {
                    btn.classList.add('disabled');
                }
            });
        });
    </script>
</body>
</html>
