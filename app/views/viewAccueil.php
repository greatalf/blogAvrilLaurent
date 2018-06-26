<?php
use Laurent\App\Service\Flash;
?>
    <header>
        <div id="my_picture" class="container">
            <div class="row">
                <div class="col-lg-12">
                 	<img class="img-responsive" src="app/public/Bootstrap/img/profile.png" alt="Developpeur web">
                        <div class="intro-text">
                            <?php FLASH::cookieFlash('deco', 'success'); ?>
                            <span class="name">Laurent AVRIL</span>
                            <hr class="star-light">
                            <span class="skills">Developper web - A. Laurent, parce qu'il vous le rend...</span>
                        </div>
                </div>
            </div>
        </div>
    </header>

<!-- Portfolio Grid Section -->
    <section id="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Portfolio</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 portfolio-item">
                    <a href="http://chalets-et-caviar.fr/" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="app/public/Bootstrap/img/portfolio/chalets-et-caviar.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="C:/wamp64/www/Blog_Avril_Laurent/app/public/Les Films de Plein Air/index.html" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="app/public/Bootstrap/img/portfolio/Les Films de Plein Air.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="http://www.zannonces.fr/" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="app/public/Bootstrap/img/portfolio/zannonces.png" class="img-responsive" alt="">
                    </a>
                </div>                
            </div>
        </div>
    </section>

    <!-- Section A propos -->
    <section class="success" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>A propos</h2>
                    <hr class="star-light">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-2">
                    <p>Féru d'informatique depuis mon adolescence, la programmation web s'est révelé rapidement comme une passion pour moi depuis l'année 2016. L'apprentissage ne me fait peur, et on peut me qualifier de : "Hard Learner"</p>
                </div>
                <div class="col-lg-4">
                    <p>Aujourd'hui en formation sur la plateforme OpenclassRooms, je compte décrocher mon Bac +3 en développement d'application -PHP/Symfony. Plus j'acquière des compétences, plus j'ai envie d'en découvrir.</p>
                </div>
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <a href="app/public/Bootstrap/img/CV.pdf" class="btn btn-lg btn-outline">
                        <i class="fa fa-file-pdf-o"></i> Voir mon C.V
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Contactez-moi</h2>
                    <hr class="star-primary">
                    <?php FLASH::flash(); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19. -->
                    <!-- The form should work on most web servers, but if the form is not working you may need to configure your web server differently. -->
                    <form name="sentMessage" id="contactForm" method="post" action="contact">
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Nom</label>
                                <input type="text" class="form-control" placeholder="Nom*" id="name" name="name" required data-validation-required-message="Entrez votre nom S.V.P." value="<?= isset(htmlspecialchars($_POST['name'])) ? htmlspecialchars($_POST['name']) : '' ?>">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Prénom</label>
                                <input type="text" class="form-control" placeholder="Prénom*" id="lastname" name="lastname" required data-validation-required-message="Entrez votre prénom S.V.P." value="<?= isset(htmlspecialchars($_POST['lastname'])) ? htmlspecialchars($_POST['lastname']) : '' ?>">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="Email*" id="email" name="email" required data-validation-required-message="Entrez votre adresse mail S.V.P." value="<?= isset(htmlspecialchars($_POST['email'])) ? htmlspecialchars($_POST['email']) : '' ?>">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Message*</label>
                                <textarea rows="3" class="form-control" placeholder="Message*" id="message" name="message" required data-validation-required-message="Entrez un message S.V.P." value="<?= isset(htmlspecialchars($_POST['message'])) ? htmlspecialchars($_POST['message'] ): '' ?>"></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <br>
                        <div id="success"></div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <button type="submit" class="btn btn-success btn-lg">Envoyer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
