<!-- Barre inférieure avec les contrôles -->


<footer class="bar bar-above-the-bottom-bar">
</footer>

<footer class="bar bottom-bar">

    <!-- Croix directionnelle -->
    <div class="dpad">
      <a href="index.php" class="btn up">home</a>
      <a href="contacts.php" class="btn down">contact</a>
      <a href="hobbies.php" class="btn left">hobbies</a>
      <a href="about.php" class="btn right">about</a>
    </div>

    <!-- Boutons start -->
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

    <div class="btns-ab">
      <!-- Boutons a -->
      <a href="skills.php" class="btn button-a">compétences</a>
      <!-- Boutons b -->
      <a href="projects.php" class="btn button-b">projets</a>
    </div>


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
<script>
  function updateCenterHeight() {
    const topBarHeight = 90;
    const bottomBarHeight = window.innerWidth * 0.13;
    const availableHeightDivInsideContentCenter = window.innerHeight - topBarHeight - bottomBarHeight;
    const availableHeightContentCenter = availableHeightDivInsideContentCenter + 40 ;

    document.querySelector('.div-inside-content').style.minHeight = `${availableHeightDivInsideContentCenter}px`;
    document.querySelector('.content').style.minHeight = `${availableHeightContentCenter}px`;
  }

  window.addEventListener('resize', updateCenterHeight);
  window.addEventListener('DOMContentLoaded', updateCenterHeight);
</script>