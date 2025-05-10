<!-- loading.php -->
<div id="global-loader">
  <div class="loading-text">Chargement<span class="dots"></span></div>
</div>

<style>
  #global-loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #ffffff;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: Arial, sans-serif;
  }

  .loading-text {
      font-size: 1.5rem;
      color: #9046cf;
      font-weight: bold;
  }

  .dots::after {
      content: '';
      display: inline-block;
      animation: dots 1.5s steps(4, end) infinite;
  }

  @keyframes dots {
      0% { content: ''; }
      25% { content: '.'; }
      50% { content: '..'; }
      75% { content: '...'; }
      100% { content: ''; }
  }
</style>

<script>
  window.addEventListener("load", function () {
      const loader = document.getElementById("global-loader");
      if (loader) {
          loader.style.opacity = '0';
          setTimeout(() => loader.style.display = 'none', 1000);
      }
  });
</script>
