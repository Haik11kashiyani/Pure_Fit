function validateUser() {
    let name = document.getElementById('userName').value.trim();
    let email = document.getElementById('userEmail').value.trim();
    let password = document.getElementById('userPassword').value.trim();
    if (name === "" || email === "" || password === "") {
        alert("All user fields are required");
        return false;
    }
    return true;
}

function validateProduct() {
    let name = document.getElementById('productName').value.trim();
    let price = document.getElementById('productPrice').value.trim();
    let category = document.getElementById('productCategory').value.trim();
    if (name === "" || price === "" || category === "") {
        alert("Please fill all product details");
        return false;
    }
    return true;
}

function validateSettings() {
    let siteName = document.getElementById('siteName').value.trim();
    let email = document.getElementById('adminEmail').value.trim();
    if (siteName === "" || email === "") {
        alert("Site Name and Email are required");
        return false;
    }
    return true;
}

    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
    }

