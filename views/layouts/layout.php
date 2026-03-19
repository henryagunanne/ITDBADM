<!DOCTYPE html>
<html>
  <head>
    <title><?= $title ?? 'Cool Beans' ?></title>
    <link rel="stylesheet" href="../public/common/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Notable&family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
  </head>
  <body>
    <?= $body ?>
  </body>
</html>

<script>
    // search toggle
  const searchToggle = document.querySelector('.search-toggle');
  const searchInput = document.querySelector('.search-input');

  if (searchToggle && searchInput) {
      searchToggle.onclick = (e) => {
          e.preventDefault();
          searchInput.classList.toggle('active');
          if (searchInput.classList.contains('active')) {
              searchInput.focus();
          }
      };

      // close when clicking outside
      window.addEventListener('click', (e) => {
          if (!e.target.closest('.search-wrapper')) {
              searchInput.classList.remove('active');
          }
      });

      // search on enter
      searchInput.addEventListener('keypress', (e) => {
          if (e.key === 'Enter' && searchInput.value.trim()) {
              window.location.href = `/itdbadm-mp/views/beans.php?search=${encodeURIComponent(searchInput.value.trim())}`;
          }
      });
  }
</script>