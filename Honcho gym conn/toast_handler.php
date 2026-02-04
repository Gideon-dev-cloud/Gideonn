<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="liveToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastMessage">
        Action Successful!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<script>
    // Check URL parameters for messages
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    const type = urlParams.get('type'); // 'success' or 'danger'

    if (msg) {
        const toastEl = document.getElementById('liveToast');
        const toastBody = document.getElementById('toastMessage');
        
        // Set Message
        toastBody.innerText = msg;
        
        // Change Color based on type
        if(type === 'error') {
            toastEl.classList.remove('bg-success');
            toastEl.classList.add('bg-danger');
        }

        // Show Toast
        const toast = new bootstrap.Toast(toastEl);
        toast.show();

        // Clean URL so refresh doesn't show toast again
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>