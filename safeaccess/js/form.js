// Client-side validation & password strength
document.addEventListener('DOMContentLoaded', function(){
  const pwd = document.querySelector('input[name="password"]');
  const strengthBar = document.querySelector('.strength > i');
  const strengthText = document.querySelector('.strength-text');
  const form = document.querySelector('form');

  function scorePassword(s){
    let score = 0;
    if (!s) return 0;
    // length
    if (s.length >= 8) score += 1;
    if (s.length >= 12) score += 1;
    // variety
    if (/[a-z]/.test(s)) score += 1;
    if (/[A-Z]/.test(s)) score += 1;
    if (/[0-9]/.test(s)) score += 1;
    if (/[^A-Za-z0-9]/.test(s)) score += 1;
    return Math.min(score,6);
  }

  function updateStrength(){
    if (!pwd || !strengthBar) return;
    const s = scorePassword(pwd.value);
    const pct = Math.round((s/6)*100);
    strengthBar.style.width = pct + '%';
    let txt = 'Very weak';
    if (s >= 5) txt = 'Strong';
    else if (s >= 4) txt = 'Good';
    else if (s >= 2) txt = 'Weak';
    strengthText && (strengthText.textContent = txt);
  }

  if (pwd){
    pwd.addEventListener('input', updateStrength);
    updateStrength();
  }

  if (form){
    form.addEventListener('submit', function(e){
      const username = form.querySelector('input[name="username"]').value || '';
      const email = form.querySelector('input[name="email"]');
      if (username.length < 3){
        e.preventDefault(); alert('Username must be at least 3 characters'); return;
      }
      if (email && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email.value)){
        e.preventDefault(); alert('Please enter a valid email'); return;
      }
    });
  }
});