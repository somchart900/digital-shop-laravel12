<!-- Floating Contact Button -->
<div class="contact-fab">
  <button id="contact-btn" class="fab-button">
    <i class="bi bi-chat-dots"></i>
    <i class="bi bi-x close-icon"></i>
  </button>
  <div class="contact-links" id="contact-links">
    <a href="{{ $line->value ?? 'https://line.me/ti/p/xxxxxxxx' }}" target="_blank" class="link-item" style="--color: #00C300;">
      <span class="link-icon"><i class="bi bi-line"></i></span>
      <span class="link-text">Line</span>
    </a>
    <a href="{{ $messenger->value ?? 'https://m.me/xxxxxxxx' }}" target="_blank" class="link-item" style="--color: #006AFF;">
      <span class="link-icon"><i class="bi bi-messenger"></i></span>
      <span class="link-text">Messenger</span>
    </a>
  </div>
</div>

<style>
  @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css');
  
  .contact-fab {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 9999;
    display: flex;
    flex-direction: column-reverse;
    align-items: flex-end;
    gap: 1rem;
  }

  .fab-button {
    background: linear-gradient(135deg, #040920, #0e2ca5);
    color: white;
    border: none;
    border-radius: 50%;
    width: 64px;
    height: 64px;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(74, 108, 247, 0.3);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
  }

  .fab-button .close-icon {
    position: absolute;
    opacity: 0;
    transform: rotate(-90deg);
    transition: all 0.3s ease;
  }

  .fab-button.active {
    transform: rotate(90deg);
    background: linear-gradient(135deg, #ff4d4d, #cc0000);
  }

  .fab-button.active .bi-chat-dots {
    opacity: 0;
    transform: rotate(-90deg);
  }

  .fab-button.active .close-icon {
    opacity: 1;
    transform: rotate(0deg);
  }

  .fab-button:hover {
    transform: scale(1.1);
  }

  .fab-button.active:hover {
    transform: rotate(90deg) scale(1.1);
  }

  .contact-links {
    display: flex;
    flex-direction: column-reverse;
    gap: 1rem;
    pointer-events: none;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
  }

  .contact-links.active {
    pointer-events: auto;
    opacity: 1;
    transform: translateY(0);
  }

  .link-item {
    display: flex;
    align-items: center;
    background: white;
    color: #333;
    text-decoration: none;
    padding: 0.75rem 1.5rem 0.75rem 0.75rem;
    border-radius: 50px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    transform: translateX(20px);
    opacity: 0;
  }

  .contact-links.active .link-item {
    transform: translateX(0);
    opacity: 1;
  }

  .link-item:nth-child(1) {
    transition-delay: 0.1s;
  }
  
  .link-item:nth-child(2) {
    transition-delay: 0.2s;
  }

  .link-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: var(--color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    font-size: 18px;
  }

  .link-text {
    font-family: 'Segoe UI', sans-serif;
    font-weight: 500;
    font-size: 14px;
  }

  .link-item:hover {
    transform: translateX(-5px) !important;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    color: var(--color);
  }
</style>

<script>
  const btn = document.getElementById('contact-btn');
  const links = document.getElementById('contact-links');

  btn.addEventListener('click', () => {
    btn.classList.toggle('active');
    links.classList.toggle('active');
  });
</script>