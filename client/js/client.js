
document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById('sidebar');
  const closeBtn = document.getElementById('close-btn');
  const openBtn = document.getElementById('open-btn');
  const openBtnContainer = document.getElementById('open-btn-container');

  if (sidebar) {
    sidebar.classList.add('closed'); // Default to closed on page load
    if (openBtnContainer) openBtnContainer.style.display = 'block';
    if (closeBtn) closeBtn.style.display = 'none';
  }

  if (closeBtn && openBtn && sidebar && openBtnContainer) {
    closeBtn.addEventListener('click', () => {
      sidebar.classList.remove('opened');
      sidebar.classList.add('closed');
      openBtnContainer.style.display = 'block';
      closeBtn.style.display = 'none';
    });

    openBtn.addEventListener('click', () => {
      sidebar.classList.remove('closed');
      sidebar.classList.add('opened');
      openBtnContainer.style.display = 'none';
      closeBtn.style.display = 'inline-block';
    });
  }
});
