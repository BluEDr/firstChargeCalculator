function validateForm() {
    alert("aaa");
    var numberField = document.getElementById("price").value;

    // Use a regular expression to check if it's a real number
    var numberRegex = /^[-+]?[0-9]*\.?[0-9]+$/;

    if (!numberRegex.test(numberField)) {
        // document.getElementById("error").textContent = "Please enter a valid real number.";
        alert("False");
        return false; // Prevent form submission
    }

    // Clear any previous error messages
    // document.getElementById("error").textContent = "";

    // Continue with form submission
    alert("True");
    return true;
}
