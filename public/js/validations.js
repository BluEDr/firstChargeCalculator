function validateForm() {
    var numberField = document.getElementById("price").value;

    // Use a regular expression to check if it's a real number
    var numberRegex = /^[-+]?[0-9]*\.?[0-9]+$/;

    if (!numberRegex.test(numberField)) {
        alert("False");
        return false; // Prevent form submission
    }

    alert("True");
    // window.location.href = "../resources/views/home2";
    // location.replace("./home2");
    return true;
}
