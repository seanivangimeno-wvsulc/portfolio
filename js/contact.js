document.addEventListener('DOMContentLoaded', function () {
  initContactForm();
  initCharacterCounts();
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

    var formStatus = document.getElementById('form-status');
    formStatus.style.display = 'none';

    fields.forEach(function (field) {
      clearFieldError(field, document.getElementById(field.id + '-error'));
    });

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

    var submitBtn = form.querySelector('.contact__submit');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Sending...';

    var formData = new FormData(form);

    fetch('contact.php', {
      method: 'POST',
      body: formData
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
      if (data.success) {
        showFormStatus('success', data.message);
        form.reset();
        initCharacterCounts();
      } else {
        for (var key in data.errors) {
          var field = document.getElementById(key);
          var errEl = document.getElementById(key + '-error');
          if (field && errEl) {
            showFieldError(field, errEl, data.errors[key]);
          }
        }
        showFormStatus('error', 'Please fix the errors before submitting.');
      }
    })
    .catch(function () {
      showFormStatus('error', 'Something went wrong. Please try again later.');
    })
    .finally(function () {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Send Message \u2192';
    });
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
}

function initCharacterCounts() {
  var messageField = document.getElementById('message');
  if (!messageField) return;

  var countEl = document.getElementById('char-count');
  if (!countEl) return;

  var max = messageField.getAttribute('maxlength') || 1000;

  function updateCount() {
    var len = messageField.value.length;
    countEl.textContent = len + ' / ' + max;
    countEl.classList.toggle('over', len > max * 0.9);
  }

  updateCount();
  messageField.addEventListener('input', updateCount);
}
