	<header>
		<div class="container">
			<div class="slider-container">
				<div class="slider-container">
					<div class="intro-text">
						<div class="intro-lead-in">Os serviços de melhor qualidade!</div>
						<div class="intro-heading">Leo Tech</div>
						<a href="#about" class="page-scroll btn btn-xl">Saiba Mais</a>
					</div>
				</div>
			</div>
		</div>
	</header>
	<section id="about" class="light-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 text-center">
						<div class="section-title">
							<h2>ABOUT</h2>
							<p>A creative agency based on Candy Land, ready to boost your business with some beautifull templates. Lattes Agency is one of the best in town see more you will be amazed.</p>
						</div>
					</div>
				</div>				
			</div>
			<!-- /.container -->
		</section>		
		<section class="overlay-dark bg-img1 dark-bg short-section">
			<div class="container text-center">
				<div class="row">
					<div class="col-md-3 mb-sm-30">
						<div class="counter-item">
							<h2 data-count="x">X</h2>
							<a class="page-scroll" href="#service"><h6>Serviços</h6></a>
						</div>
					</div>
					<div class="col-md-3 mb-sm-30">
						<div class="counter-item">
							<h2 data-count="x">X</h2>
							<a class="page-scroll" href="#team"><h6>Equipe</h6></a>
						</div>
					</div>
					<div class="col-md-3 mb-sm-30">
						<div class="counter-item">
							<h2 data-count="x">X</h2>
							<a class="page-scroll" href="#portfolio"><h6>Contato</h6></a>
						</div>
					</div>
					<div class="col-md-3 mb-sm-30">
						<div class="counter-item">
							<h2 data-count="x">X</h2>
							<a class="page-scroll" href="#portfolio"><h6>Serviços</h6></a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section id="service" class="light-bg">
			<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<div class="section-title">
						<h2>Serviços</h2>
						<p>Conheça nossos serviços.</p>
					</div>
				</div>
			</div>
			<div class="row">
				<?php 
					if (!empty($services)) {
						foreach ($services as $service) { ?>
							<div class="col-md-4">
								<div class="ot-portfolio-item">
									<figure class="effect-bubba">
										<img src="<?=base_url().$service["service_img"]?>" alt="img02" class="img-responsive center-block" />
										<figcaption>											
											<a href="#" data-toggle="modal" data-target="#service_<?=$service["service_id"]?>"></a>
										</figcaption>
									</figure>
								</div>
							</div>	

							<div class="modal fade" id="service_<?=$service["service_id"]?>" tabindex="-1" role="dialog" aria-labelledby="Modal-label-1">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="X"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="Modal-label-1"><?=$service["service_name"]?></h4>
										</div>
										
										<div class="modal-body">
											<img src="<?=base_url().$service["service_img"]?>" alt="img01" class="img-responsive center-block" />
											<div class="modal-works"><span>Duração: <?=intval($service["service_duration"])?> (h)</span></div>
											<p><?=$service["service_description"]?></p>
										</div>
										
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
										</div>
									</div>
								</div>
							</div>
				<?php
						}// foreach
					}// if
				?>	
			</div>
			</div>
		</section>		
		<section id="team" class="light-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 text-center">
						<div class="section-title">
							<h2>Nossa equipe</h2>
							<p>Conheça nossa equipe.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<?php 
					if (!empty($team)) {
						foreach ($team as $member) { ?>

							<div class="col-md-3">
								<a href="#" data-toggle="modal" data-target="#member_<?=$member["member_id"]?>">
									<div class="team-item">
										<div class="team-image">
											<img src="<?=base_url().$member["member_photo"]?>" class="img-responsive img-circle" alt="author">
										</div>
										<div class="team-text">
											<h3><?=$member["member_name"]?></h3>
										</div>
									</div>
								</a>
							</div>

							<div class="modal fade" id="member_<?=$member["member_id"]?>" tabindex="-1" role="dialog" aria-labelledby="Modal-label-1">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="Modal-label-1"><?=$member["member_name"]?></h4>
										</div>
										
										<div class="modal-body">
											<img src="<?=base_url().$member["member_photo"]?>" alt="img01" class="img-responsive center-block" />
											<p><?=$member["member_description"]?></p>
										</div>
										
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
										</div>
									</div>
								</div>
							</div>
					<?php } // FOREACH
					} // IF ?>
				</div>
			</div>
			<br>
		</section>
		<section id="contact">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 text-center">
						<div class="section-title">
							<h2>Contato</h2>
							<p>If you have some Questions or need Help! Please Contact Us!<br>We make Cool and Clean Design for your Business</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<h3>Our Business Office</h3>
						<p>3422 Street, Barcelona 432, Spain, New Building North, 15th Floor</p>
						<p><i class="fa fa-phone"></i> +101 377 655 22125</p>
						<p><i class="fa fa-envelope"></i> mail@yourcompany.com</p>
					</div>
					<div class="col-md-3">
						<h3>Business Hours</h3>
						<p><i class="fa fa-clock-o"></i> <span class="day">Weekdays:</span><span>9am to 8pm</span></p>
						<p><i class="fa fa-clock-o"></i> <span class="day">Saturday:</span><span>9am to 2pm</span></p>
						<p><i class="fa fa-clock-o"></i> <span class="day">Sunday:</span><span>Closed</span></p>
					</div>
					<div class="col-md-6">
						<form name="sentMessage" id="contactForm" novalidate="">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Your Name *" id="name" required="" data-validation-required-message="Please enter your name.">
										<p class="help-block text-danger"></p>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input type="email" class="form-control" placeholder="Your Email *" id="email" required="" data-validation-required-message="Please enter your email address.">
										<p class="help-block text-danger"></p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<textarea class="form-control" placeholder="Your Message *" id="message" required="" data-validation-required-message="Please enter a message."></textarea>
										<p class="help-block text-danger"></p>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="row">
								<div class="col-lg-12 text-center">
									<div id="success"></div>
									<button type="submit" class="btn">Send Message</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
		<p id="back-top">
			<a href="#top"><i class="fa fa-angle-up"></i></a>
		</p>