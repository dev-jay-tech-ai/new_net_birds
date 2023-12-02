<?php
  echo '
  <ul class="nav navbar-nav d-flex location justify-content-center mb-3 w-100">               
    <li><a href="' . $code . '.php">All</a></li>        
    <li><a href="' . $code . '.php?location=london">London</a></li>        
    <li><a href="' . $code . '.php?location=manchester">Manchester</a></li>       
    <li><a href="' . $code . '.php?location=glasgow">Glasgow</a></li> 
    <li><a href="' . $code . '.php?location=nottingham">Nottingham</a></li>        
    <li><a href="' . $code . '.php?location=birmingham">Birmingham</a></li>        
    <li><a href="' . $code . '.php?location=others">Others</a></li>    
  </ul>';
?>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const locationLinks = document.querySelectorAll('.location.nav.navbar-nav li a');
    function updateSelectedClass() {
      const currentLocation = window.location.search.split('=')[1];
      locationLinks.forEach(link => {
        link.classList.remove('selected');
        if((!currentLocation && link.getAttribute('href') === '<?php echo $code; ?>.php') || 
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