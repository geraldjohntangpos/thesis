<?php
		session_start();

		if(!isset($_SESSION['USERID']) && !isset($_SESSION['USERNAME']) && !isset($_SESSION['NAME']) && !isset($_SESSION['TYPE'])) {
				session_destroy();
				header('Location: login.php?q=loginfirst');
		}
		else {
				if($_SESSION['TYPE'] == "Healer") {
						header('Location: healerpages/loginhealer.php');
				}
				else if($_SESSION['TYPE'] == "Admin") {
						header('Location: adminpages/adminlogin.php');
				}
				$clientid = $_SESSION['USERID'];
		}

		include 'queries/connection.php';

		//	Check if the get variables are set.

		if(isset($_GET['accountid']) && isset($_GET['ref'])) {
				$accountid = $_GET['accountid'];
				$ref = $_GET['ref'];
				$backlink;

				//		Check if the variable ref is recognized.

				if($ref == "promotion") {
						$backlink = $ref;
				}
				else if($ref == "allhealers") {
						$backlink = $ref;
				}
				else {

						//			if not recognized.

						$backlink = "promotion";
				}

				//		fetch the healer if it exist.

				$sql = "SELECT * FROM healer WHERE ACCT_ID = '$accountid'";
				$retrieve = $conn->query($sql)->fetchAll();
				if($retrieve) {
						foreach($retrieve as $row) {
								//fetch the data of a certain healer.
								$picture = $row['PICTURE'];
								$details = $row['DETAILS'];
								$name = $row['LASTNAME']. ", " .$row['FIRSTNAME'];
								$clinichours = $row['CLINICHOURS'];
								$address = $row['ADDRESS'];
								$contact = $row['CONTACT'];
						}
				}
				else {

						//			if healer does not exist.

						header('Location: ' .$backlink. '.php');
				}
		}
		else {

				//		if get variables are not set.

				header('Location: promotion.php');
		}

		$likecounter = "";
		$color = "#fff";

		$sql = "SELECT * FROM reaction WHERE LABEL = 'healer' AND LABEL_ID = '$accountid'";
		$retrieve = $conn->query($sql)->fetchAll();
		if($retrieve) {
				$count = 0;
				foreach($retrieve as $row) {
						$likerid = $row['LIKER_ID'];
						$count++;
						if($likerid == $_SESSION['USERID']) {
								$color = "green";
						}
				}
				$likecounter = $count;
		}

