<?php
session_start();
require_once '../database/db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$page_title = "Request a Quote - SB Smart";
$current_page = 'request-quote.php';
$url_prefix = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $url_prefix; ?>asstes/css/style.css">
    <link rel="stylesheet" href="<?php echo $url_prefix; ?>assets/css/user-dashboard.css">
    <link rel="stylesheet" href="<?php echo $url_prefix; ?>asstes/css/footer.css">
    <link rel="stylesheet" href="<?php echo $url_prefix; ?>assets/css/brand-menu.css">
    <link rel="stylesheet" href="<?php echo $url_prefix; ?>assets/css/header-menu.css">
    
    <style>
        .quote-form-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .btn-submit {
            background-color: #004aad;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-submit:hover {
            background-color: #003380;
        }
        .download-link {
            color: #004aad;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        .download-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php require_once '../includes/header.php'; ?>

<div class="dashboard-container">
            
            <!-- Sidebar -->
            <?php require_once 'includes/sidebar.php'; ?>

            <!-- Main Content -->
            <div class="dashboard-content">
                <div class="dashboard-header">
                    <h1>Request a Quote</h1>
                </div>

                <div class="quote-form-container">
                    <p style="margin-bottom: 20px; color: #666;">
                        Have bulk requirements? Upload your list or describe your needs below.
                    </p>
                    
                    <a href="<?php echo $url_prefix; ?>assets/downloads/bulk_quote_template.csv" class="download-link" download>
                        <i class="fa-solid fa-file-csv"></i> Download Template (CSV)
                    </a>

                    <form id="bulkQuoteForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="form-label">Message / Requirements</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="Describe your requirements here..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Upload File (Excel/CSV/PDF)</label>
                            <input type="file" name="quote_file" class="form-control" accept=".csv, .xlsx, .xls, .pdf">
                            <small style="color: #666; display: block; margin-top: -15px; margin-bottom: 20px;">Max file size: 5MB</small>
                        </div>
                        
                        <input type="hidden" name="action" value="submit_bulk_quote">

                        <button type="submit" class="btn-submit" id="submitBtn">Submit Request</button>
                    </form>
                    <div id="responseMessage" style="margin-top: 20px; display: none;"></div>
                </div>
            </div>

</div>

<?php require_once '../includes/footer.php'; ?>

<script>
    $('#bulkQuoteForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var btn = $('#submitBtn');
        btn.prop('disabled', true).text('Submitting...');

        $.ajax({
            url: '<?php echo $url_prefix; ?>quote_handler.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    $('#bulkQuoteForm')[0].reset();
                    $('#responseMessage').html('<div style="color: green; font-weight: 600;"><i class="fa-solid fa-check-circle"></i> ' + response.message + '</div>').show();
                } else {
                    $('#responseMessage').html('<div style="color: red; font-weight: 600;"><i class="fa-solid fa-times-circle"></i> ' + response.message + '</div>').show();
                }
                btn.prop('disabled', false).text('Submit Request');
            },
            error: function() {
                $('#responseMessage').html('<div style="color: red; font-weight: 600;">An error occurred. Please try again.</div>').show();
                btn.prop('disabled', false).text('Submit Request');
            }
        });
    });
</script>

</body>
</html>
