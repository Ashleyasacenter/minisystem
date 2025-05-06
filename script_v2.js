// Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add Violation Modal
    const addViolationBtn = document.getElementById('addViolationBtn');
    const addViolationModal = document.getElementById('addViolationModal');
    const addViolationClose = addViolationModal.querySelector('.close');

    if (addViolationBtn) {
        addViolationBtn.addEventListener('click', function() {
            addViolationModal.style.display = 'block';
        });

        addViolationClose.addEventListener('click', function() {
            addViolationModal.style.display = 'none';
        });
    }

    // Add Violation Type Modal
    const addViolationTypeBtn = document.getElementById('addViolationTypeBtn');
    const addViolationTypeModal = document.getElementById('addViolationTypeModal');
    const addViolationTypeClose = addViolationTypeModal ? addViolationTypeModal.querySelector('.close') : null;

    if (addViolationTypeBtn && addViolationTypeModal) {
        addViolationTypeBtn.addEventListener('click', function() {
            addViolationTypeModal.style.display = 'block';
        });
    }

    if (addViolationTypeClose) {
        addViolationTypeClose.addEventListener('click', function() {
            addViolationTypeModal.style.display = 'none';
        });
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });

    // Close the add violation modal and reload page after form submission
    const addViolationForm = addViolationModal.querySelector('form');
    if (addViolationForm) {
        addViolationForm.addEventListener('submit', function(event) {
            // Allow form to submit normally, then close modal and reload page
            setTimeout(() => {
                addViolationModal.style.display = 'none';
                window.location.reload();
            }, 100);
        });
    }

    // Close the add violation type modal and reload page after form submission
    const addViolationTypeForm = addViolationTypeModal ? addViolationTypeModal.querySelector('form') : null;
    if (addViolationTypeForm) {
        addViolationTypeForm.addEventListener('submit', function(event) {
            setTimeout(() => {
                addViolationTypeModal.style.display = 'none';
                window.location.reload();
            }, 100);
        });
    }
});

// Other modal functions (viewViolation, editViolation) remain unchanged
function searchTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tr');

    input.addEventListener('keyup', function() {
        const filter = input.value.toLowerCase();

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            let found = false;

            for (let j = 0; j < row.cells.length; j++) {
                const cell = row.cells[j];
                if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }

            row.style.display = found ? '' : 'none';
        }
    });
}

function viewViolation(violationId) {
    if (!window.violations) return;
    const violation = window.violations.find(v => v.violation_id == violationId);
    if (!violation) return;

    const detailsDiv = document.getElementById('violationDetails');
    detailsDiv.innerHTML = `
        <p><strong>Student:</strong> ${violation.first_name} ${violation.last_name}</p>
        <p><strong>Course:</strong> ${violation.course}</p>
        <p><strong>Year Level:</strong> ${violation.year_level}</p>
        <p><strong>Violation Type:</strong> ${violation.violation_type}</p>
        <p><strong>Date:</strong> ${new Date(violation.violation_date).toLocaleDateString()}</p>
        <p><strong>Description:</strong> ${violation.description}</p>
        <p><strong>Reported By:</strong> ${violation.reported_by_name}</p>
        <p><strong>Status:</strong> ${violation.status}</p>
        <p><strong>Resolution Details:</strong> ${violation.resolution_details || 'N/A'}</p>
    `;

    // Store current student info for editing
    window.currentStudent = {
        student_id: violation.student_id,
        first_name: violation.first_name,
        last_name: violation.last_name,
        course: violation.course,
        year_level: violation.year_level,
        contact_number: violation.contact_number || '',
        email: violation.email || ''
    };

    const viewViolationModal = document.getElementById('viewViolationModal');
    viewViolationModal.style.display = 'block';

    // Add event listener to Edit Student Info button
    const editStudentBtn = document.getElementById('editStudentBtn');
    if (editStudentBtn) {
        editStudentBtn.onclick = function() {
            openEditStudentModal();
        };
    }
}

// Function to open and populate the Edit Student Modal
function openEditStudentModal() {
    if (!window.currentStudent) return;

    document.getElementById('edit_student_id').value = window.currentStudent.student_id;
    document.getElementById('edit_first_name').value = window.currentStudent.first_name;
    document.getElementById('edit_last_name').value = window.currentStudent.last_name;
    document.getElementById('edit_course').value = window.currentStudent.course;
    document.getElementById('edit_year_level').value = window.currentStudent.year_level;
    document.getElementById('edit_contact_number').value = window.currentStudent.contact_number;
    document.getElementById('edit_email').value = window.currentStudent.email;

    const editStudentModal = document.getElementById('editStudentModal');
    editStudentModal.style.display = 'block';
}

// Add event listener to close button of Edit Student Modal
document.addEventListener('DOMContentLoaded', function() {
    const editStudentModal = document.getElementById('editStudentModal');
    const editStudentClose = editStudentModal.querySelector('.close');
    if (editStudentClose) {
        editStudentClose.addEventListener('click', function() {
            editStudentModal.style.display = 'none';
        });
    }
});

function editViolation(violationId) {
    if (!window.violations) return;
    const violation = window.violations.find(v => v.violation_id == violationId);
    if (!violation) return;

    document.getElementById('update_violation_id').value = violation.violation_id;
    document.getElementById('status').value = violation.status;
    document.getElementById('resolution_details').value = violation.resolution_details || '';

    const updateViolationModal = document.getElementById('updateViolationModal');
    updateViolationModal.style.display = 'block';
}

        // JavaScript to handle Add Violation Type modal
        document.addEventListener('DOMContentLoaded', function () {
            var addBtn = document.getElementById('addViolationTypeBtn');
            var modal = document.getElementById('addViolationTypeModal');
            var closeBtns = modal.querySelectorAll('.close');

            addBtn.addEventListener('click', function () {
                modal.style.display = 'block';
            });

            closeBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    modal.style.display = 'none';
                });
            });

            window.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    
