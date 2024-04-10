<!DOCTYPE html>
<html lang="en">
<?php include("db_conn.php");
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);

    // Set a session timeout (e.g., 30 minutes)
    $sessionTimeout = 30 * 60; // 30 minutes in seconds
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $sessionTimeout) {
        session_unset();
        session_destroy();
        header("Location: loginAdmin.php"); // Redirect to login page
        exit;
    }
    $_SESSION['last_activity'] = time();
    

} else {
    header("Location: loginAdmin.php");
    exit;
}
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="static/images/CvSU-logo-trans.png" sizes="192x192">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <title>CvSU Admin Dashboard</title>
   
</head>
<body>


<nav class="main-header navbar navbar-expand navbar-white navbar-light w-auto p-3 fixed ">
  <!-- Left navbar links -->
  <ul class="navbar-nav ">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" id="menu-toggle"></i></a>
    
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="dashboardAdmin.php" class="nav-link">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Contact</a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Help
      </a>
      <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
        <a class="dropdown-item" href="#">FAQ</a>
        <a class="dropdown-item" href="#">Support</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#">Contact</a>
      </div>
    </li>
  </ul>

  <!-- SEARCH FORM -->
  <form class="form-inline ml-3">
    <div class="input-group input-group-sm">
      <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" id = "searchInput">
      <div class="input-group-append">
        <button class="btn btn-navbar" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
  
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        <span class="badge badge-danger navbar-badge">3</span>
      </a>
    
    <li class="nav-item">
      <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i
          class="fas fa-th-large"></i></a>
    </li>
  </ul>

  <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i> <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                                        data-bs-target="#userSettingsModal">Settings</a></li>
                                <li><a class="dropdown-item" href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
</nav>
           
           <!-- Edit Modal -->
           <div class="modal fade" id="editModal<?= $rowNumber ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $rowNumber ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel<?= $rowNumber ?>">Edit Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit.php" method="post">
                    <!-- Use id instead of tag for editing -->
                    <input type="hidden" name="idToEdit" value="<?= $intent['id'] ?>">
                    New Tag Value: <input type="text" name="newTagValue" class="form-control" value="<?= $intent['tag'] ?>"><br>

                    <div class="form-group mb-3">
                     <label for="patternsToEdit">Patterns:</label>
                            <div id="patternsContainer">
                        <?php foreach ($intent['patterns'] as $pattern): ?>
                            <input type="text" name="patternsToEdit[]" class="form-control mb-2" value="<?= $pattern ?>">
                            <button type="button" class="btn btn-primary" onclick="edit_removePattern(this)">Remove Pattern</button>
                        <?php endforeach; ?>
                        <button type="button" class="btn btn-primary" onclick="edit_addPattern()">Add Pattern</button>
                    </div>
                </div>

                    <div class="form-group mb-3">
                        <label for="responsesToEdit">Responses:</label>
                        <div id="responsesContainer">
                            <?php foreach ($intent['responses'] as $response): ?>
                                <input type="text" name="responsesToEdit[]" class="form-control mb-2" value="<?= $response ?>">
                                <button type="button" class="btn btn-primary" onclick="edit_removeResponse(this)">Remove Response</button>
                            <?php endforeach; ?>
                            <button type="button" class="btn btn-primary" onclick="edit_addResponse()">Add Response</button>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
            </div>
            
        </div>
    </div>
   
</div>


<!-- Delete modal -->
<div class="modal fade" id="deleteModal<?= $rowNumber ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?= $rowNumber ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel<?= $rowNumber ?>">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this entry?
            </div>
            <div class="modal-footer">
                <form action="delete.php" method="post">
                    <!-- Make sure the value of idToDelete matches the correct id -->
                    <input type="hidden" name="idToDelete" value="<?= $intent['id'] ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>






    <!-- User Settings Modal -->
    <div class="modal fade user-settings-modal " id="userSettingsModal" tabindex="-1" aria-labelledby="userSettingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userSettingsModalLabel">User Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
               
                    <button id="darkModeToggle" class="btn btn-primary">Toggle Dark Mode</button>




                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />

