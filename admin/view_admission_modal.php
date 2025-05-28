<?php
/**
 * View Admission Application Modal
 * 
 * This file is used to display the full details of an admission application in a modal.
 */

// Start session
session_start();

// Include database connection
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    die('Unauthorized access');
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    die('No application ID provided');
}

try {
    $db = getDbConnection();
    
    // Get application details
    $stmt = $db->prepare("SELECT * FROM admission_forms WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $application = $stmt->fetch();
    
    if (!$application) {
        die('Application not found');
    }
} catch (PDOException $e) {
    die('Error loading application details');
}
?>

<div class="space-y-4">
    <!-- Student Information -->
    <div>
        <h4 class="font-semibold text-gray-900 mb-2">Student Information</h4>
        <div class="space-y-2">
            <p><span class="font-medium">Name:</span> <?php echo htmlspecialchars($application['child_first_name'] . ' ' . $application['child_last_name']); ?></p>
            <p><span class="font-medium">Date of Birth:</span> <?php echo date('F d, Y', strtotime($application['child_date_of_birth'])); ?></p>
            <p><span class="font-medium">Gender:</span> <?php echo ucfirst(htmlspecialchars($application['child_gender'])); ?></p>
            <p><span class="font-medium">Applying for:</span> <?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($application['applying_for_class']))); ?></p>
            <?php if (!empty($application['previous_school'])): ?>
                <p><span class="font-medium">Previous School:</span> <?php echo htmlspecialchars($application['previous_school']); ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Parent Information -->
    <div>
        <h4 class="font-semibold text-gray-900 mb-2">Parent/Guardian Information</h4>
        <div class="space-y-2">
            <p><span class="font-medium">Name:</span> <?php echo htmlspecialchars($application['parent_name']); ?></p>
            <p><span class="font-medium">Phone:</span> <?php echo htmlspecialchars($application['parent_phone']); ?></p>
            <p><span class="font-medium">Email:</span> <?php echo htmlspecialchars($application['parent_email']); ?></p>
            <p><span class="font-medium">Address:</span> <?php echo htmlspecialchars($application['parent_address']); ?></p>
            <p><span class="font-medium">How did you hear about us:</span> <?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($application['how_did_you_hear']))); ?></p>
        </div>
    </div>
    
    <?php if (!empty($application['additional_info'])): ?>
        <!-- Additional Information -->
        <div>
            <h4 class="font-semibold text-gray-900 mb-2">Additional Information</h4>
            <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($application['additional_info'])); ?></p>
        </div>
    <?php endif; ?>
    
    <!-- Application Status -->
    <div>
        <h4 class="font-semibold text-gray-900 mb-2">Application Status</h4>
        <div class="space-y-2">
            <p><span class="font-medium">Status:</span> 
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    <?php
                    echo match($application['status'] ?? 'pending') {
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'approved' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                    };
                    ?>">
                    <?php echo ucfirst($application['status'] ?? 'pending'); ?>
                </span>
            </p>
            <p><span class="font-medium">Submitted:</span> <?php echo date('F d, Y H:i', strtotime($application['created_at'])); ?></p>
            <?php if ($application['is_processed']): ?>
                <p><span class="font-medium">Processed:</span> <?php echo date('F d, Y H:i', strtotime($application['updated_at'])); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div> 