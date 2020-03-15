<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>OpenChatter</title>
	<link rel="shortcut icon" type="image/x-icon" href="./res/images/logo.png">
	<link rel="stylesheet" href="./res/css/index.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<?php 
		require_once __DIR__.'/vendor/autoload.php';
		require_once __DIR__.'/config.php';
		session_start(); 
	?>
</head>
<body>
	<nav class="text-center bg-dark p-2">
		<img src="./res/images/logo.png" class="mr-1" alt="openchatter logo" width="28">
		<span class="text-white font-weight-light">OpenChatter</span>
		<?php
			if(isset($_SESSION['user_info'])){
				$user_info = $_SESSION['user_info'];
				echo "<span class='float-right text-white font-weight-light'>" . $user_info['given_name'] . " " . $user_info['family_name'][0] . "</span>";
			} else {
				echo '<a href="login.php" class="float-right text-white">Login</a>';
			}
		?>
	</nav>
	<main id="chat" class="p-3"></main>
	<footer id="footer" class="footer card-footer card-transparent fixed-bottom height-50">
		<form id="send_message" action="chat_message.php" method="GET">
			<?php
				if(isset($_SESSION['user_info'])){
					echo '<input type="text" name="msg" placeholder="Press Return to Send" class="bg-transparent border-top-0 border-right-0 border-left-0 w-100 border-dark" autofocus>';
					echo '<input name="redirect_uri" value="http://' . $_SERVER['HTTP_HOST'] . '/" hidden>';
				} else {
					echo '<input type="text" name="msg" placeholder="Sign in to chat" class="bg-transparent border-top-0 border-right-0 border-left-0 w-100 border-dark" disabled>';
				}
			?>
		</form>
	</footer>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script>
		$("#send_message").keydown((event) => {
			if(event.keyCode == 13){
				$("#send_message").submit();
			}
		})

		var previous_data = "";
		function poll(){
			$.get({
				url: "http://" + location.host + "/get_messages.php",
				success: (data) => {
					if(previous_data == data) return;
					$("#chat").html("")
					messages = data.split("_NXT_MSG_");
					for(var i = 1; i < messages.length; i++){	// Skip message one, it'll be empty
						var message = messages[i];
						var author_split = message.split(":");
						var author = author_split[0];
						var parsed_message = "";
						for(var ii = 1; ii < author_split.length; ii++){	// we need to reconstruct the message because we split it up by colons, i=1 because we don't want to include the author's name
							var item = author_split[ii];
							if(ii != 1){	// We took out the colons when splitting, so we need to add them back, unless it's the first item, then we don't want a colon at the beginning of the message
								parsed_message += ":" + item;
							} else {
								parsed_message += item;
							}
						}

						var message_type;
						var username = `<?php 
							if(isset($_SESSION['user_info'])){
								$user_info = $_SESSION['user_info'];
								echo $user_info['given_name'] . " " . $user_info['family_name'][0];
							} else {
								echo 'null';
							}
						?>`;
						if(username == author){
							author = "You"
							message_type = "mymessage";
						} else {
							message_type = "message";
						}

						// <span class="font-weight-bold author-text">Author A:</span>

						var div = `	<div class="card pt-2 pr-2 pl-2 mb-5 ` + message_type + `">
										<span class="font-weight-bold author-text">` + author + `:</span>
										<div class="card-body message-text">
											` + parsed_message + `
										</div>
									</div>`

						$("#chat").prepend(div);
					}
				}
			})
		}

		poll(); // intervals don't run it the first time, so we'll do it
		setInterval(poll, <?php echo POLLING_RATE; ?>)
	</script>
</body>
</html>