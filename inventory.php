<?php
include 'sidebar.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evacuation Center Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: flex-end;
            margin-top: 70px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            width: 1170px;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1rem 1.5rem;
        }
        h4 {
            font-weight: 600;
            margin: 0;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border-radius: 5px;
            padding: 0.5rem 1rem;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .btn-success {
            background-color: #198754;
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            border-radius: 5px;
        }
        .btn-success:hover {
            background-color: #157347;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body class="bg-light">
    <main class="main container py-5" id="main">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-house-damage me-2"></i>Inventory Management</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" id="evacuationForm">
                    <input type="hidden" name="action" value="save">
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="centerName" class="form-label required-field">Evacuation Center Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                <input type="text" class="form-control" id="centerName" name="centerName" 
                                       placeholder="Enter Center Name" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="centerType" class="form-label required-field">Type of Center</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <select class="form-select" id="centerType" name="centerType" required>
                                    <option value="" selected disabled>Select Center Type</option>
                                    <option value="School">School</option>
                                    <option value="Community Center">Community Center</option>
                                    <option value="Gymnasium">Gymnasium</option>
                                    <option value="Church">Church</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="street" class="form-label required-field">Street</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-road"></i></span>
                                <input type="text" class="form-control" id="street" name="street" 
                                       placeholder="Enter Street" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="landmark" class="form-label required-field">Landmark</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" class="form-control" id="landmark" name="landmark" 
                                       placeholder="Enter Landmark" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <label for="locationAddress" class="form-label required-field">Full Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                <textarea class="form-control" id="locationAddress" name="locationAddress" 
                                          rows="2" placeholder="Enter complete address" required></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label required-field">Status</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">Capacity (optional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                                <input type="number" class="form-control" id="capacity" name="capacity" 
                                       placeholder="Enter maximum capacity">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="reset" class="btn btn-outline-secondary me-3">
                            <i class="fas fa-undo me-2"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Save Center
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.getElementById('evacuationForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });

        // Clear validation on input
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('input', function() {
                if (this.hasAttribute('required') && this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    </script>
</body>
</html>