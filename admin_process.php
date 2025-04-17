<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Get selected values
        $collegeCode = $_SESSION['collegeCode'];

        switch ($action) {
            case 'sub_data':
                header("Location: import_sub_data.php?collegeCode=$collegeCode");
                exit();
            case 'placement':
                header("Location:placements_upload.php?collegeCode=$collegeCode");
                exit();
            case 'std_data':
                header("Location: import_std_data.php");
                exit();
            case 'slides':
                header("Location: update_slides.php");
                exit();
            case 'class_id':
                header("Location: admin_class_id.php?collegeCode=$collegeCode");
                exit();
            case 'ext_ab':
                header("Location: upload_external_abilities.php");
                exit();
            case 'create_data':
                header("Location: automatic_table_creation.php");
                exit();
            case 'delete_all_data':
                header("Location: delete_all_data.php");
                exit();
            case 'drop_results_table':
                header("Location: drop_results_table.php");
                exit();
                // Add more cases as needed for other buttons

            default:
                // Handle any default case if needed
                break;
        }
    }
}
?>