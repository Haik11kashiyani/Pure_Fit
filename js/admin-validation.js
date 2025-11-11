const AdminValidation = {
    showAlert: function(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas fa-${icon} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        if ($('.alert-container').length) {
            $('.alert-container').html(alertHtml);
        } else {
            $('main').prepend('<div class="alert-container">' + alertHtml + '</div>');
        }
        
        $('html, body').animate({ scrollTop: 0 }, 300);
        
        setTimeout(() => {
            $('.alert').fadeOut(300, function() { $(this).remove(); });
        }, 5000);
    },

    messages: {
        addSuccess: 'Added successfully',
        updateSuccess: 'Updated successfully',
        deleteSuccess: 'Deleted successfully',
        error: 'An error occurred. Please try again',
        required: 'All required fields must be filled',
        invalidEmail: 'Please enter a valid email address',
        invalidPhone: 'Please enter a valid phone number',
        passwordMismatch: 'Passwords do not match',
        confirmDelete: 'Are you sure you want to delete this item?'
    },

    validateRequired: function(formId) {
        let isValid = true;
        $(formId + ' [required]').each(function() {
            if (!$(this).val().trim()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        return isValid;
    },

    confirmAction: function(message = null) {
        return confirm(message || this.messages.confirmDelete);
    },

    initPasswordToggle: function() {
        $('input[type="password"]').each(function() {
            const $input = $(this);
            if (!$input.next('.password-toggle').length) {
                $input.wrap('<div class="position-relative"></div>');
                $input.after(`
                    <span class="password-toggle position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10;">
                        <i class="fas fa-eye text-muted"></i>
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
    AdminValidation.initPasswordToggle();
    
    $('form').on('submit', function() {
        $(this).find('.is-invalid').removeClass('is-invalid');
    });
});