<div class="container-fluid">
<div class="row">
		<div class="col-12">
			<!-- Page title -->
			<div class="my-5">
				<h3>My Profile</h3>
				<hr>
			</div>
			<!-- Form START -->
			<form class="file-upload">
				<div class="row mb-5 gx-5">
					<!-- Contact detail -->
					<div class="col-xxl-8 mb-5 mb-xxl-0">
						<div class="bg-secondary-soft px-4 py-5 rounded">
							<div class="row g-3">
								<h4 class="mb-4 mt-0">Contact detail</h4>
								<!-- First Name -->
								<div class="col-md-6">
									<label class="form-label">First Name *</label>
									<input type="text" class="form-control" placeholder="" aria-label="First name" value="Scaralet">
								</div>
								<!-- Last name -->
								<div class="col-md-6">
									<label class="form-label">Last Name *</label>
									<input type="text" class="form-control" placeholder="" aria-label="Last name" value="Doe">
								</div>
								<!-- Phone number -->
								<div class="col-md-6">
									<label class="form-label">Phone number *</label>
									<input type="text" class="form-control" placeholder="" aria-label="Phone number" value="(333) 000 555">
								</div>
								<!-- Mobile number -->
								<div class="col-md-6">
									<label class="form-label">Mobile number *</label>
									<input type="text" class="form-control" placeholder="" aria-label="Phone number" value="+91 9852 8855 252">
								</div>
								<!-- Email -->
								<div class="col-md-6">
									<label for="inputEmail4" class="form-label">Email *</label>
									<input type="email" class="form-control" id="inputEmail4" value="example@homerealty.com">
								</div>
								<!-- Skype -->
								<div class="col-md-6">
									<label class="form-label">Skype *</label>
									<input type="text" class="form-control" placeholder="" aria-label="Phone number" value="Scaralet D">
								</div>
							</div> <!-- Row END -->
						</div>
					</div>
					<!-- Upload profile -->
					<div class="col-xxl-4">
						<div class="bg-secondary-soft px-4 py-5 rounded">
							<div class="row g-3">
								<h4 class="mb-4 mt-0">Upload your profile photo</h4>
								<div class="text-center">
									<!-- Image upload -->
									<div class="square position-relative display-2 mb-3">
										<i class="fas fa-fw fa-user position-absolute top-50 start-50 translate-middle text-secondary"></i>
									</div>
									<!-- Button -->
									<input type="file" id="customFile" name="file" hidden="">
									<label class="btn btn-success-soft btn-block" for="customFile">Upload</label>
									<button type="button" class="btn btn-danger-soft">Remove</button>
									<!-- Content -->
									<p class="text-muted mt-3 mb-0"><span class="me-1">Note:</span>Minimum size 300px x 300px</p>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- Row END -->

				<!-- Social media detail -->
				<div class="row mb-5 gx-5">
					<div class="col-xxl-6 mb-5 mb-xxl-0">
						<div class="bg-secondary-soft px-4 py-5 rounded">
							<div class="row g-3">
								<h4 class="mb-4 mt-0">Social media detail</h4>
								<!-- Facebook -->
								<div class="col-md-6">
									<label class="form-label"><i class="fab fa-fw fa-facebook me-2 text-facebook"></i>Facebook *</label>
									<input type="text" class="form-control" placeholder="" aria-label="Facebook" value="http://www.facebook.com">
								</div>
								<!-- Twitter -->
								<div class="col-md-6">
									<label class="form-label"><i class="fab fa-fw fa-twitter text-twitter me-2"></i>Twitter *</label>
									<input type="text" class="form-control" placeholder="" aria-label="Twitter" value="http://www.twitter.com">
								</div>
								<!-- Linkedin -->
								<div class="col-md-6">
									<label class="form-label"><i class="fab fa-fw fa-linkedin-in text-linkedin me-2"></i>Linkedin *</label>
									<input type="text" class="form-control" placeholder="" aria-label="Linkedin" value="http://www.linkedin.com">
								</div>
								<!-- Instragram -->
								<div class="col-md-6">
									<label class="form-label"><i class="fab fa-fw fa-instagram text-instagram me-2"></i>Instagram *</label>
									<input type="text" class="form-control" placeholder="" aria-label="Instragram" value="http://www.instragram.com">
								</div>
								<!-- Dribble -->
								<div class="col-md-6">
									<label class="form-label"><i class="fas fa-fw fa-basketball-ball text-dribbble me-2"></i>Dribble *</label>
									<input type="text" class="form-control" placeholder="" aria-label="Dribble" value="http://www.dribble.com">
								</div>
								<!-- Pinterest -->
								<div class="col-md-6">
									<label class="form-label"><i class="fab fa-fw fa-pinterest text-pinterest"></i>Pinterest *</label>
									<input type="text" class="form-control" placeholder="" aria-label="Pinterest" value="http://www.pinterest.com">
								</div>
							</div> <!-- Row END -->
						</div>
					</div>

					<!-- change password -->
					<div class="col-xxl-6">
						<div class="bg-secondary-soft px-4 py-5 rounded">
							<div class="row g-3">
								<h4 class="my-4">Change Password</h4>
								<!-- Old password -->
								<div class="col-md-6">
									<label for="exampleInputPassword1" class="form-label">Old password *</label>
									<input type="password" class="form-control" id="exampleInputPassword1">
								</div>
								<!-- New password -->
								<div class="col-md-6">
									<label for="exampleInputPassword2" class="form-label">New password *</label>
									<input type="password" class="form-control" id="exampleInputPassword2">
								</div>
								<!-- Confirm password -->
								<div class="col-md-12">
									<label for="exampleInputPassword3" class="form-label">Confirm Password *</label>
									<input type="password" class="form-control" id="exampleInputPassword3">
								</div>
							</div>
						</div>
					</div>
				</div> <!-- Row END -->
				<!-- button -->
				<div class="gap-3 d-md-flex justify-content-md-end text-center">
					<button type="button" class="btn btn-danger btn-lg">Delete profile</button>
					<button type="button" class="btn btn-primary btn-lg">Update profile</button>
				</div>
			</form> <!-- Form END -->
		</div>
	</div>
	</div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Training console within a modal -->
    <div class="modal fade" id="trainingConsoleModal" tabindex="-1" aria-labelledby="trainingConsoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trainingConsoleModalLabel">Training Console</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <button id="trainButton" class="btn btn-success btn-md mb-4" type="submit" name="train_chatbot">
                        Train Chatbot
                    </button>
                </form>
                <div id="training-console" class="text-left">
                    <?php
                    if (isset($_POST['train_chatbot'])) {
                        try {
                            // Execute the Python training script and capture output and errors
                            $output = shell_exec("python train.py 2>&1");

                            // Check if there was an error
                            if ($output === null) {
                                throw new Exception("Command execution failed");
                            }

                            // Display the training progress or logs (including errors)
                            echo '<div class="container mt-4">';
                            echo '<div class="row justify-content-center">';
                            echo '<div class="col-md-10">';
                            echo '<pre>' . $output . '</pre>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        } catch (Exception $e) {
                            // Handle the exception (e.g., display an error message)
                            echo '<div class="container mt-4">';
                            echo '<div class="row justify-content-center">';
                            echo '<div class="col-md-10">';
                            echo '<div class="alert alert-danger" role="alert">';
                            echo 'An error occurred: ' . $e->getMessage();
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Add Intent within a modal -->
<div class="modal fade" id="addIntentModal" tabindex="-1" aria-labelledby="addIntentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addIntentModalLabel">Add New Intent</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                





                <form action="process_add_intent.php" method="POST">
                    <div class="mb-3">
                        <label for="tag" class="form-label">Tag:</label>
                        <input type="text" class="form-control" id="tag" name="tag" required>
                    </div>

                    <div class="mb-3">
                        <label for="patterns" class="form-label">Patterns:</label>
                        <div id="patternsContainer">
                        <textarea class="form-control" name="patterns[]" rows="2" required></textarea>
                        </div>
                        <button type="button" class="btn btn-primary mt-2" onclick="addPattern()">Add Pattern</button>
                        <button type="button" class="btn btn-danger mt-2" onclick="removePattern()">Remove Pattern</button>

                    </div>

                    <div class="mb-3">
                        <label for="responses" class="form-label">Responses:</label>
                        <div id="responsesContainer">
                            <textarea class="form-control" name="responses[]" rows="3" required></textarea>
                        </div>
                        <button type="button" class="btn btn-primary mt-2" onclick="addResponse()">Add Response</button>
                        <button type="button" class="btn btn-danger mt-2" onclick="removeResponse()">Remove Response</button>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-success" type="submit">Add Intent</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</div>

<!-- Feedback within a modal -->
<div class="modal fade" id="viewTableModal" tabindex="-1" aria-labelledby="viewTableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTableModalLabel">Feedback Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Message</th>
                                <th>Email</th>
                                <th>Action</th> <!-- New column for the delete button -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Your database connection code here
                            $result = mysqli_query($conn, "SELECT * FROM test_db.feedback");

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['message'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td><button class='btn btn-danger' onclick='deleteFeedback(" . $row['id'] . ")'>Delete</button></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  

        function deleteFeedback(feedbackId) {
            // Use jQuery for the AJAX request
            var confirmation = confirm("Are you sure you want to delete this feedback?");
            
            if (confirmation) {
                $.post('delete_feedback.php', { id: feedbackId }, function(data) {
                    // Handle the response
                    alert(data);
                   
                  setTimeout(function(){ location.reload(); }, 1000);
                    // You may also consider removing the row from the table without reloading the whole content
                }).fail(function() {
                    // Handle errors
                    alert("Error deleting feedback");
                });
            }
        }
    </script>



    <div class="d-flex" id="wrapper">


  
        <!-- Sidebar -->
        <div class="bg-transparent text-dark" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold  border-bottom">
                <img src="static/images/CvSU-logo-trans.png" alt="image" width="50" height="40"> CvSU
            </div>
            <div class="list-group list-group-flush my-3">
                <a href="dashboardAdmin.php" class="list-group-item list-group-item-action bg-transparent second-text second-text active">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
                <button class="list-group-item list-group-item-action bg-transparent second-text active"
                    data-bs-toggle="modal" data-bs-target="#addIntentModal">
                    <i class="fas fa-project-diagram me-2"></i> Add Response
                </button>
                <button class="list-group-item list-group-item-action bg-transparent second-text active"
                    data-bs-toggle="modal" data-bs-target="#trainingConsoleModal">
                    <i class="fas fa-brain me-2"></i> Train
                </button>
                <a href="#" class="list-group-item list-group-item-action bg-transparent second-text active"
                    data-bs-toggle="modal" data-bs-target="#viewTableModal">
                    <i class="fas fa-paperclip me-2"></i> Feedback
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    onclick="return confirm('Are you sure you want to logout?');">
                    <i class="fas fa-power-off me-2"></i> Logout
                </a>
            </div>


            
        </div>
        <!-- /#sidebar-wrapper -->



        <!-- Page Content -->
<div id="page-content-wrapper">
       
        
            <div class="row my-5">
    <h3 class="fs-4 mb-3 col-md-8" style= " padding-left: 30px;">Automated Response System Dataset</h3>

 



        <!-- Page Content -->
      <div class="col">
    <table class="table bg-white rounded shadow-sm table-hover">
        <thead>
            <tr>
                <th scope="col" width="50">#</th>
                <th scope="col">Tags</th>
                <th scope="col">Patterns</th>
                <th scope="col">Responses</th>
                <th scope="col" class="actions-column">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Number of records per page
        $offset = ($page - 1) * $limit;

        $json_data = file_get_contents("intents.json");
        $intents = json_decode($json_data, true);

        if (isset($intents['intents']) && is_array($intents['intents'])):
            $rowNumber = ($page - 1) * $limit + 1; // Initialize a counter variable considering the current page
            foreach (array_slice($intents['intents'], $offset, $limit) as $intent):
        ?>
            <tr>
                <th scope="row"><?= $rowNumber ?></th>
                <td><?= htmlspecialchars($intent['tag']) ?></td>

                <!-- Display patterns as a table-like layout -->
                <td>
                    <table class="table table-bordered">
                        <?php foreach ($intent['patterns'] as $pattern): ?>
                            <tr><td><?= htmlspecialchars($pattern) ?></td></tr>
                        <?php endforeach; ?>
                    </table>
                </td>

                <!-- Display responses asa table-like layout -->
                <td>
                    <table class="table table-bordered">
                        <?php foreach ($intent['responses'] as $response): ?>
                            <tr><td><?= htmlspecialchars($response) ?></td></tr>
                        <?php endforeach; ?>
                    </table>
                </td>

                <td>
                    <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editModal<?= $rowNumber ?>">Edit</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $rowNumber ?>">Delete</button>
                </td>
            </tr>
        <?php
            $rowNumber++;
            endforeach;
     
        ?>
        </tbody>
    </table>

    <!-- Add pagination links -->
    <?php
    $total_intents = count($intents['intents']);
    $total_pages = ceil($total_intents / $limit);

    echo '<ul class="pagination justify-content-center">';
    for ($i = 1; $i <= $total_pages; $i++):
        if ($i == $page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }
    endfor;
    echo '</ul>';
    ?>
</div>

 



            <?php
            $rowNumber++; // Increment the counter variable
        endif;
        ?>
        </tbody>
    </table>
    
</div>
<a href="#" id="backToTopBtn" class="back-to-top" style ="display: inline; "><i class="fa fa-angle-up"aria-hidden="true"></i></a>

  
        </div>
       
        
    
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");
        toggleButton.onclick = function () {
            el.classList.toggle("toggled");
        };

        const searchInput = document.getElementById("searchInput");
        searchInput.addEventListener("input", function () {
            const searchText = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll("table tbody tr");

            rows.forEach(function (row) {
                const columns = row.getElementsByTagName("td");
                let found = false;

                // Loop through columns and check if any contains the search text
                for (let i = 0; i < columns.length; i++) {
                    if (columns[i].textContent.toLowerCase().includes(searchText)) {
                        found = true;
                        break;
                    }
                }

                // Show or hide the row based on search results
                if (found) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });

        // Function to update the training console with new text
        function updateTrainingConsole(text) {
            const trainingConsole = document.getElementById("training-console");
            trainingConsole.innerHTML += text;
        }

        document.getElementById('trainButton').addEventListener('click', function () {
            // Clear the existing console output
            document.getElementById("training-console").innerHTML = "";

            // Function to fetch updates and update the console
            function fetchUpdates() {
                fetch('http://192.168.254.116:5000/train', {
                    method: 'POST'
                })
                    .then(function (response) {
                        if (response.ok) {
                            return response.text(); // Convert the response to text
                        } else {
                            console.error('Error starting training:', response.statusText);
                        }
                    })
                    .then(function (data) {
                        // Update the console with new data
                        updateTrainingConsole(data);

                        // Continue fetching updates every 1 second (adjust as needed)
                        setTimeout(fetchUpdates, 1000);
                    });
            }

            // Start fetching updates
            fetchUpdates();
        });

         // Dark mode toggle functionality
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        darkModeToggle.addEventListener('click', function () {
            body.classList.toggle('dark-mode');
            const isDarkMode = body.classList.contains('dark-mode');
            // Store user's preference in localStorage
            localStorage.setItem('darkMode', isDarkMode);
        });

        // Check the user's dark mode preference from localStorage
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            body.classList.add('dark-mode');
            
        }

        
    function addPattern() {
    var patternsContainer = document.getElementById('patternsContainer');
    var textarea = document.createElement('textarea');
    textarea.className = 'form-control mt-2';
    textarea.name = 'patterns[]';
    textarea.rows = 2; // Set the number of rows
    textarea.required = true;
    patternsContainer.appendChild(textarea);
    }


    function addResponse() {
    var responsesContainer = document.getElementById('responsesContainer');
    var textarea = document.createElement('textarea');
    textarea.className = 'form-control mt-2';
    textarea.name = 'responses[]';
    textarea.rows = 3; // Set the number of rows
    textarea.required = true;
    responsesContainer.appendChild(textarea);
    }

    function removePattern() {
    var patternsContainer = document.getElementById('patternsContainer');
    var patterns = patternsContainer.getElementsByTagName('textarea');
    
    // Check if there's at least one pattern to remove
    if (patterns.length > 1) {
        patterns[patterns.length - 1].remove();
    } else {
        alert('At least one pattern is required.');
    }
}

function removeResponse() {
    var responsesContainer = document.getElementById('responsesContainer');
    var responses = responsesContainer.getElementsByTagName('textarea');
    
    // Check if there's at least one response to remove
    if (responses.length > 1) {
        responses[responses.length - 1].remove();
    } else {
        alert('At least one response is required.');
    }
}

function edit_addPattern() {
    var patternsContainer = document.getElementById('patternsContainer');
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'patternsToEdit[]';
    input.className = 'form-control mb-2';
    patternsContainer.insertBefore(input, patternsContainer.lastChild);
    var removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'btn btn-primary';
    removeButton.textContent = 'Remove Pattern';
    removeButton.onclick = function() {
        edit_removePattern(removeButton);
    };
    patternsContainer.insertBefore(removeButton, input.nextSibling);
}

function edit_removePattern(button) {
    var patternsContainer = document.getElementById('patternsContainer');
    var inputs = patternsContainer.getElementsByTagName('input');

    if (inputs.length > 1) {
        patternsContainer.removeChild(button.previousElementSibling);
        patternsContainer.removeChild(button);
    } else {
        alert('At least one pattern is required.');
    }
}



window.addEventListener('scroll', () => {
    const backToTopButton = document.getElementById('backToTopBtn');

    // Show back to top button when user scrolls down
    if (window.scrollY > 300) {
      backToTopButton.style.display = 'block';
    } else {
      backToTopButton.style.display = 'none';
    }
  });

  // Scroll to the top when the button is clicked
  document.getElementById('backToTopBtn').addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth' // Smooth scrolling behavior
    });
  });

    </script>


