    <div class='mb-3 text-center' style='color: #783ff1'>
    <?php 
      if (!isset($_SESSION['userId'])) {
        echo "Login is required to write";		
      } 
    ?>