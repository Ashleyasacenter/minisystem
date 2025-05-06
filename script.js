// Toggle inline Add Student form visibility
document.addEventListener('DOMContentLoaded', function() {
    const addStudentBtn = document.getElementById('addStudentBtn');
    const addStudentFormContainer = document.getElementById('addStudentFormContainer');

    if (addStudentBtn && addStudentFormContainer) {
        addStudentBtn.addEventListener('click', function() {
            console.log('Add New Student button clicked');
            console.log('Current form display:', addStudentFormContainer.style.display);
            if (addStudentFormContainer.style.display === 'none' || addStudentFormContainer.style.display === '') {
                addStudentFormContainer.style.display = 'block';
                console.log('Form shown');
            } else {
                addStudentFormContainer.style.display = 'none';
                console.log('Form hidden');
            }
        });
    }

    // AJAX form submission for add student
    const addStudentForm = document.querySelector('#addStudentFormContainer form');
    if (addStudentForm) {
        addStudentForm.addEventListener('submit', function(event) {
            event.preventDefault();
            console.log('Add Student form submitted');

            const formData = new FormData(addStudentForm);
            formData.append('add_student', '1'); // Ensure add_student is sent

            fetch('students.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Fetch response received');
                return response.json();
            })
            .then(data => {
                console.log('Response JSON:', data);
                // Clear previous messages
                let errorDiv = document.querySelector('.alert-error');
                let successDiv = document.querySelector('.alert-success');
                if (errorDiv) errorDiv.remove();
                if (successDiv) successDiv.remove();

                if (data.success) {
                    // Show success message
                    const successMessage = document.createElement('div');
                    successMessage.className = 'alert alert-success';
                    successMessage.textContent = data.message || 'Student added successfully';
                    addStudentFormContainer.insertAdjacentElement('beforebegin', successMessage);

                    // Reset form and hide it
                    addStudentForm.reset();
                    addStudentFormContainer.style.display = 'none';

                    // Optionally reload the page to update the students list
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Show error message
                    const errorMessage = document.createElement('div');
                    errorMessage.className = 'alert alert-error';
                    errorMessage.textContent = data.error || 'Failed to add student';
                    addStudentFormContainer.insertAdjacentElement('beforebegin', errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
});