?>
		<!DOCTYPE html>
		<html lang="en">

		<head>
				<title> Healer </title>
				<meta charset="utf-8" />
				<meta content="width=device-width, initial-scale=1" name="viewport" />
				<link rel="icon" href="assets/images/icon.png" />
				<link href="assets/css/main.css" rel="stylesheet" />
				<script src="bower_components/jquery/dist/jquery.min.js"></script>
				<script src="bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js"></script>
				<script src="bower_components/wow/dist/wow.min.js"></script>
				<script>
						new WOW().init();
				</script>
				<style type="text/css" rel="Stylesheet">
					table a {
						color: #00f;
					}
				</style>
		</head>

		<body>
				<!--      logo-->
				<div class="container bgMenu">
						<nav class="navbar1 navbar-inverse1 navbar-fixed-top  wow bounceInUp">
								<div class="row">
										<div class="col-md-3 col-sm-3">
												<a href="promotion.php" class="navbar-brand"><img class="img-responsive wow slideInLeft" src="assets/images/logo.png" /></a>
										</div>
										<!-- navbar -->
										<div class="col-md-9 col-sm-9">
												<!-- Menu Items -->
												<div class="topUser">
														<ul class="nav navbar-nav">
																<li>
																		<a href="promotion.php">
																				<?php echo $_SESSION['NAME']; ?>
																		</a>
																</li>
																<li> <a>|</a> </li>
																<li> <a href="queries/signout.php">SIGN-OUT</a> </li>
														</ul>
												</div>
										</div>
								</div>
						</nav>
				</div>
				<!--end nav bar-->
				<!--about healer        -->
				<section class="healerPage">
						<div class="container">
								<!-- picture healer -->
								<div class="row">
										<div class="col-md-8 col-sm-8 col-md-push-4 col-sm-4">
												<p class="wow fadeInRight">
														<?php echo $details; ?>
												</p>
												<table column=3 style="width: 100%; border:1px solid #0f0;">
													<tr style="width: 100%; border:1px solid #0f0;">
														<th colspan=3 style="border:1px solid #0f0; text-align: center;">
															<h1>Service Offered</h1>
														</th>
													</tr>
													<tr style="width: 100%; border:1px solid #0f0;">
														<th style="border:1px solid #0f0; text-align: center; width: 50%;">
															Service Name
														</th>
														<th style="border:1px solid #0f0; text-align: center; width: 20%;">
															Price
														</th>
														<th style="border:1px solid #0f0; text-align: center; width: 30%;">
															Action
														</th>
													</tr>
													<?php
														include 'queries/healerservice.php';
													?>
												</table>
												<p class="wow fadeInRight">
												</p>
												<table column=4 style="width: 100%; border:1px solid #0f0;">
													<tr style="width: 100%; border:1px solid #0f0;">
														<th colspan=4 style="border:1px solid #0f0; text-align: center;">
															<h1>Products Offered</h1>
														</th>
													</tr>
													<tr style="width: 100%; border:1px solid #0f0;">
														<th style="border:1px solid #0f0; text-align: center; width: 40%;">
															Product Name
														</th>
														<th style="border:1px solid #0f0; text-align: center; width: 10%;">
															Quantity
														</th>
														<th style="border:1px solid #0f0; text-align: center; width: 20%;">
															Price
														</th>
														<th style="border:1px solid #0f0; text-align: center; width: 30%;">
															Action
														</th>
													</tr>
													<?php
														include 'queries/healerproduct.php';
													?>
												</table>
										</div>
										<div class="col-md-4 col-sm-4 col-md-pull-8 col-sm-8"> <img class="img-responsive wow fadeInUpBig" src="images/healer/<?php echo $picture; ?>" style="width: 300px; height: 300px; border-radius: 50%;" />
												<p class="wow fadeInUpBig">Healer Name:
														<?php echo $name; ?>
												</p>
												<p class="wow fadeInUpBig">Location:
														<?php echo $address; ?>
												</p>
												<p class="wow fadeInUpBig">Contact Number:
														<?php echo $contact; ?>
												</p>
												<p class="wow fadeInUpBig">Clinic Hours:
														<?php echo $clinichours; ?>
												</p>
												<p class="wow fadeInUpBig">
														<a href="queries/like.php?label=healer&labelid=<?php echo $accountid; ?>&ref=<?php echo $ref; ?>" style="text-decoration: none; color: <?php echo $color;?>">
																<span class="fa fa-thumbs-o-up like"> LIKE </span>
																<?php echo $likecounter;?>
														</a>
												</p>
												<div class="back hvr-grow">
														<a href="<?php echo $backlink; ?>.php"> <img src="assets/images/back.png"></a>
												</div>

												<?php
																				$sql = "SELECT * FROM appointment WHERE CLIENT_ID = '$clientid' AND HEALER_ID = '$accountid' AND STATUS = 'ACTIVE'";

																				$retrieve = $conn->query($sql)->fetchAll();
																				if(count($retrieve)>0) {
																						?><a href="queries/cancelappointment.php?id=<?php echo $accountid; ?>&ref=<?php echo $ref; ?>" style="padding 15px; font-size:24px;"><i class="appointment">Cancel Appointment</i></a>
														<?php
																				}
																				else {
																						?> <a href="appointmentform.php?id=<?php echo $accountid; ?>" style="padding 15px; font-size:24px;"><i class="appointment">Add Appointment</i></a>
																<?php
																				}
																		?>
										</div>
								</div>
						</div>
				</section>
				<!--comments-->
				<section class="comments">
						<div class="container">
								<div class="row">
										<div class="col-md-12 col-sm-12">
												<h1 class="wow fadeInDown">Comments</h1>
												<ul class="wow fadeInDown">
														<iframe src="queries/gethealercomments.php?accountid=<?php echo $accountid; ?>&q=comments" style="width: 450px; height: 450px;"> </iframe>
												</ul>
										</div>
								</div>
						</div>
				</section>
				<?php include "footer.php"; ?>
		</body>

		</html>
