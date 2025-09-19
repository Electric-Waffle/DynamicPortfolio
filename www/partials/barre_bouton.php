<!-- Barre inférieure avec les contrôles -->



<footer class="bar bottom-bar">

    <!-- Croix directionnelle -->
    <div class="dpad">
      <a href="index.php" class="btn up">home</a>
      <a href="contacts.php" class="btn down">contact</a>
      <a href="hobbies.php" class="btn left">hobbies</a>
      <a href="about.php" class="btn right">about</a>
    </div>

    <!-- Boutons A & B -->
    <div class="buttons">

      <?php
      if ($mode == "Backoffice") {
        echo "<form method=\"post\" action=\"\">";
        echo "<input class=\"btn button-middle\" type=\"submit\" name=\"deconnexion\" value=\"select\">";
        echo "</form>";
      } else {
        echo "<form method=\"post\" action=\"\">";
        echo "<input class=\"btn button-middle\" type=\"submit\" name=\"connexion\" value=\"start\">";
        echo "</form>";
      }
      ?>
    </div>

    <a href="skills.php" class="btn button-a">compétences</a>
    <a href="projects.php" class="btn button-b">projets</a>

</footer>

<?php 
if (!empty($message_erreur)): 

  $type_message = "good-info";
  if (str_contains($message_erreur, 'ERREUR')) {
    $type_message = "bad-info";
  }

?>
  <div id="toast" class="<?php echo $type_message ; ?>"><?= htmlspecialchars($message_erreur) ?></div>
<?php endif; ?>
