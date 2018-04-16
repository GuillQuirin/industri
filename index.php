<div class="col-sm-12 padding-top-20">
<div class="row">
<div>
<img class="img-logo" style="width: 10%;" 
src="https://scontent-fra3-1.xx.fbcdn.net/v/t1.0-9/552345_420640654657180_1666928990_n.jpg?oh=7e0262fb4fa4671e45c13bfefcbfc4ef&oe=58C27523" 
alt="logo">
</div>
<div class="col-xs-10 col-xs-offset-1 text-center">
<?php 
/**
 * Recipe class file
 *
 * PHP Version 5.2
 *
 * @category Recipe
 * @package  Recipe
 * @author   Lorna Jane Mitchell <lorna@ibuildings.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://example.com/recipes
 */
if(isset($competition)) : ?>
<h1>CONCOURS <br> <?php echo $competition->getName(); ?></h1>
<p>Organisé
du <?php echo date('d/m/Y', strtotime($competition->getStart_date())); ?>
au <?php echo date('d/m/Y', strtotime($competition->getEnd_date())); ?>.
</p>
<?php else : ?>
<h1>Pas de concours ouvert actuellement</h1>
<?php endif; ?>
</div>
</div>

<div class="row">
<div class="col-xs-10 col-xs-offset-1 text-center">
<?php if(isset($competition)) :?>
<hr>
<p><h3><?php echo $competition->getDescription(); ?></h3></p>
<hr>
<h2>TENTEZ DE GAGNER <br> <?php echo $competition->getPrize(); ?></h2>
<?php 
if($competition->getUrl_prize()!==null)
    echo "<div class='col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3'><img class='img-responsive' src='".$competition->getUrl_prize()."' alt='photo du prix'></div>";
?>
<?php endif; ?>
</div>
</div>
<input type="hidden" id="isConnected" 
value="<?php echo (isset($_SESSION['ACCESS_TOKEN'])) ? 1 : 0; ?>">
<?php if(!isset($_SESSION['idFB'])) : ?>
<div class="row">
<h4><p class="col-sm-8 col-sm-offset-2 text-center">
Connectez-vous à l'application et admirez les chefs d'oeuvres 
que vous réservent les autres participants !</p></h4>
</div>
<?php endif; ?>
<div class="row">
<div class="col-xs-10 col-xs-offset-1">
<?php if(isset($user)) :  //Utilisateur connecté ?>
<div class="col-xs-6 col-sm-6 col-md-6">
<button class="btn" id="logout">
Bienvenue <?php echo $user->getFirst_name(); ?><br>
Se déconnecter
</button>
</div>
<div class="col-xs-6 col-sm-6 col-md-6">
<a href="<?php echo WEBPATH.'/gallery'; ?>">
<button class="btn">
Accèder aux photos des participants
</button>
</a>
</div>
<?php else : //Visiteur non connecté ?>
<div class="col-sm-6 col-sm-offset-3">
<button class="btn" id="login">Se connecter !</button>
</div>
<?php endif; ?>
</div>
</div>
<?php if(isset($competition)) : ?>

<div class="row">
<div class="col-xs-10 col-xs-offset-1 text-center">

<?php 
$listAlonePic = [];
if(isset($images)) :?>


<?php if(isset($canParticipate) && $canParticipate) : ?>

<p><h3>Nous vous remercions d'avoir participé au concours, 
le gagnant sera désigné à la fin de celui-ci.</h3></p>

<?php else: ?>
<h2>Participez à notre concours</h2>
<h3>en sélectionnant une photo d'un de vos albums Facebook....</h3>
<div class="panel-group listPictures"" 
id="accordion" role="tablist" aria-multiselectable="true">

<?php 
//Photos individuelles
if(isset($images["photos"])) :
?>

<div class="panel panel-default col-md-6 col-md-offset-3">
<a role="button" data-toggle="collapse" 
data-parent="#accordion" href="#collapseOne" 
aria-expanded="false" aria-controls="collapseOne">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
Photos individuelles
</h4>
</div>
</a>
<div id="collapseOne" class="panel-collapse collapse" 
role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">
<div class="row">
<?php
foreach ($images["photos"]["data"] as $key => $photo) :?>
<div class="col-xs-6 col-md-4">
<a class="thumbnail cursor-pointer">
<img src='<?php echo $photo['source']; ?>' 
data-toggle='modal' 
data-target='<?php echo "#".$photo['id']; ?>'
alt="Photo individuelle">
</a>
</div>

<!--Modal-->
<?php 
//Empêche d'avoir la meme image en individuel + album : bug de modal
$listAlonePic[] = $photo['id']; 
?>
<div class="modal fade" id='<?php echo $photo['id']; ?>' 
tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<form action="<?php echo WEBPATH.'/index/submit'; ?>" 
class="onlineForm" method="post">
<div class="modal-content">
<div class="modal-header">
<p>Assurez-vous d'être le propriétaire du contenu que vous envoyez.</p> 
<p>En participant au concours, 
vous acceptez les <a href="<?php echo WEBPATH.'/cgu'; ?>" 
class="cgu-link">conditions générales d'utilisations</a>.</p>
</div>
<div class="modal-body">
<img src='<?php echo $photo['source']; ?>' alt="photo de l'utilisateur">
<input type="hidden" name="idPhoto" value="<?php echo $photo['id']; ?>">
<input type="hidden" name="fromFB">
</div>
<div class="modal-footer">
<div class="col-md-12">
<?php if(isset($cantPublish) && $cantPublish) : ?>
<p>Vous pouvez autoriser Facebook à enregistrer vos photos 
dans un album administrable afin de pouvoir y intégrer un message.</p>
<button class="postPhotos">Autoriser</button>
<?php else: ?>
<p>Rédigez un message personnalisé pour vos amis qui consulteraient votre photo.</p>
<textarea class="col-xs-12 col-md-12" name="message"></textarea>
<?php endif; ?>
</div>
<div class="col-sm-6 col-md-6">
<button type="button" class="btn" data-dismiss="modal">Annuler</button>
</div>
<div class="col-sm-6 col-md-6">
<button type="submit" class="btn sendPicture">Envoyer cette photo</button>
</div>
<div class="errorSend text-left">
<p>Attention, certaines informations sont nécessaires 
pour finaliser votre participation :</p>
<ul class="listError">
</ul>
<p>Vous pouvez modifier vos autorisations à Facebook en cliquant 
<a href=""><button>ici</button></a></p>
</div>
</div>
</div>
</form>
</div>
</div>
<?php 
endforeach;
?>
</div>
<div class="row">
<div class="col-md-12">
<a class="backTop" role="button" data-toggle="collapse" 
data-parent="#accordion" href="#collapseOne" 
aria-expanded="false" aria-controls="collapseOne">
<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
</a>
</div>
</div>
</div>
</div>
</div>
<?php else: ?>
<p>Vous devez autoriser Facebook à récupérer les photos de vos albums</p>
<div class="col-sm-6 col-sm-offset-3">
<button class="btn getPhotos">Autoriser</button>
</div>
<?php
endif;
?>
</div>

<div class="row">
<div class="col-xs-10 col-xs-offset-1 col-md-6 col-md-offset-3 text-center">
<h3>....ou en important une photo depuis votre ordinateur.</h3>



<p>Vous devez autoriser Facebook à enregistrer 
vos photos dans un album que vous pourrez ensuite administrer</p>
<button class="btn col-sm-6 col-sm-offset-3 postPhotos">Autoriser</button>

</div>
</div>
<?php
endif;
endif;
?>
</div>
</div>
<?php endif; ?>
</div>