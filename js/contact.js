document.addEventListener('DOMContentLoaded', function () {
  initContactForm();
  initCharacterCounts();
  showToast('success', 'Welcome! Feel free to reach out.');
});

function initContactForm() {
  var form = document.getElementById('contact-form');
  if (!form) return;

  var fields = form.querySelectorAll('.input');
  fields.forEach(function (field) {
    field.addEventListener('blur', function () {
      validateField(field);
    });
    field.addEventListener('input', function () {
      if (field.classList.contains('error')) {
        validateField(field);
      }
    });
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var isValid = true;

    fields.forEach(function (field) {
      if (!validateField(field)) {
        isValid = false;
      }
    });

    if (!isValid) {
      showFormStatus('error', 'Please fix the errors before submitting.');
      return;
    }

    submitForm(form);
  });
}

function validateField(field) {
  var value = field.value.trim();
  var errorEl = document.getElementById(field.id + '-error');
  var isValid = true;

  if (field.hasAttribute('required') && !value) {
    showFieldError(field, errorEl, 'This field is required');
    isValid = false;
  } else if (field.type === 'email' && value) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
      showFieldError(field, errorEl, 'Please enter a valid email address');
      isValid = false;
    }
  } else if (value && field.id === 'subject' && value.length < 3) {
    showFieldError(field, errorEl, 'Subject must be at least 3 characters');
    isValid = false;
  } else if (value && field.id === 'message' && value.length < 10) {
    showFieldError(field, errorEl, 'Message must be at least 10 characters');
    isValid = false;
  }

  if (isValid) {
    clearFieldError(field, errorEl);
  }

  return isValid;
}

function showFieldError(field, errorEl, message) {
  field.classList.add('error');
  if (errorEl) {
    errorEl.textContent = message;
    errorEl.classList.add('show');
  }
}

function clearFieldError(field, errorEl) {
  field.classList.remove('error');
  if (errorEl) {
    errorEl.textContent = '';
    errorEl.classList.remove('show');
  }
}

function showFormStatus(type, message) {
  var status = document.getElementById('form-status');
  if (!status) return;

  status.className = 'contact__form-status ' + type;
  status.textContent = message;
  status.style.display = 'block';

  setTimeout(function () {
    status.style.display = 'none';
  }, 5000);
}

function submitForm(form) {
  var submitBtn = form.querySelector('.contact__submit');
  var originalText = submitBtn.innerHTML;

  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner"></span> Sending...';

  var formData = new FormData(form);

  var statusEl = document.getElementById('form-status');
  statusEl.className = 'contact__form-status success';
  statusEl.textContent = 'Thanks! Your message has been sent successfully.';
  statusEl.style.display = 'block';

  setTimeout(function () {
    submitBtn.disabled = false;
    submitBtn.innerHTML = originalText;
    form.reset();
    setTimeout(function () {
      statusEl.style.display = 'none';
    }, 5000);
  }, 1500);
}

function initCharacterCounts() {
  var messageField = document.getElementById('message');
  if (!messageField) return;

  var countEl = document.getElementById('char-count');
  if (!countEl) return;

  var max = messageField.getAttribute('maxlength') || 1000;

  messageField.addEventListener('input', function () {
    var len = messageField.value.length;
    countEl.textContent = len + ' / ' + max;
    countEl.classList.toggle('over', len > max * 0.9);
  });
}

function showToast(type, message) {
  var existing = document.querySelector('.toast');
  if (existing) existing.remove();

  var toast = document.createElement('div');
  toast.className = 'toast toast--' + type;
  toast.innerHTML =
    '<span>' + message + '</span>' +
    '<button class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">&times;</button>';

  document.body.appendChild(toast);

  setTimeout(function () {
    if (toast.parentElement) toast.remove();
  }, 4000);
}
