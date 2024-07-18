document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');
    
    var addButton = document.getElementById('show-add-car-form');
    if (addButton) {
        console.log('Add button found');
        addButton.addEventListener('click', function() {
            var form = document.getElementById('add-car-form');
            if (form.style.display === 'none' || form.style.display === '') {
                console.log('Form is currently hidden. Showing form.');
                form.style.display = 'block';
            } else {
                console.log('Form is currently visible. Hiding form.');
                form.style.display = 'none';
            }
        });
    } else {
        console.error('Add button not found');
    }

    document.querySelectorAll('.car-row').forEach(row => {
        row.addEventListener('click', function() {
            const carId = this.dataset.carId;
            const detailsRow = document.querySelector(`.car-details[data-car-id="${carId}"]`);
            if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                detailsRow.style.display = 'table-row';
            } else {
                detailsRow.style.display = 'none';
            }
        });
    });

    document.querySelectorAll('.rent-car').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const carId = this.dataset.carId;
            rentCar(carId);
        });
    });

    document.querySelectorAll('.release-car').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation(); 
            const carId = this.dataset.carId;
            releaseCar(carId);
        });
    });

    function rentCar(carId) {
        fetch('rent_car.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ car_id: carId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Car rented successfully!');
                location.reload();
            } else {
                alert('Failed to rent car.');
            }
        });
    }

    function releaseCar(carId) {
        fetch('release_car.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ car_id: carId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Car released successfully!');
                location.reload();
            } else {
                alert('Failed to release car.');
            }
        });
    }
});
