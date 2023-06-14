const isImage = (file) => Boolean(file && file.type !== undefined && file.type.startsWith('image/'));
const isValidString = (value) => /^[a-zA-Z0-9\s]+$/.test(value);
const isNumber = (value) => typeof value === 'number' || (value !== '' && typeof value === 'string' && !isNaN(Number(value)));
const isValidDateFormat = (value) => {
    const date = new Date(value);
    return date instanceof Date && !isNaN(date) && value === date.toISOString().slice(0, 10);
}

function countValid(array) {
    return array.reduce((count, bool) => {
        if (bool) return count;
        return count + 1;
    }, 0);
}

function displayInvalidForms(button, inputs, checks) {
    button.removeAttribute('disabled');

    inputs.forEach((input, index) => {
        if (!checks[index]) {
            input.classList.add('is-invalid');
        }
    });
}

function fileToBase64(image) {
    return new Promise((resolve) => {
        const reader = new FileReader();

        reader.onload = (e) => {
            imageData = e.target.result;
            resolve(imageData);
        };

        reader.readAsDataURL(image);
    });
}

const addFormInputs = document.querySelectorAll('#add-form input');
const addButton = document.querySelector('#add-btn');
addButton.addEventListener('click', async () => {
    addFormInputs.forEach(form => form.classList.remove('is-invalid'));
    addButton.setAttribute('disabled', '');

    const productName = document.querySelector('#prod-name').value;
    const unit = document.querySelector('#unit').value;
    const price = document.querySelector('#price').value;
    const expiryDate = document.querySelector('#exp-date').value;
    const availableInv = document.querySelector('#available-inv').value;
    const file = document.querySelector('#image').files[0];

    const checks = [
        isValidString(productName),
        isValidString(unit),
        isNumber(price),
        isValidDateFormat(expiryDate),
        isNumber(availableInv),
        isImage(file)
    ];

    // convert image to base64 string
    await fileToBase64(file)
        .then((promise) => {
            const fileData = promise;
            
            if (countValid(checks) > 0) {
                displayInvalidForms(addButton, addFormInputs, checks);
            } else {
                fetch('backend/create.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        product_name: productName,
                        unit: unit,
                        price: price,
                        expiry_date: expiryDate,
                        available_inv: availableInv,
                        image_data: fileData
                    })
                })
                    .then((response) => response.json())
                    .then((data) => {
                        console.log(data);
                        if (data.statusCode === 200) {
                            // close moddal
                            const addModal = bootstrap.Modal.getInstance(document.querySelector('#add'));
                            addModal.toggle();
                            // clear form
                            document.querySelector('#add-form').reset();
                            // reload window to see changes on table
                            window.location.reload();
                        }
                    })
                    .catch((error) => console.error(error));
            }
        })
        .catch(() => {
            displayInvalidForms(addButton, addFormInputs, checks);
        });
});

const viewButtons = document.querySelectorAll('.view-menu-btn');
viewButtons.forEach((viewButton) => {
    viewButton.addEventListener('click', (e) => {
        fetch('backend/read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: e.target.value
            })
        })
            .then((response) => response.json())
            .then((data) => {
                const details = data[0];

                // display image
                const img = document.createElement('img');
                img.src = details['image'];
                document.querySelector('#image-display').innerHTML = ''; // remove previous image
                document.querySelector('#image-display').appendChild(img);

                // display details
                document.querySelector('#prod-name-detail').textContent = details['product_name'];
                document.querySelector('#unit-detail').textContent = details['unit'];
                document.querySelector('#price-detail').textContent = details['price'];
                document.querySelector('#exp-date-detail').textContent = details['date_of_expiry'];
                document.querySelector('#avail-inv-detail').textContent = details['available_inventory'];
                document.querySelector('#avail-inv-cost-detail').textContent = details['available_inventory_cost'];
            })
            .catch((error) => console.error(error));
    });
});

let ogImgData;
const updateMenuButtons = document.querySelectorAll('.update-menu-btn');
updateMenuButtons.forEach((updateButton) => {
    updateButton.addEventListener('click', (e) => {
        fetch('backend/read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: e.target.value
            })
        })
            .then((response) => response.json())
            .then((data) => {
                const details = data[0];

                const img = document.createElement('img');
                img.src = details['image'];
                document.querySelector('#image-display').innerHTML = '';
                document.querySelector('#image-display').appendChild(img);

                ogImgData = details['image']; // store data for comparison later if it's changed
                document.querySelector('#update-btn').value = details['id'];
                document.querySelector('#prod-name-update').value = details['product_name'];
                document.querySelector('#unit-update').value = details['unit'];
                document.querySelector('#price-update').value = details['price'];
                document.querySelector('#exp-date-update').value = details['date_of_expiry'];
                document.querySelector('#avail-inv-update').value = details['available_inventory'];
            })
            .catch((error) => console.error(error));
    });
});

function update(productName, unit, price, expiryDate, availableInv, fileData) {
    const id = document.querySelector('#update-btn').value;

    fetch('backend/update.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id,
            product_name: productName,
            unit: unit,
            price: price,
            expiry_date: expiryDate,
            available_inv: availableInv,
            image_data: fileData
        })
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.statusCode === 200) {
                const updateModal = bootstrap.Modal.getInstance(document.querySelector('#update'));
                updateModal.toggle();
                document.querySelector('#update-form').reset();
                window.location.reload();
            }
        })
        .catch((error) => console.error(error));
}

const updateFormInputs = document.querySelectorAll('#update-form input');
const updateButton = document.querySelector('#update-btn');
updateButton.addEventListener('click', async () => {
    updateButton.setAttribute('disabled', '');

    const productName = document.querySelector('#prod-name-update').value;
    const unit = document.querySelector('#unit-update').value;
    const price = document.querySelector('#price-update').value;
    const expiryDate = document.querySelector('#exp-date-update').value;
    const availableInv = document.querySelector('#avail-inv-update').value;
    const file = document.querySelector('#image-update').files[0];

    const checks = [
        isValidString(productName),
        isValidString(unit),
        isNumber(price),
        isValidDateFormat(expiryDate),
        isNumber(availableInv),
        isImage(file)
    ];

    let fileData;
    if (file === undefined) {
        // save original image data if no image is submitted
        fileData = ogImgData;
        checks[5] = true;

        if (countValid(checks) > 0) {
            displayInvalidForms(updateButton, updateFormInputs, checks);
        } else {
            update(productName, unit, price, expiryDate, availableInv, fileData)
        }
    } else {
        // process submitted file
        await fileToBase64(file)
            .then((promise) => {
                fileData = promise;
                update(productName, unit, price, expiryDate, availableInv, fileData)
            });
    }
});

const deleteButtons = document.querySelectorAll('.delete-menu-btn');
deleteButtons.forEach((deleteButton) => {
    deleteButton.addEventListener('click', (e) => {
        document.querySelector('#delete-btn').value = e.target.value;
    });
});

const deleteButton = document.querySelector('#delete-btn');
deleteButton.addEventListener('click', () => {
    fetch('backend/delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: document.querySelector('#delete-btn').value,
        })
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.statusCode === 200) {
                const deleteModal = bootstrap.Modal.getInstance(document.querySelector('#delete'));
                deleteModal.toggle();
                window.location.reload();
            }
        })
        .catch((error) => console.error(error));
});
