<?php
  echo '
  <ul class="nav navbar-nav d-flex location justify-content-center mb-3 w-100">               
    <li><a href="list.php?code=' . $code . '">All</a></li>        
    <li><a href="list.php?code=' . $code . '&location=london">伦敦</a></li>        
    <li><a href="list.php?code=' . $code . '&location=manchester">曼城</a></li>       
    <li><a href="list.php?code=' . $code . '&location=glasgow">格拉斯哥</a></li> 
    <li><a href="list.php?code=' . $code . '&location=nottingham">诺丁汉</a></li>        
    <li><a href="list.php?code=' . $code . '&location=birmingham">伯明翰</a></li>        
    <li><a href="list.php?code=' . $code . '&location=others">其他</a></li>    
  </ul>';
?>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const locationLinks = document.querySelectorAll('.location.nav.navbar-nav li a');
    function updateSelectedClass() {
      const currentLocation = window.location.search.split('location=')[1];
      locationLinks.forEach(link => {
        link.classList.remove('selected');
        if((!currentLocation && link.getAttribute('href') === 'list.php?code=<?php echo $code; ?>') || 
          (currentLocation && link.getAttribute('href').includes(currentLocation))) {
          link.classList.add('selected');
        }
      });
    }
    updateSelectedClass();
    locationLinks.forEach(link => {
      link.addEventListener('click', function() {
        locationLinks.forEach(otherLink => {
          otherLink.classList.remove('selected');
        });
        this.classList.add('selected');
        const newLocation = this.getAttribute('href');
        history.pushState(null, '', newLocation);
        window.location.href = newLocation;
      });
    });
  });
</script>