<?php
    // Handle Deleting an Intent
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tagToDelete'])) {
        $tag_to_delete = $_POST['tagToDelete'];

        $json_data = file_get_contents("intents.json");
        $intents = json_decode($json_data, true);

        foreach ($intents['intents'] as $key => $intent) {
            if ($intent['tag'] == $tag_to_delete) {
                unset($intents['intents'][$key]);
            }
        }

        $intents['intents'] = array_values($intents['intents']);
        file_put_contents("intents.json", json_encode($intents, JSON_PRETTY_PRINT));
        header("Location: dashboardAdmin.php");
        exit();
    }

    // Handle Editing an Intent
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tagToEdit']) && isset($_POST['newTagValue'])) {
        $tag_to_edit = $_POST['tagToEdit'];
        $new_tag_value = $_POST['newTagValue'];

        $json_data = file_get_contents("intents.json");
        $intents = json_decode($json_data, true);

        foreach ($intents['intents'] as &$intent) {
            if ($intent['tag'] == $tag_to_edit) {
                $intent['tag'] = $new_tag_value;
            }
        }

        file_put_contents("intents.json", json_encode($intents, JSON_PRETTY_PRINT));
        header("Location: dashboardAdmin.php");
        exit();
    }

    // Handle Adding an Intent
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tag']) && isset($_POST['patterns']) && isset($_POST['responses'])) {
        $tag = $_POST["tag"];
        $patterns = $_POST["patterns"];
        $responses = $_POST["responses"];

        $json_data = file_get_contents("intents.json");
        $intents = json_decode($json_data, true);

        $new_intent = array(
            "tag" => $tag,
            "patterns" => $patterns,
            "responses" => $responses
        );

        $intents['intents'][] = $new_intent;

        file_put_contents("intents.json", json_encode($intents, JSON_PRETTY_PRINT));

        echo '<script>alert("Intent added successfully, click the train button to refresh the data.");</script>';
        echo '<script>window.location.href = "dashboardAdmin.php";</script>';
        exit();
    }
    ?>

</body>

</html>
