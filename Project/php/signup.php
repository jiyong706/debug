<?php
	session_start();
	include_once "config.php";
	$fname = mysqli_real_escape_string($conn, $_POST['fname']);
	$lname = mysqli_real_escape_string($conn, $_POST['lname']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);
	
	if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
		//check user email is valid or not
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){ //if email is valid
			//check if email already exist in the database or not
			$sql = mysqli_query($conn, "SELECT email FROM users WHERE email = '{$email}'");
			if(mysqli_num_rows($sql) > 0 ){ //if email already exist at least one
				echo "$email - This email already exist!";
			}
			else{
				//checking if user uploaded image or not 
				if(isset($_FILES['image'])){ //if image file is uploaded
					$img_name = $_FILES['image']['name']; //getting user uploaded img name
					$img_type = $_FILES['image']['type']; //getting user uploaded img type
					$tmp_name = $_FILES['image']['tmp_name']; //temporary name used to save/move file in the folder
					
					//let's explode image and get the last extension like jpg png
					$img_explode = explode('.', $img_name);
					$img_ext = end($img_explode); //here we get the extension of the user uploaded img file
					
					$extensions = ['png', 'jpeg', 'jpg']; //these are some valid img ext and we've store them in array
					if(in_array($img_ext, $extensions) === true){ //checking if img ext is matched with any array extensions
						$time = time(); //this will return us current time
										//time is needed for renaming user file with current time so that all img file will have unique name
						$new_img_name = $time.$img_name;
						if(move_uploaded_file($tmp_name, "images/".$new_img_name)){
							$status = "Active now"; //once user signed up then the user's status will be active now
							$random_id = rand(time(), 100000000); //creating random id for the user
							
							//inserting all user data inside the table
							$sql2 = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
												 VALUES({$random_id}, '{$fname}', '{$lname}', '{$email}', '{$status}', '{$new_img_name}', '{$status}')");
							if($sql2){
								$sql3 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
								if(mysqli_num_rows($sql3) > 0){
									$row = mysqli_fetch_assoc($sql3);
									$_SESSION['unique_id'] = $row['unique_id']; 
									echo "success";
								}
							}
						}
					}
					else{
						echo "Please select an Image file - jpeg, jpg, png!";
					}
					
					
				}
				else{
					echo "Please select an Image file!";
				}
			}
			
		}
		else{
			echo "$email - This is not a valid email!";
		}
	}
	else{
		echo "All input field are required!";
	}
?>