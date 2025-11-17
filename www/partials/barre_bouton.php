<!-- Barre inférieure avec les contrôles -->


<footer class="bar bar-above-the-bottom-bar">
</footer>

<footer class="bar bottom-bar">

    <!-- Croix directionnelle -->
    <div class="dpad">

      <div class="dpad-form-1"></div>
      <div class="dpad-form-2"></div>
      <div class="dpad-circle"></div>

      <a href="index.php" class="dpad-arrow dpad-arrow-up btn up"><span>🏠</span></a>
      <a href="contacts.php" class="dpad-arrow dpad-arrow-right btn down"><span>✉️</span></a>
      <a href="hobbies.php" class="btn left dpad-arrow dpad-arrow-left"><span>🎮</span></a>
      <a href="about.php" class="dpad-arrow dpad-arrow-down btn right"><span>ℹ️</span></a>

    </div>

    <!-- Boutons start -->
    <div style="display : flex; gap:141px">

      <div class="buttons button-start-select">
        <div class="button-select-top">
          <a href="veille.php" class=""><span>📡</span></a>
        </div>
      </div>

      <div class="buttons button-start-select">
        <?php
        if ($mode == "Backoffice") {
        ?>

          <div class="button-select-top">
            <form method="post" action="">
              <input class="btn" type="submit" name="deconnexion" value="🛠️">
            </form>
          </div>

        <?php 
        }
        else 
        {
        ?>

          <div class="button-select-top">
            <form method="post">
              <input class="btn" type="submit" name="connexion" value="🧰">
            </form>
          </div>

        <?php 
        }
        ?>
      </div>
        
    </div>

    <div class="buttons btns-ab-background">
      <!-- Boutons a -->
      <div class="btns-ab btn-a">
        <div class="button-top">
          <a href="skills.php" class="btn button-a">📚</a>
        </div>
      </div>
      
      
      <!-- Boutons b -->
      <div class="btns-ab btn-b">
        <div class="button-top">
          <a href="projects.php" class="btn button-b">🚧</a>
        </div>
      </div>
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