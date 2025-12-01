const Validation = {
    messages: {
        required: 'This field is required',
        email: 'Please enter a valid email address',
        password: 'Password must be at least 6 characters',
        passwordMatch: 'Passwords do not match',
        phone: 'Please enter a valid phone number',
        username: 'Username must be at least 3 characters',
        success: 'Operation completed successfully',
        error: 'An error occurred. Please try again'
    },

    showMessage: function(type, message, container = '.alert-container') {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas fa-${icon} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $(container).html(alertHtml);
        $('html, body').animate({ scrollTop: 0 }, 300);
        
        setTimeout(() => {
            $('.alert').fadeOut(300, function() { $(this).remove(); });
        }, 5000);
    },

    validateEmail: function(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },

    validatePhone: function(phone) {
        const re = /^[0-9]{10}$/;
        return re.test(phone.replace(/[\s-]/g, ''));
    },

    validatePassword: function(password) {
        return password.length >= 6;
    },

    validateUsername: function(username) {
        return username.length >= 3;
    },

    validateForm: function(formId, rules) {
        let isValid = true;
        const errors = [];

        $(formId + ' .error-message').remove();
        $(formId + ' .is-invalid').removeClass('is-invalid');

        $.each(rules, (field, rule) => {
            const $field = $(formId + ' [name="' + field + '"]');
            const value = $field.val().trim();

            if (rule.required && !value) {
                isValid = false;
                this.showFieldError($field, this.messages.required);
                errors.push(field);
            } else if (value) {
                if (rule.email && !this.validateEmail(value)) {
                    isValid = false;
                    this.showFieldError($field, this.messages.email);
                    errors.push(field);
                }
                if (rule.password && !this.validatePassword(value)) {
                    isValid = false;
                    this.showFieldError($field, this.messages.password);
                    errors.push(field);
                }
                if (rule.phone && !this.validatePhone(value)) {
                    isValid = false;
                    this.showFieldError($field, this.messages.phone);
                    errors.push(field);
                }
                if (rule.username && !this.validateUsername(value)) {
                    isValid = false;
                    this.showFieldError($field, this.messages.username);
                    errors.push(field);
                }
                if (rule.match) {
                    const matchValue = $(formId + ' [name="' + rule.match + '"]').val();
                    if (value !== matchValue) {
                        isValid = false;
                        this.showFieldError($field, this.messages.passwordMatch);
                        errors.push(field);
                    }
                }
            }
        });

        return isValid;
    },

    showFieldError: function($field, message) {
        $field.addClass('is-invalid');
        
        // Check if the field is inside an input-group with password toggle
        const $inputGroup = $field.closest('.input-group');
        const errorMessage = `<div class="error-message text-danger small mt-1" style="font-size: 0.875rem; margin-top: 0.25rem; margin-bottom: 0.5rem; display: block; width: 100%;">${message}</div>`;
        
        if ($inputGroup.length > 0) {
            // Add error message after the entire input-group
            $inputGroup.after(errorMessage);
        } else {
            // Add error message after the field (default behavior)
            $field.after(errorMessage);
        }
    },

    initPasswordToggle: function() {
        $('input[type="password"]').each(function() {
            const $input = $(this);
            if (!$input.next('.password-toggle').length) {
                $input.wrap('<div class="position-relative"></div>');
                $input.after(`
                    <span class="password-toggle position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                        <i class="fas fa-eye"></i>
                    </span>
                `);
            }
        });

        $(document).on('click', '.password-toggle', function() {
            const $toggle = $(this);
            const $input = $toggle.prev('input');
            const $icon = $toggle.find('i');

            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                $icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                $input.attr('type', 'password');
                $icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    }
};

$(document).ready(function() {
    Validation.initPasswordToggle();
    
    // Clear field errors when user starts typing
    $(document).on('input keydown change', '.form-control', function() {
        const $field = $(this);
        if ($field.hasClass('is-invalid')) {
            $field.removeClass('is-invalid');
            // Remove the error message associated with this field
            const $inputGroup = $field.closest('.input-group');
            if ($inputGroup.length > 0) {
                $inputGroup.next('.error-message').remove();
            } else {
                $field.next('.error-message').remove();
            }
        }
    });
});
