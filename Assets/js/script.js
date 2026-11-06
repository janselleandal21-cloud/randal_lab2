
document.addEventListener("DOMContentLoaded", function () {

    // ==========================================
    // 1. REMOVE MOVIE CONFIRMATION
    // ==========================================

    const removeButtons = document.querySelectorAll(".remove-item");

    removeButtons.forEach(function (button) {

        button.addEventListener("click", function (event) {

            event.preventDefault();

            const form = button.closest("form");

            if (!form) {
                return;
            }

            Swal.fire({
                title: "Remove Movie?",
                text: "Are you sure you want to remove this movie from your cart?",
                icon: "warning",
                background: "#111a2b",
                color: "#ffffff",
                showCancelButton: true,
                confirmButtonText: "Yes, Remove",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#e8b85b",
                cancelButtonColor: "#263b5c",
                reverseButtons: true,
                customClass: {
                    popup: "cinema-swal-popup",
                    title: "cinema-swal-title",
                    confirmButton: "cinema-swal-confirm",
                    cancelButton: "cinema-swal-cancel"
                }
            }).then(function (result) {

                if (result.isConfirmed) {

                    // Submit the original form after confirmation
                    form.requestSubmit(button);

                }

            });

        });

    });


    // ==========================================
    // 2. PREVENT MULTIPLE CHECKOUT SUBMISSIONS
    // ==========================================

    const forms = document.querySelectorAll(".checkout-form");

    forms.forEach(function (form) {

        form.addEventListener("submit", function () {

            const submitButton = form.querySelector(
                'button[type="submit"], input[type="submit"]'
            );

            if (submitButton) {

                submitButton.disabled = true;

                if (submitButton.tagName === "BUTTON") {

                    submitButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';

                } else {

                    submitButton.value = "Processing...";

                }

            }

        });

    });


    // ==========================================
    // 3. PRINT RECEIPT
    // ==========================================

    const printButton = document.querySelector("#printReceipt");

    if (printButton) {

        printButton.addEventListener("click", function () {

            window.print();

        });

    }


    // ==========================================
    // 4. SWEETALERT SYSTEM NOTIFICATIONS
    // ==========================================

    const alerts = document.querySelectorAll(".alert");

    alerts.forEach(function (alert) {

        let icon = "info";

        if (alert.classList.contains("alert-success")) {

            icon = "success";

        } else if (alert.classList.contains("alert-danger")) {

            icon = "error";

        } else if (alert.classList.contains("alert-warning")) {

            icon = "warning";

        }

        Swal.fire({
            text: alert.textContent.trim(),
            icon: icon,
            toast: true,
            position: "top-end",
            background: "#111a2b",
            color: "#ffffff",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });

        // Remove the original Bootstrap alert
        alert.remove();

    });

});
