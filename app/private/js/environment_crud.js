function error_environment_name() {
    var testable = document.getElementById("test_environment_name");
    if (!testable.checkValidity()) {
        document.getElementById("error_environment_name").innerHTML = "De omgevingsnaam moet minimaal 3 karakters bevatten";
    } else {
        document.getElementById("error_environment_name").innerHTML = "";
    }
}

function error_description() {
    var testable = document.getElementById("test_description");
    if (!testable.checkValidity()) {
        document.getElementById("error_description").innerHTML = "De omschrijving mag maximaal 225 tekens lang zijn";
    } else {
        document.getElementById("error_description").innerHTML = "";
    }
}
